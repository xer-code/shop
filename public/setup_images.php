<?php

require_once dirname(__DIR__) . '/config/config.php';

// Target directory
$targetDir = PUBLIC_PATH . '/assets/images/products/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$uploadsDir = PUBLIC_PATH . '/uploads/products/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// 1. Copy generated AI images
$aiImages = [
    'macbook-pro.jpg' => 'C:\\Users\\rexag\\.gemini\\antigravity-ide\\brain\\ffd37602-ae19-4031-b5e0-9c40b3fdac03\\macbook_pro_1784705077695.png',
    'iphone-15.jpg' => 'C:\\Users\\rexag\\.gemini\\antigravity-ide\\brain\\ffd37602-ae19-4031-b5e0-9c40b3fdac03\\iphone_15_1784705091196.png',
    'sony-headphones.jpg' => 'C:\\Users\\rexag\\.gemini\\antigravity-ide\\brain\\ffd37602-ae19-4031-b5e0-9c40b3fdac03\\sony_headphones_1784705102532.png',
    'ps5-pro.jpg' => 'C:\\Users\\rexag\\.gemini\\antigravity-ide\\brain\\ffd37602-ae19-4031-b5e0-9c40b3fdac03\\ps5_pro_1784705117108.png',
    'leather-jacket.jpg' => 'C:\\Users\\rexag\\.gemini\\antigravity-ide\\brain\\ffd37602-ae19-4031-b5e0-9c40b3fdac03\\leather_jacket_1784705129625.png',
];

$copiedCount = 0;
foreach ($aiImages as $destName => $srcPath) {
    if (file_exists($srcPath)) {
        copy($srcPath, $targetDir . $destName);
        copy($srcPath, $uploadsDir . $destName);
        $copiedCount++;
    }
}

// Curated high quality product photo library (Unsplash direct product imagery)
$categoryImages = [
    // Electronics (1)
    1 => [
        'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80', // MacBook Pro
        'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=800&q=80', // iPhone 15
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80', // Headphones
        'https://images.unsplash.com/photo-1593784991095-a205069470b6?auto=format&fit=crop&w=800&q=80', // Smart TV
        'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?auto=format&fit=crop&w=800&q=80', // iPad Air
        'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?auto=format&fit=crop&w=800&q=80', // AirPods
        'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&q=80', // Dell XPS
        'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80', // Camera
        'https://images.unsplash.com/photo-1527977966376-1c8408f9f108?auto=format&fit=crop&w=800&q=80', // Drone
        'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=800&q=80', // Galaxy S24
        'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=800&q=80', // Smartwatch
        'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=800&q=80', // Bose Headphones
        'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=800&q=80', // Monitor
        'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=800&q=80', // Speaker
        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=800&q=80', // Action Cam
        'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?auto=format&fit=crop&w=800&q=80', // VR Headset
    ],
    // Fashion (2)
    2 => [
        'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80', // Leather Jacket
        'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=800&q=80', // Silk Evening Dress
        'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?auto=format&fit=crop&w=800&q=80', // Cashmere Coat
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80', // Swiss Watch
        'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=800&q=80', // Suit
        'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80', // Sunglasses
        'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?auto=format&fit=crop&w=800&q=80', // Oxford Shoes
        'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=800&q=80', // Denim Jeans
        'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80', // White Sneakers
        'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80', // Designer Handbag
    ],
    // Home & Living (3)
    3 => [
        'https://images.unsplash.com/photo-1558317374-067fb5f30001?auto=format&fit=crop&w=800&q=80', // Robot Vacuum
        'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?auto=format&fit=crop&w=800&q=80', // Espresso Machine
        'https://images.unsplash.com/photo-1585771724684-38269d6639fd?auto=format&fit=crop&w=800&q=80', // Air Purifier
        'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80', // Stand Mixer
        'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80', // Memory Foam Bed
        'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80', // Bathroom Accessories
        'https://images.unsplash.com/photo-1580481072645-022f9a6d8310?auto=format&fit=crop&w=800&q=80', // Ergo Office Chair
        'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=800&q=80', // Cookware
    ],
    // Gaming (4)
    4 => [
        'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?auto=format&fit=crop&w=800&q=80', // PS5
        'https://images.unsplash.com/photo-1621259182978-fbf93132d53d?auto=format&fit=crop&w=800&q=80', // Xbox Series X
        'https://images.unsplash.com/photo-1578303512597-81e6cc155b3e?auto=format&fit=crop&w=800&q=80', // Switch
        'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=800&q=80', // Gaming PC
        'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80', // Gaming Controller
        'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=800&q=80', // Gaming Mouse
        'https://images.unsplash.com/photo-1599669454699-248893623440?auto=format&fit=crop&w=800&q=80', // Gaming Headset
        'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80', // Gaming Setup
    ],
    // Automotive (5)
    5 => [
        'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80', // Dash Cam / Car Tech
        'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80', // Supercar / Auto
        'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=800&q=80', // Car Detail / Ceramic
        'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=800&q=80', // Car Interior
        'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=800&q=80', // Sports Car
        'https://images.unsplash.com/photo-1600793575654-910699b5e4d4?auto=format&fit=crop&w=800&q=80', // Auto Parts
    ]
];

// Specific flagship overrides using AI generated images or direct matches
$flagshipImages = [
    'MacBook Pro 16" M3 Max' => '/assets/images/products/macbook-pro.jpg',
    'iPhone 15 Pro Max 256GB' => '/assets/images/products/iphone-15.jpg',
    'Sony WH-1000XM5 Headphones' => '/assets/images/products/sony-headphones.jpg',
    'PlayStation 5 Pro Console' => '/assets/images/products/ps5-pro.jpg',
    'Premium Italian Leather Jacket' => '/assets/images/products/leather-jacket.jpg',
];

// Connect to database directly via PDO
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Update flagship items first
    foreach ($flagshipImages as $title => $imgUrl) {
        $stmt = $db->prepare("UPDATE products SET image_url = ? WHERE title = ?");
        $stmt->execute([$imgUrl, $title]);
    }
    
    // Update all other products by category
    $stmt = $db->query("SELECT id, category_id, title, image_url FROM products");
    $products = $stmt->fetchAll();
    
    $updatedCount = 0;
    foreach ($products as $index => $p) {
        // Skip flagships already updated
        if (isset($flagshipImages[$p['title']])) {
            $updatedCount++;
            continue;
        }
        
        $catId = (int) $p['category_id'];
        $catPool = $categoryImages[$catId] ?? $categoryImages[1];
        $selectedImg = $catPool[$index % count($catPool)];
        
        $updateStmt = $db->prepare("UPDATE products SET image_url = ? WHERE id = ?");
        $updateStmt->execute([$selectedImg, $p['id']]);
        $updatedCount++;
    }
    
    echo "SUCCESS: Successfully updated image URLs for $updatedCount products! (Copied $copiedCount AI flagship images)";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
