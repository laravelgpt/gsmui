<?php
// Test the gsm-button example rendering
$example = <<<'BLADE'
<gsm-button
 label="Delete Account"
 variant="danger"
 size="sm"
/>
BLADE;

echo "GSM-UI Button Component Example:\n";
echo "===================================\n\n";
echo $example . "\n\n";
echo "This will render as:\n";
echo "- Variant: danger (glowing red)\n";
echo "- Size: sm (36px height)\n";
echo "- Label: Delete Account\n";
echo "- Style: Glassmorphism with neon red glow\n";
