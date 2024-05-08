<?php
namespace Blog\Services;

class Signer {
  private static string $secret = "aboba";
  private static array $header = ["alg" => "HS256", "typ" => "JWT"];

  public static function sign(array $payload, string $exp): string {
    $split = str_split($exp);
    $value = (int)implode("", [$split[0], $split[1]]);
    $t = $split[count($split) - 1];

    switch ($t) {
    case "m":
      $value = $value * 60;
      break;
    case "h":
      $value = $value * 60 * 60;
      break;
    case "d":
      $value = $value * 24 * 60 * 60;
      break;
    default:
      throw new \ValueError("Expiration time must be in minutes/hours/days (m/h/d)");

    }
    $payload["exp"] = time() + $value;

    $header_encode = self::encode(json_encode(self::$header));
    $payload_encode = self::encode(json_encode($payload));
    $signature = self::encode(hash_hmac("sha256", "{$header_encode}.{$payload_encode}", self::$secret, true));

    return "{$header_encode}.{$payload_encode}.{$signature}";
  }

  public static function verify(string $token): ?array {
    [$header, $payload, $signature] = explode(".", $token);
    $decoded_payload = json_decode(self::decode($payload), true);
    $signature_to_check = self::encode(hash_hmac("sha256", "{$header}.{$payload}", self::$secret, true));

    if ($signature == $signature_to_check && isset($decoded_payload["exp"]) && $decoded_payload["exp"] >= time()) {
      unset($decoded_payload["exp"]);
      return $decoded_payload;
    }

    return null;
  }

  private static function encode(string $data): string {
    return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
  }

  private static function decode(string $data): string {
    return base64_decode(strtr($data, "-_", "+/"));
  }
}
?>
