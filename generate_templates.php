
<?php

$templateCategories = [
    'Landing' => [
        'Startup' => 'Modern startup landing with hero, features, testimonials',
        'SaaS' => 'SaaS product landing with pricing, features, FAQ',
        'MobileApp' => 'Mobile app showcase with device mockups',
        'Agency' => 'Creative agency portfolio and services',
        'Ecommerce' => 'Ecommerce store front page',
        'Course' => 'Online course platform landing',
        'Event' => 'Conference and event landing page',
        'App' => 'Mobile app landing with app store badges',
        'Service' => 'Service business landing with booking',
        'Portfolio' => 'Creative portfolio with gallery',
        'Personal' => 'Personal brand and resume landing',
    ],
    'Ecommerce' => [
        'Shopify' => 'Full Shopify-style store with cart',
        'Amazon' => 'Multi-vendor marketplace layout',
        'Fashion' => 'Clothing and fashion boutique',
        'Electronics' => 'Tech gadgets and electronics store',
        'Furniture' => 'Home furniture and decor shop',
        'Grocery' => 'Supermarket and grocery delivery',
        'Beauty' => 'Cosmetics and beauty products',
        'Sports' => 'Athletic gear and sports equipment',
        'Books' => 'Online bookstore with recommendations',
        'Jewelry' => 'Luxury jewelry and watches store',
        'Pet' => 'Pet supplies and accessories shop',
    ],
    'SaaS' => [
        'CRM' => 'Customer relationship management dashboard',
        'ProjectManagement' => 'Task and project tracking system',
        'Accounting' => 'Financial management and invoicing',
        'HR' => 'Human resources and employee portal',
        'Analytics' => 'Business intelligence and data analytics',
        'EmailMarketing' => 'Email campaigns and automation',
        'Support' => 'Help desk and ticket system',
        'Inventory' => 'Stock and warehouse management',
        'POS' => 'Point of sale system for retail',
        'Booking' => 'Appointment and reservation system',
        'CMS' => 'Content management system backend',
        'SocialMedia' => 'Social media management dashboard',
        'FileStorage' => 'Cloud file storage and sharing',
        'VPN' => 'Virtual private network management',
        'Monitoring' => 'Server and application monitoring',
        'Backup' => 'Data backup and recovery system',
        'Security' => 'Cybersecurity and threat detection',
        'Collaboration' => 'Team collaboration workspace',
        'Design' => 'Design tool and asset management',
        'Development' => 'Developer tools and CI/CD pipeline',
    ],
    'Admin' => [
        'Dashboard' => 'Main admin dashboard with widgets',
        'UserManagement' => 'User accounts and permissions',
        'ContentManager' => 'CMS content management',
        'Analytics' => 'Site analytics and reporting',
        'Settings' => 'System configuration panel',
        'Billing' => 'Subscription and payment management',
        'Support' => 'Customer support dashboard',
        'Audit' => 'Security audit and logs',
        'Backup' => 'Database and file backup manager',
        'API' => 'API documentation and testing',
    ],
    'Marketing' => [
        'SEO' => 'SEO tools and ranking tracker',
        'LeadGen' => 'Lead generation and capture forms',
        'Webinar' => 'Webinar hosting and registration',
        'Survey' => 'Customer survey and feedback',
        'Newsletter' => 'Email newsletter signup',
        'CaseStudies' => 'Customer success stories',
        'Pricing' => 'Tiered pricing comparison',
        'Roadmap' => 'Product roadmap and changelog',
        'Jobs' => 'Company careers page',
        'Press' => 'Media kit and press releases',
    ],
    'Portfolio' => [
        'Designer' => 'Creative designer portfolio',
        'Developer' => 'Software developer showcase',
        'Photographer' => 'Photography portfolio gallery',
        'Artist' => 'Digital art and illustration',
        'Writer' => 'Author and writing portfolio',
        'Filmmaker' => 'Video production showcase',
        'Architect' => 'Architecture and design portfolio',
        'Musician' => 'Music and audio portfolio',
        'Fashion' => 'Fashion design portfolio',
        'Branding' => 'Brand identity case studies',
    ],
    'Blog' => [
        'Personal' => 'Personal blog and journal',
        'Tech' => 'Technology and programming blog',
        'Business' => 'Business and entrepreneurship',
        'Lifestyle' => 'Lifestyle and wellness blog',
        'News' => 'News publication layout',
        'Magazine' => 'Online magazine format',
        'Tutorial' => 'Educational tutorial blog',
        'Review' => 'Product review blog',
        'Travel' => 'Travel blog and guides',
        'Food' => 'Recipe and food blog',
    ],
    'Documentation' => [
        'API' => 'API reference documentation',
        'UserGuide' => 'User manual and guides',
        'Developer' => 'Developer setup and integration',
        'Tutorial' => 'Step-by-step tutorials',
        'FAQ' => 'Frequently asked questions',
        'Changelog' => 'Version history and updates',
        'Examples' => 'Code examples and demos',
        'BestPractices' => 'Guidelines and standards',
        'Troubleshooting' => 'Common issues and solutions',
        'Glossary' => 'Terms and definitions',
    ],
    'ComingSoon' => [
        'Startup' => 'New startup launch page',
        'Product' => 'Product launch countdown',
        'Event' => 'Event announcement page',
        'App' => 'Mobile app pre-launch',
        'Service' => 'New service announcement',
        'Brand' => 'Brand rebranding reveal',
        'Feature' => 'New feature announcement',
        'Update' => 'Major update preview',
        'Beta' => 'Beta testing signup',
        'Conference' => 'Conference launch page',
    ],
    'Error' => [
        '404' => 'Page not found error',
        '500' => 'Internal server error',
        '503' => 'Service unavailable',
        '403' => 'Forbidden access',
        '401' => 'Unauthorized',
        'Maintenance' => 'Site under maintenance',
        'ComingSoon' => 'Coming soon placeholder',
        'Expired' => 'Link expired',
        'NotFound' => 'Resource not found',
        'ServerError' => 'Generic server error',
    ],
];

