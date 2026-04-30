import React from "react";
export const Table = ({ className, children, ...props }) => (
  <div className="table " + className {...props}>{children}</div>
);
