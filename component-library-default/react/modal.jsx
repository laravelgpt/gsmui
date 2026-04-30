import React from "react";
export const Modal = ({ className, children, ...props }) => (
  <div className="modal " + className {...props}>{children}</div>
);