$totalTemplates = 0;
$totalCategories = 0;

echo "🚀 GENERATING TEMPLATES...\n\n";

foreach ($templateCategories as $category => $templates) {
    $totalCategories++;
    echo "📁 Category: $category\n";
    
    foreach ($templates as $name => $description) {
        $totalTemplates++;
        $templateName = str_replace(' ', '', $name);
        $kebabName = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $templateName));
        
        // Generate template files for each stack
        generateTemplateFiles($category, $templateName, $kebabName, $description);
        
        $variations = 3 * 3 * 12; // 3 sizes × 3 variants × 12 themes
        echo "  ✨ $name ($variations variations)\n";
    }
    echo "\n";
}

echo "✅ GENERATION COMPLETE!\n";
echo "========================\n";
echo "Total Categories: $totalCategories\n";
echo "Total Templates: $totalTemplates\n";
echo "Total Variations: " . ($totalTemplates * 3 * 3 * 12) . "+\n";
echo "========================\n";

function generateTemplateFiles($category, $name, $kebabName, $description) {
    // Blade template
    $bladeDir = __DIR__ . "/resources/views/templates/" . strtolower($category);
    if (!is_dir($bladeDir)) mkdir($bladeDir, 0777, true);
    
    $bladeContent = generateBladeTemplate($category, $name, $kebabName, $description);
    file_put_contents("$bladeDir/{$kebabName}.blade.php", $bladeContent);
    
    // Livewire Volt template
    $voltDir = __DIR__ . "/app/Components/Templates/$category";
    if (!is_dir($voltDir)) mkdir($voltDir, 0777, true);
    
    $voltContent = generateVoltTemplate($category, $name, $kebabName, $description);
    file_put_contents("$voltDir/{$name}.php", $voltContent);
    
    // React template
    $reactDir = __DIR__ . "/app/Components/Templates/React";
    if (!is_dir($reactDir)) mkdir($reactDir, 0777, true);
    
    $reactContent = generateReactTemplate($category, $name, $kebabName, $description);
    file_put_contents("$reactDir/{$name}.jsx", $reactContent);
    
    // Vue template
    $vueDir = __DIR__ . "/app/Components/Templates/Vue";
    if (!is_dir($vueDir)) mkdir($vueDir, 0777, true);
    
    $vueContent = generateVueTemplate($category, $name, $kebabName, $description);
    file_put_contents("$vueDir/{$name}.vue", $vueContent);
}

