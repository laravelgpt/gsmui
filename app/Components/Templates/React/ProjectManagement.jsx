import React from 'react';
import PropTypes from 'prop-types';
import { gsmButton } from './components/Button';

const ProjectManagement = ({ title, subtitle, features = [] }) => {
    return (
        <div className="template-project-management min-h-screen">
            {/* Hero Section */}
            <section className="hero-section bg-[var(--deep-space)] py-20">
                <div className="container mx-auto px-4">
                    <div className="text-center max-w-4xl mx-auto">
                        <h1 className="text-5xl md:text-7xl font-bold mb-6 glow-blue">
                            {title || 'ProjectManagement'}
                        </h1>
                        <p className="text-xl md:text-2xl text-gray-400 mb-8">
                            {subtitle || 'Task and project tracking system'}
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <gsmButton
                                label="Get Started"
                                variant="primary"
                                size="lg"
                                className="min-w-[160px]"
                            />
                            <gsmButton
                                label="Learn More"
                                variant="ghost"
                                size="lg"
                                className="min-w-[160px]"
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section className="features-section py-20 bg-[rgba(19,24,40,0.5)]">
                <div className="container mx-auto px-4">
                    <h2 className="text-3xl md:text-4xl font-bold text-center mb-12 glow-green">
                        Powerful Features
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {features.map((feature, index) => (
                            <div key={index} className="feature-card glass-card p-6 text-center">
                                <div className="w-16 h-16 mx-auto mb-4 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center">
                                    <svg className="w-8 h-8 text-[#00D4FF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                        <polyline points="2 17 12 22 22 17"></polyline>
                                        <polyline points="2 12 12 17 22 12"></polyline>
                                    </svg>
                                </div>
                                <h3 className="text-xl font-bold mb-2">{feature.title}</h3>
                                <p className="text-gray-400">{feature.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="cta-section py-20">
                <div className="container mx-auto px-4">
                    <div className="glass-card p-12 text-center">
                        <h2 className="text-3xl md:text-4xl font-bold mb-6">Ready to Get Started?</h2>
                        <p className="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                            Join thousands of satisfied users today.
                        </p>
                        <gsmButton
                            label="Start Free Trial"
                            variant="primary"
                            size="lg"
                            className="min-w-[200px]"
                        />
                    </div>
                </div>
            </section>
        </div>
    );
};

ProjectManagement.propTypes = {
    title: PropTypes.string,
    subtitle: PropTypes.string,
    features: PropTypes.arrayOf(
        PropTypes.shape({
            title: PropTypes.string.isRequired,
            description: PropTypes.string.isRequired,
        })
    ),
};

ProjectManagement.defaultProps = {
    title: 'ProjectManagement',
    subtitle: 'Task and project tracking system',
    features: [
        { title: 'Feature One', description: 'Amazing feature description' },
        { title: 'Feature Two', description: 'Another great feature' },
        { title: 'Feature Three', description: 'One more awesome feature' },
    ],
};

export default ProjectManagement;
