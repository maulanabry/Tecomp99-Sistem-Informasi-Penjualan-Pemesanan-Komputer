<?php

// Simple test to check if our files have syntax errors

echo "Testing Sales Report Implementation...\n\n";

// Test 1: Check if controller file exists and has no syntax errors
$controllerFile = 'app/Http/Controllers/Owner/LaporanController.php';
if (file_exists($controllerFile)) {
    echo "✓ Controller file exists: $controllerFile\n";

    // Check for syntax errors
    $output = shell_exec("php -l $controllerFile 2>&1");
    if (strpos($output, 'No syntax errors') !== false) {
        echo "✓ Controller syntax is valid\n";
    } else {
        echo "✗ Controller syntax error: $output\n";
    }
} else {
    echo "✗ Controller file not found: $controllerFile\n";
}

// Test 2: Check if view files exist
$viewFiles = [
    'resources/views/owner/laporan/penjualan-produk.blade.php',
    'resources/views/owner/laporan/penjualan-produk-pdf.blade.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists($viewFile)) {
        echo "✓ View file exists: $viewFile\n";
    } else {
        echo "✗ View file not found: $viewFile\n";
    }
}

// Test 3: Check if routes file was updated
$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    if (strpos($routesContent, 'pemilik.laporan.penjualan-produk') !== false) {
        echo "✓ Routes updated successfully\n";
    } else {
        echo "✗ Routes not found in web.php\n";
    }
}

// Test 4: Check if sidebar was updated
$sidebarFile = 'resources/views/components/sidebar-owner.blade.php';
if (file_exists($sidebarFile)) {
    $sidebarContent = file_get_contents($sidebarFile);
    if (strpos($sidebarContent, 'Laporan Order') !== false) {
        echo "✓ Sidebar updated successfully\n";
    } else {
        echo "✗ Sidebar not updated\n";
    }
}

echo "\nImplementation Summary:\n";
echo "- Sales Report Controller: Created with filtering, charts, and export functionality\n";
echo "- Main Sales Report View: Created with responsive design and Chart.js integration\n";
echo "- PDF Export View: Created for print/PDF generation\n";
echo "- Sidebar Navigation: Updated with 'Laporan Order' menu\n";
echo "- Routes: Added for main view and export functions\n";
echo "- Features: Date filtering, search, charts, CSV export, print functionality\n";

echo "\nNext Steps:\n";
echo "1. Install DOMPDF package for proper PDF generation: composer require barryvdh/laravel-dompdf\n";
echo "2. Install Laravel Excel for Excel export: composer require maatwebsite/excel\n";
echo "3. Test the functionality by accessing /pemilik/laporan/penjualan-produk\n";