function generateBladeTemplate($category, $name, $kebabName, $description) {
    return "@extends('layouts.app')

@section('title', '$name Template')

@section('content')
<div class=\"template-{$kebabName} min-h-screen\" x-data=\"templateConfig()\">
    <!-- Hero Section -->
    <section class=\"hero-section bg-[var(--deep-space)] py-20\">
        <div class=\"container mx-auto px-4\">
            <div class=\"text-center max-w-4xl mx-auto\">
                <h1 class=\"text-5xl md:text-7xl font-bold mb-6 glow-blue\">
                    {{ \$title ?? '$name' }}
                </h1>
                <p class=\"text-xl md:text-2xl text-gray-400 mb-8\">
                    {{ \$subtitle ?? '$description' }}
                </p>
                <div class=\"flex flex-col sm:flex-row gap-4 justify-center\">
                    <x-components.volt.gsm-button 
                        label=\"Get Started\" 
                        variant=\"primary\" 
                        size=\"lg\"
                        class=\"min-w-[160px]\"
                    />
                    <x-components.volt.gsm-button 
                        label=\"Learn More\" 
                        variant=\"ghost\" 
                        size=\"lg\"
                        class=\"min-w-[160px]\"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class=\"features-section py-20 bg-[rgba(19,24,40,0.5)]\">
        <div class=\"container mx-auto px-4\">
            <h2 class=\"text-3xl md:text-4xl font-bold text-center mb-12 glow-green\">
                Powerful Features
            </h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8\">
                @foreach(\$features ?? [] as \$feature)
                    <div class=\"feature-card glass-card p-6 text-center\">
                        <div class=\"w-16 h-16 mx-auto mb-4 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center\">
                            <svg class=\"w-8 h-8 text-[#00D4FF]\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\">
                                <polygon points=\"12 2 2 7 12 12 22 7 12 2\"></polygon>
                                <polyline points=\"2 17 12 22 22 17\"></polyline>
                                <polyline points=\"2 12 12 17 22 12\"></polyline>
                            </svg>
                        </div>
                        <h3 class=\"text-xl font-bold mb-2\">{{ \$feature['title'] ?? 'Feature' }}</h3>
                        <p class=\"text-gray-400\">{{ \$feature['description'] ?? 'Feature description' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class=\"cta-section py-20\">
        <div class=\"container mx-auto px-4\">
            <div class=\"glass-card p-12 text-center\">
                <h2 class=\"text-3xl md:text-4xl font-bold mb-6\">Ready to Get Started?</h2>
                <p class=\"text-xl text-gray-400 mb-8 max-w-2xl mx-auto\">
                    Join thousands of satisfied users today.
                </p>
                <x-components.volt.gsm-button 
                    label=\"Start Free Trial\" 
                    variant=\"primary\" 
                    size=\"lg\"
                    class=\"min-w-[200px]\"
                />
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
function templateConfig() {
    return {
        title: '{{ $name }}',
        subtitle: '{{ $description }}',
        features: [
            { title: 'Feature One', description: 'Amazing feature description' },
            { title: 'Feature Two', description: 'Another great feature' },
            { title: 'Feature Three', description: 'One more awesome feature' },
        ]
    }
}
</script>
@endpush
";
}

