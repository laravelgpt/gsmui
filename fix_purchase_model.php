
<?php

// Fix Purchase model to explicitly enable timestamps for audit
$file = 'app/Models/Purchase.php';
$content = file_get_contents($file);

// Add timestamps declaration after fillable
if (strpos($content, 'public $timestamps') === false) {
    $content = str_replace(
        "    protected \$casts = [\n        'amount' => 'decimal:2',\n        'payment_data' => 'array',\n    ];\n\n    /** RELATIONSHIPS */\n",
        "    protected \$casts = [\n        'amount' => 'decimal:2',\n        'payment_data' => 'array',\n    ];\n\n    /**\n     * Indicates if the model should be timestamped.\n     *\n     * @var bool\n     */\n    public \$timestamps = true;\n\n    /** RELATIONSHIPS */\n",
        $content
    );
    file_put_contents($file, $content);
    echo "✅ Fixed: Purchase model - Added explicit timestamps declaration\n";
}
