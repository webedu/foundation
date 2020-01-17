import React from "react";
import "./Token1.css";

function Token1(props) {
  return React.createElement(
    "div",
     { className: "Token1" },
     React.createElement("span", null, props.title)
    );
}

export default Token1;