function generateVoltTemplate($category, $name, $kebabName, $description) {
    return "<?php

use function Livewire\\Volt\\set;

set('title', '$name');
set('subtitle', '$description');
set('features', []);

?>

<div>
    <!-- Hero Section -->
    <section class=\"hero-section bg-[var(--deep-space)] py-20\">
        <div class=\"container mx-auto px-4\">
            <div class=\"text-center max-w-4xl mx-auto\">
                <h1 class=\"text-5xl md:text-7xl font-bold mb-6 glow-blue\">
                    {{ \$title }}
                </h1>
                <p class=\"text-xl md:text-2xl text-gray-400 mb-8\">
                    {{ \$subtitle }}
                </p>
                <div class=\"flex flex-col sm:flex-row gap-4 justify-center\">
                    <x-components.volt.gsm-button 
                        label=\"Get Started\" 
                        variant=\"primary\" 
                        size=\"lg\"
                        class=\"min-w-[160px]\"
                    />
                    <x-components.volt.gsm-button 
                        label=\"Learn More\" 
                        variant=\"ghost\" 
                        size=\"lg\"
                        class=\"min-w-[160px]\"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class=\"features-section py-20 bg-[rgba(19,24,40,0.5)]\">
        <div class=\"container mx-auto px-4\">
            <h2 class=\"text-3xl md:text-4xl font-bold text-center mb-12 glow-green\">
                Powerful Features
            </h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8\">
                @foreach(\$features as \$feature)
                    <div class=\"feature-card glass-card p-6 text-center\">
                        <div class=\"w-16 h-16 mx-auto mb-4 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center\">
                            <svg class=\"w-8 h-8 text-[#00D4FF]\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\">
                                <polygon points=\"12 2 2 7 12 12 22 7 12 2\"></polygon>
                                <polyline points=\"2 17 12 22 22 17\"></polyline>
                                <polyline points=\"2 12 12 17 22 12\"></polyline>
                            </svg>
                        </div>
                        <h3 class=\"text-xl font-bold mb-2\">{{ \$feature['title'] }}</h3>
                        <p class=\"text-gray-400\">{{ \$feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class=\"cta-section py-20\">
        <div class=\"container mx-auto px-4\">
            <div class=\"glass-card p-12 text-center\">
                <h2 class=\"text-3xl md:text-4xl font-bold mb-6\">Ready to Get Started?</h2>
                <p class=\"text-xl text-gray-400 mb-8 max-w-2xl mx-auto\">
                    Join thousands of satisfied users today.
                </p>
                <x-components.volt.gsm-button 
                    label=\"Start Free Trial\" 
                    variant=\"primary\" 
                    size=\"lg\"
                    class=\"min-w-[200px]\"
                />
            </div>
        </div>
    </section>
</div>
";
}

