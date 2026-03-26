<?php
$files = ['overview', 'doctors', 'secretaries', 'patients', 'settings'];

foreach ($files as $name) {
    $path = "resources/views/admin/$name.blade.php";
    if (!file_exists($path)) {
        echo "Missing $path\n";
        continue;
    }
    
    $content = file_get_contents($path);
    
    // Extract everything inside <main class="..."> ... </main>
    if (preg_match('/<main[^>]*>(.*?)<\/main>/is', $content, $matches)) {
        $mainContent = trim($matches[1]);
        
        $bladeContent = "@extends('admin.layout')\n\n@section('title', 'Admin - " . ucfirst($name) . "')\n\n@section('content')\n" . $mainContent . "\n@endsection\n";
        
        file_put_contents($path, $bladeContent);
        echo "Formatted $name\n";
    } else {
        echo "Failed to find <main> in $name\n";
    }
}
