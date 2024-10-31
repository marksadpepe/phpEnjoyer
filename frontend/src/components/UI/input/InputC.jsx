import React from "react";
import classes from "./InputC.module.css";

const InputC = (props) => {
  return (
    <input
      className={classes.itC}
      {...props}
    />
  );
};

export default InputC;
