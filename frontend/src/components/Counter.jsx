import React, {useState} from "react";

const Counter = () => {
  const [hits, setHits] = useState(0);

  function inc() {
    setHits(hits + 1);
  }

  return (
    <div>
      <h1>Hits - {hits}</h1>
      <button onClick={inc} className="btn">Hit</button>
    </div>
  );
};

export default Counter;
