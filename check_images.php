<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MenuItem;

echo "Checking menu_items for problematic image URLs...\n";
echo str_repeat("=", 60) . "\n";

$items = MenuItem::select('id', 'name', 'image_url')->get();

$problematic = [];

foreach ($items as $item) {
    $imageUrl = $item->image_url;
    
    // Check for problematic patterns
    if (str_contains($imageUrl, '${') || str_contains($imageUrl, 'placeholder') || str_contains($imageUrl, '150x100')) {
        $problematic[] = [
            'id' => $item->id,
            'name' => $item->name,
            'image_url' => $imageUrl
        ];
    }
}

if (empty($problematic)) {
    echo "No problematic image URLs found in database.\n";
} else {
    echo "Found " . count($problematic) . " problematic image URLs:\n\n";
    foreach ($problematic as $p) {
        echo "ID: {$p['id']}\n";
        echo "Name: {$p['name']}\n";
        echo "Image URL: {$p['image_url']}\n";
        echo str_repeat("-", 40) . "\n";
    }
}

echo "\nSample of first 5 menu items:\n";
echo str_repeat("=", 60) . "\n";
$sample = MenuItem::select('id', 'name', 'image_url')->limit(5)->get();
foreach ($sample as $item) {
    echo "ID: {$item->id}, Name: {$item->name}, Image: " . ($item->image_url ?: 'NULL') . "\n";
}
