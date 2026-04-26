import React from 'react';
import PropTypes from 'prop-types';

const Sidebar = ({ title, content, variant = 'base', size = 'md', theme = 'default', disabled = false, children, ...props }) => {
    const className = \`Navigation-Sidebar theme-${theme} size-${size} variant-${variant}${disabled ? ' disabled' : ''}\`;

    return (
        <div className={className} role="button" aria-label={title} {...props}>
            {title && <div className="font-bold mb-2">{title}</div>}
            {content && <div className="content">{content}</div>}
            {children}
        </div>
    );
};

Sidebar.propTypes = {
    title: PropTypes.string,
    content: PropTypes.node,
    variant: PropTypes.string,
    size: PropTypes.string,
    theme: PropTypes.string,
    disabled: PropTypes.bool,
    children: PropTypes.node,
};

export default Sidebar;
