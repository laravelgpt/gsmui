import React from "react";
export const Input = ({ className, children, ...props }) => (
  <div className="input " + className {...props}>{children}</div>
);
