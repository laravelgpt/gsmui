
<?php

echo "🚀 UPDATING ALL GSM-UI COMPONENTS...\n\n";

$basePath = __DIR__;
$updatedCount = 0;

// Update all Livewire Volt components
$voltPath = "$basePath/app/Components/Livewire/Volt";
$files = glob("$voltPath/*.php");

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Ensure Midnight Electric theme variables are present
    if (strpos($content, '--electric-blue') === false) {
        // Add theme integration if missing
        $content = str_replace(
            '.gsm-button {',
            ".gsm-button {\n    /* Midnight Electric Theme Integration */\n    --electric-blue: #00D4FF;\n    --toxic-green: #39FF14;\n    --indigo: #6366F1;",
            $content
        );
    }
    
    // Ensure accessibility features
    if (strpos($content, 'focus-visible') === false && strpos($content, 'focus:outline-none') !== false) {
        $content = str_replace(
            'focus:outline-none',
            'focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--electric-blue)]',
            $content
        );
    }
    
    // Ensure reduced motion support
    if (strpos($content, 'prefers-reduced-motion') === false) {
        $content .= "\n\n/* Reduced Motion Support */\n@media (prefers-reduced-motion: reduce) {\n    .gsm-button {\n        transition: none;\n        animation: none;\n    }\n}\n";
    }
    
    file_put_contents($file, $content);
    $updatedCount++;
    echo "✅ Updated: Volt/" . basename($file) . "\n";
}

// Update all Blade components
$bladePath = "$basePath/resources/views/components/blade";
$categories = glob("$bladePath/*", GLOB_ONLYDIR);

foreach ($categories as $category) {
    $categoryName = basename($category);
    $files = glob("$category/*.blade.php");
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Add theme variables
        if (strpos($content, 'theme-{{') === false) {
            // Enhance with theme support
            $content = str_replace(
                '<div class="',
                '<div class="theme-{{ $theme ?? \'default\' }} ', 
                $content
            );
        }
        
        file_put_contents($file, $content);
        $updatedCount++;
        echo "✅ Updated: Blade/$categoryName/" . basename($file) . "\n";
    }
}

// Update React components
$reactPath = "$basePath/app/Components/React/components";
$files = glob("$reactPath/*.jsx");

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Add theme prop
    if (strpos($content, 'theme =') === false) {
        $content = str_replace(
            "variant = 'base', size = 'md'",
            "variant = 'base', size = 'md', theme = 'default'",
            $content
        );
    }
    
    // Add accessibility
    if (strpos($content, 'aria-') === false) {
        $content = str_replace(
            '<div className={className}',
            '<div className={className} role="button" aria-label={title}',
            $content
        );
    }
    
    file_put_contents($file, $content);
    $updatedCount++;
    echo "✅ Updated: React/" . basename($file) . "\n";
}

// Update Vue components
$vuePath = "$basePath/app/Components/Vue/components";
$files = glob("$vuePath/*.vue");

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Add theme prop
    if (strpos($content, 'theme:') === false) {
        $content = str_replace(
            "size: { type: String, default: 'md' },",
            "size: { type: String, default: 'md' },\n    theme: { type: String, default: 'default' },",
            $content
        );
    }
    
    file_put_contents($file, $content);
    $updatedCount++;
    echo "✅ Updated: Vue/" . basename($file) . "\n";
}

// Update all stubs
$stubPath = "$basePath/stubs";
$files = glob("$stubPath/*.stub");

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Ensure theme variables in stubs
    if (strpos($content, 'var(--electric-blue)') === false) {
        $content .= "\n\n/* Theme Variables */\n:root {\n    --electric-blue: #00D4FF;\n    --toxic-green: #39FF14;\n    --indigo: #6366F1;\n    --deep-space: #0B0F19;\n}\n";
    }
    
    file_put_contents($file, $content);
    $updatedCount++;
    echo "✅ Updated: Stub/" . basename($file) . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "✅ UPDATE COMPLETE!\n";
echo "Total Components Updated: $updatedCount\n";
echo str_repeat('=', 50) . "\n";

