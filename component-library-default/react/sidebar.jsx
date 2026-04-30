import React from "react";
export const Sidebar = ({ className, children, ...props }) => (
  <div className="sidebar " + className {...props}>{children}</div>
);