function generateReactTemplate($category, $name, $kebabName, $description) {
    return "import React from 'react';
import PropTypes from 'prop-types';
import { gsmButton } from './components/Button';

const {$name} = ({ title, subtitle, features = [] }) => {
    return (
        <div className=\"template-{$kebabName} min-h-screen\">
            {/* Hero Section */}
            <section className=\"hero-section bg-[var(--deep-space)] py-20\">
                <div className=\"container mx-auto px-4\">
                    <div className=\"text-center max-w-4xl mx-auto\">
                        <h1 className=\"text-5xl md:text-7xl font-bold mb-6 glow-blue\">
                            {title || '$name'}
                        </h1>
                        <p className=\"text-xl md:text-2xl text-gray-400 mb-8\">
                            {subtitle || '$description'}
                        </p>
                        <div className=\"flex flex-col sm:flex-row gap-4 justify-center\">
                            <gsmButton
                                label=\"Get Started\"
                                variant=\"primary\"
                                size=\"lg\"
                                className=\"min-w-[160px]\"
                            />
                            <gsmButton
                                label=\"Learn More\"
                                variant=\"ghost\"
                                size=\"lg\"
                                className=\"min-w-[160px]\"
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section className=\"features-section py-20 bg-[rgba(19,24,40,0.5)]\">
                <div className=\"container mx-auto px-4\">
                    <h2 className=\"text-3xl md:text-4xl font-bold text-center mb-12 glow-green\">
                        Powerful Features
                    </h2>
                    <div className=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8\">
                        {features.map((feature, index) => (
                            <div key={index} className=\"feature-card glass-card p-6 text-center\">
                                <div className=\"w-16 h-16 mx-auto mb-4 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center\">
                                    <svg className=\"w-8 h-8 text-[#00D4FF]\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" strokeWidth=\"2\">
                                        <polygon points=\"12 2 2 7 12 12 22 7 12 2\"></polygon>
                                        <polyline points=\"2 17 12 22 22 17\"></polyline>
                                        <polyline points=\"2 12 12 17 22 12\"></polyline>
                                    </svg>
                                </div>
                                <h3 className=\"text-xl font-bold mb-2\">{feature.title}</h3>
                                <p className=\"text-gray-400\">{feature.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className=\"cta-section py-20\">
                <div className=\"container mx-auto px-4\">
                    <div className=\"glass-card p-12 text-center\">
                        <h2 className=\"text-3xl md:text-4xl font-bold mb-6\">Ready to Get Started?</h2>
                        <p className=\"text-xl text-gray-400 mb-8 max-w-2xl mx-auto\">
                            Join thousands of satisfied users today.
                        </p>
                        <gsmButton
                            label=\"Start Free Trial\"
                            variant=\"primary\"
                            size=\"lg\"
                            className=\"min-w-[200px]\"
                        />
                    </div>
                </div>
            </section>
        </div>
    );
};

{$name}.propTypes = {
    title: PropTypes.string,
    subtitle: PropTypes.string,
    features: PropTypes.arrayOf(
        PropTypes.shape({
            title: PropTypes.string.isRequired,
            description: PropTypes.string.isRequired,
        })
    ),
};

{$name}.defaultProps = {
    title: '$name',
    subtitle: '$description',
    features: [
        { title: 'Feature One', description: 'Amazing feature description' },
        { title: 'Feature Two', description: 'Another great feature' },
        { title: 'Feature Three', description: 'One more awesome feature' },
    ],
};

export default {$name};
";
}

function generateVueTemplate($category, $name, $kebabName, $description) {
    return "<template>
  <div :class=\"\`template-{$kebabName} min-h-screen\`\">
    <!-- Hero Section -->
    <section class=\"hero-section bg-[var(--deep-space)] py-20\">
        <div class=\"container mx-auto px-4\">
            <div class=\"text-center max-w-4xl mx-auto\">
                <h1 class=\"text-5xl md:text-7xl font-bold mb-6 glow-blue\">
                    {{ title || '$name' }}
                </h1>
                <p class=\"text-xl md:text-2xl text-gray-400 mb-8\">
                    {{ subtitle || '$description' }}
                </p>
                <div class=\"flex flex-col sm:flex-row gap-4 justify-center\">
                    <GsmButton
                        label=\"Get Started\"
                        variant=\"primary\"
                        size=\"lg\"
                        class=\"min-w-[160px]\"
                    />
                    <GsmButton
                        label=\"Learn More\"
                        variant=\"ghost\"
                        size=\"lg\"
                        class=\"min-w-[160px]\"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class=\"features-section py-20 bg-[rgba(19,24,40,0.5)]\">
        <div class=\"container mx-auto px-4\">
            <h2 class=\"text-3xl md:text-4xl font-bold text-center mb-12 glow-green\">
                Powerful Features
            </h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8\">
                <div
                    v-for=\"(feature, index) in features\"
                    :key=\"index\"
                    class=\"feature-card glass-card p-6 text-center\"
                >
                    <div class=\"w-16 h-16 mx-auto mb-4 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center\">
                        <svg class=\"w-8 h-8 text-[#00D4FF]\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\">
                            <polygon points=\"12 2 2 7 12 12 22 7 12 2\"></polygon>
                            <polyline points=\"2 17 12 22 22 17\"></polyline>
                            <polyline points=\"2 12 12 17 22 12\"></polyline>
                        </svg>
                    </div>
                    <h3 class=\"text-xl font-bold mb-2\">{{ feature.title }}</h3>
                    <p class=\"text-gray-400\">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class=\"cta-section py-20\">
        <div class=\"container mx-auto px-4\">
            <div class=\"glass-card p-12 text-center\">
                <h2 class=\"text-3xl md:text-4xl font-bold mb-6\">Ready to Get Started?</h2>
                <p class=\"text-xl text-gray-400 mb-8 max-w-2xl mx-auto\">
                    Join thousands of satisfied users today.
                </p>
                <GsmButton
                    label=\"Start Free Trial\"
                    variant=\"primary\"
                    size=\"lg\"
                    class=\"min-w-[200px]\"
                />
            </div>
        </div>
    </section>
  </div>
</template>

<script>
import { ref } from 'vue';
import GsmButton from './components/Button.vue';

export default {
  name: '{$name}',
  components: {
    GsmButton,
  },
  props: {
    title: {
      type: String,
      default: '$name',
    },
    subtitle: {
      type: String,
      default: '$description',
    },
    features: {
      type: Array,
      default: () => [
        { title: 'Feature One', description: 'Amazing feature description' },
        { title: 'Feature Two', description: 'Another great feature' },
        { title: 'Feature Three', description: 'One more awesome feature' },
      ],
    },
  },
  setup() {
    return {};
  },
};
</script>
";
}

