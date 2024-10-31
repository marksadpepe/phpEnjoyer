import React from "react";
import classes from "./ButtonC.module.css";

const ButtonC = (props) => {
  return (
    <button {...props} className={classes.btnC}>
      {props.children}
    </button>
  );
};

export default ButtonC;
