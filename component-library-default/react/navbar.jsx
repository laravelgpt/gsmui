import React from "react";
export const Navbar = ({ className, children, ...props }) => (
  <div className="navbar " + className {...props}>{children}</div>
);
