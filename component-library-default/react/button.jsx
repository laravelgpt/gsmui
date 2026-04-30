import React from "react";
export const Button = ({ className, children, ...props }) => (
  <div className="button " + className {...props}>{children}</div>
);
