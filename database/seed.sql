-- =============================================
-- ShopX Global — Seed Data
-- =============================================

USE shopx_global;

-- =============================================
-- Users (password for all: "password123")
-- =============================================
INSERT INTO users (name, email, password_hash, role, wallet_balance) VALUES
('Admin User', 'admin@shopx.com', '$2y$10$jJnl.h2UT0X3a/f6wcPOGOcvD7GkdC53L6F2WrhGRS708yjmbloB2', 'admin', 10000.00),
('John Customer', 'john@example.com', '$2y$10$jJnl.h2UT0X3a/f6wcPOGOcvD7GkdC53L6F2WrhGRS708yjmbloB2', 'customer', 250.00),
('Jane Shopper', 'jane@example.com', '$2y$10$jJnl.h2UT0X3a/f6wcPOGOcvD7GkdC53L6F2WrhGRS708yjmbloB2', 'customer', 500.00);

-- =============================================
-- Categories
-- =============================================
INSERT INTO categories (name, slug, icon) VALUES
('Electronics', 'electronics', '💻'),
('Fashion', 'fashion', '👗'),
('Home & Living', 'home-living', '🏠'),
('Gaming', 'gaming', '🎮'),
('Automotive', 'automotive', '🚗');

-- =============================================
-- Products (200 products across 5 categories)
-- =============================================

-- ELECTRONICS (40 products)
INSERT INTO products (category_id, title, description, image_url, price, original_price, stock, rating, review_count, is_hot, discount_percent) VALUES
(1, 'MacBook Pro 16" M3 Max', 'Ultimate performance laptop with M3 Max chip, 36GB unified memory, and stunning Liquid Retina XDR display.', '/assets/images/products/macbook-pro.jpg', 3499.00, 3799.00, 25, 4.9, 2847, 1, 8),
(1, 'iPhone 15 Pro Max 256GB', 'Titanium design, A17 Pro chip, 48MP camera system with 5x optical zoom.', '/assets/images/products/iphone-15.jpg', 1199.00, 1299.00, 150, 4.8, 5621, 1, 8),
(1, 'Sony WH-1000XM5 Headphones', 'Industry-leading noise cancellation with exceptional sound quality and 30-hour battery.', '/assets/images/products/sony-headphones.jpg', 348.00, 399.99, 85, 4.7, 3214, 1, 13),
(1, 'Samsung 65" Neo QLED 4K TV', 'Quantum Matrix Technology with Neural Quantum Processor for breathtaking picture quality.', '/assets/images/products/samsung-tv.jpg', 1797.99, 2199.99, 30, 4.6, 1892, 1, 18),
(1, 'iPad Air M2 11"', 'Supercharged by M2 chip with stunning Liquid Retina display and all-day battery life.', '/assets/images/products/ipad-air.jpg', 599.00, 649.00, 120, 4.7, 2156, 0, 8),
(1, 'AirPods Pro 2nd Gen', 'Active Noise Cancellation, Adaptive Transparency, and Personalized Spatial Audio.', '/assets/images/products/airpods-pro.jpg', 229.00, 249.00, 200, 4.8, 8934, 1, 8),
(1, 'Dell XPS 15 Laptop', '15.6" OLED display, Intel Core i9, 32GB RAM, 1TB SSD for professionals.', '/assets/images/products/dell-xps.jpg', 1899.00, 2199.00, 40, 4.5, 1567, 0, 14),
(1, 'Canon EOS R6 Mark II', 'Full-frame mirrorless camera with 24.2MP sensor and 40fps continuous shooting.', '/assets/images/products/canon-r6.jpg', 2299.00, 2499.00, 20, 4.8, 987, 0, 8),
(1, 'DJI Mini 4 Pro Drone', 'Ultra-lightweight drone with 4K/60fps HDR video and omnidirectional obstacle sensing.', '/assets/images/products/dji-drone.jpg', 759.00, 849.00, 45, 4.6, 1234, 1, 11),
(1, 'Samsung Galaxy S24 Ultra', 'AI-powered smartphone with 200MP camera, titanium frame, built-in S Pen.', '/assets/images/products/galaxy-s24.jpg', 1299.99, 1419.99, 90, 4.7, 4521, 1, 8),
(1, 'Apple Watch Ultra 2', 'Most rugged and capable Apple Watch with precision dual-frequency GPS.', '/assets/images/products/apple-watch.jpg', 799.00, 849.00, 60, 4.8, 2345, 0, 6),
(1, 'Bose QuietComfort Ultra', 'Breakthrough spatial audio and world-class noise cancellation headphones.', '/assets/images/products/bose-qc.jpg', 379.00, 429.00, 55, 4.6, 1876, 0, 12),
(1, 'LG 34" UltraWide Monitor', 'Curved WQHD IPS display perfect for multitasking and creative work.', '/assets/images/products/lg-monitor.jpg', 449.99, 549.99, 35, 4.5, 1123, 0, 18),
(1, 'Sonos Era 300 Speaker', 'Spatial audio speaker with Dolby Atmos for immersive room-filling sound.', '/assets/images/products/sonos-speaker.jpg', 449.00, 499.00, 70, 4.4, 892, 0, 10),
(1, 'GoPro HERO12 Black', 'Waterproof action camera with 5.3K60 video, HyperSmooth 6.0 stabilization.', '/assets/images/products/gopro-hero.jpg', 349.99, 399.99, 80, 4.5, 2567, 0, 13),
(1, 'Kindle Scribe E-Reader', '10.2" display with premium pen for reading, writing, and journaling.', '/assets/images/products/kindle-scribe.jpg', 339.99, 389.99, 95, 4.3, 1432, 0, 13),
(1, 'Anker 737 Power Bank 24K', 'Massive 24,000mAh capacity with 140W output for laptops and phones.', '/assets/images/products/anker-powerbank.jpg', 109.99, 129.99, 150, 4.6, 3456, 0, 15),
(1, 'Logitech MX Master 3S Mouse', 'Advanced wireless mouse with MagSpeed scrolling and 8K DPI tracking.', '/assets/images/products/logitech-mouse.jpg', 89.99, 99.99, 200, 4.7, 5678, 0, 10),
(1, 'Razer BlackWidow V4 Pro', 'Mechanical gaming keyboard with customizable command dial and media keys.', '/assets/images/products/razer-keyboard.jpg', 229.99, 279.99, 45, 4.5, 1234, 0, 18),
(1, 'Dyson V15 Detect Vacuum', 'Intelligent cordless vacuum with laser dust detection technology.', '/assets/images/products/dyson-vacuum.jpg', 749.99, 849.99, 30, 4.7, 2789, 1, 12),
(1, 'Meta Quest 3 VR Headset', 'Mixed reality headset with full-color passthrough and Snapdragon XR2 Gen 2.', '/assets/images/products/quest-3.jpg', 499.99, 549.99, 55, 4.4, 3456, 1, 9),
(1, 'Nvidia RTX 4090 GPU', 'Ultimate graphics card with Ada Lovelace architecture and 24GB GDDR6X.', '/assets/images/products/rtx-4090.jpg', 1599.00, 1799.00, 15, 4.9, 1987, 1, 11),
(1, 'Samsung Galaxy Tab S9 Ultra', '14.6" Dynamic AMOLED 2X display with S Pen and Snapdragon 8 Gen 2.', '/assets/images/products/galaxy-tab.jpg', 1099.99, 1199.99, 40, 4.6, 1543, 0, 8),
(1, 'JBL Charge 5 Speaker', 'Portable Bluetooth speaker with powerful JBL Original Pro Sound.', '/assets/images/products/jbl-charge.jpg', 149.95, 179.95, 120, 4.7, 7890, 0, 17),
(1, 'Fitbit Charge 6 Tracker', 'Advanced fitness tracker with Google apps, GPS, and health metrics.', '/assets/images/products/fitbit-charge.jpg', 139.95, 159.95, 90, 4.3, 4567, 0, 13),
(1, 'TP-Link Deco XE75 Mesh WiFi', 'Tri-band WiFi 6E mesh system covering up to 7,200 sq ft.', '/assets/images/products/tplink-mesh.jpg', 299.99, 349.99, 60, 4.5, 2345, 0, 14),
(1, 'WD Black SN850X 2TB SSD', 'PCIe Gen4 NVMe SSD with up to 7,300MB/s read speeds for gaming.', '/assets/images/products/wd-ssd.jpg', 149.99, 189.99, 100, 4.8, 3456, 0, 21),
(1, 'Corsair Vengeance DDR5 32GB', 'High-performance DDR5 memory kit optimized for Intel and AMD platforms.', '/assets/images/products/corsair-ram.jpg', 119.99, 149.99, 80, 4.7, 2345, 0, 20),
(1, 'Blue Yeti X USB Microphone', 'Professional USB mic with high-res LED metering and Blue VO!CE effects.', '/assets/images/products/blue-yeti.jpg', 139.99, 169.99, 70, 4.5, 5678, 0, 18),
(1, 'Elgato Stream Deck MK.2', '15 customizable LCD keys for live content creation and productivity.', '/assets/images/products/stream-deck.jpg', 129.99, 149.99, 55, 4.6, 3456, 0, 13),
(1, 'Ring Video Doorbell Pro 2', 'Smart doorbell with 3D Motion Detection and Bird\'s Eye View.', '/assets/images/products/ring-doorbell.jpg', 219.99, 249.99, 85, 4.4, 6789, 0, 12),
(1, 'Philips Hue Starter Kit', 'Smart LED lighting kit with bridge and 3 color-changing bulbs.', '/assets/images/products/philips-hue.jpg', 129.99, 159.99, 100, 4.5, 4567, 0, 19),
(1, 'Marshall Stanmore III Speaker', 'Iconic Bluetooth speaker with room-filling sound and Placement Compensation.', '/assets/images/products/marshall-speaker.jpg', 349.99, 399.99, 40, 4.6, 1876, 0, 13),
(1, 'Roku Ultra 4K Streaming', 'Premium streaming device with Dolby Vision, Dolby Atmos, and ethernet.', '/assets/images/products/roku-ultra.jpg', 79.99, 99.99, 150, 4.4, 5432, 0, 20),
(1, 'Lenovo ThinkPad X1 Carbon', 'Ultra-lightweight business laptop with Intel Core i7 and 2.8K OLED display.', '/assets/images/products/thinkpad-x1.jpg', 1649.00, 1899.00, 25, 4.6, 1234, 0, 13),
(1, 'Google Pixel 8 Pro', 'AI-powered smartphone with Tensor G3 chip and best-in-class camera system.', '/assets/images/products/pixel-8.jpg', 899.00, 999.00, 75, 4.5, 3456, 0, 10),
(1, 'Amazfit T-Rex Ultra Watch', 'Military-grade outdoor smartwatch with dual-band GPS and 20-day battery.', '/assets/images/products/amazfit-trex.jpg', 299.99, 399.99, 45, 4.4, 1567, 0, 25),
(1, 'Sennheiser Momentum 4', 'Audiophile wireless headphones with 60-hour battery and adaptive ANC.', '/assets/images/products/sennheiser-m4.jpg', 299.95, 349.95, 50, 4.6, 2345, 0, 14),
(1, 'Seagate Expansion 4TB HDD', 'Portable external hard drive for reliable backup and storage on the go.', '/assets/images/products/seagate-hdd.jpg', 89.99, 109.99, 200, 4.3, 8901, 0, 18),
(1, 'Belkin MagSafe 3-in-1 Charger', 'Wireless charging stand for iPhone, Apple Watch, and AirPods simultaneously.', '/assets/images/products/belkin-charger.jpg', 129.95, 149.95, 90, 4.5, 3456, 0, 13);

-- FASHION (40 products)
INSERT INTO products (category_id, title, description, image_url, price, original_price, stock, rating, review_count, is_hot, discount_percent) VALUES
(2, 'Premium Italian Leather Jacket', 'Handcrafted genuine Italian leather jacket with satin lining and brass hardware.', '/assets/images/products/leather-jacket.jpg', 899.00, 1099.00, 20, 4.8, 456, 1, 18),
(2, 'Designer Silk Evening Dress', 'Elegant floor-length silk gown with delicate beading and sweetheart neckline.', '/assets/images/products/silk-dress.jpg', 1299.00, 1599.00, 10, 4.9, 234, 1, 19),
(2, 'Classic Cashmere Overcoat', 'Luxurious pure cashmere overcoat in charcoal, tailored silhouette.', '/assets/images/products/cashmere-coat.jpg', 1450.00, 1800.00, 15, 4.7, 567, 1, 19),
(2, 'Swiss Automatic Watch', 'Swiss-made automatic movement watch with sapphire crystal and leather strap.', '/assets/images/products/swiss-watch.jpg', 2999.00, 3499.00, 8, 4.9, 189, 1, 14),
(2, 'Premium Wool Suit', 'Italian merino wool two-piece suit with half-canvas construction.', '/assets/images/products/wool-suit.jpg', 899.00, 1199.00, 25, 4.6, 345, 0, 25),
(2, 'Designer Sunglasses Aviator', 'Polarized titanium aviator sunglasses with UV400 protection.', '/assets/images/products/aviator-sunglass.jpg', 289.00, 349.00, 60, 4.5, 1234, 0, 17),
(2, 'Handmade Oxford Shoes', 'Goodyear-welted Oxford shoes in burnished cognac leather.', '/assets/images/products/oxford-shoes.jpg', 449.00, 549.00, 30, 4.7, 678, 0, 18),
(2, 'Luxury Silk Tie Collection', 'Set of 5 hand-woven silk ties in classic patterns and colors.', '/assets/images/products/silk-ties.jpg', 199.00, 249.00, 50, 4.4, 890, 0, 20),
(2, 'Premium Denim Jeans', 'Japanese selvedge denim jeans with raw indigo wash and chain-stitch hem.', '/assets/images/products/selvedge-jeans.jpg', 259.00, 299.00, 80, 4.5, 2345, 0, 13),
(2, 'Merino Wool Sweater', 'Extra-fine merino wool crew neck sweater in heather gray.', '/assets/images/products/merino-sweater.jpg', 149.00, 189.00, 100, 4.6, 1567, 0, 21),
(2, 'Italian Leather Belt', 'Full-grain Italian leather belt with brushed nickel buckle.', '/assets/images/products/leather-belt.jpg', 89.00, 119.00, 120, 4.5, 2345, 0, 25),
(2, 'Luxury Scarf Collection', 'Pure cashmere scarf in herringbone pattern, oversized wrap style.', '/assets/images/products/cashmere-scarf.jpg', 199.00, 259.00, 40, 4.7, 567, 0, 23),
(2, 'Premium Sneakers White', 'Minimalist white leather sneakers with cushioned insole and rubber outsole.', '/assets/images/products/white-sneakers.jpg', 189.00, 229.00, 90, 4.6, 3456, 1, 17),
(2, 'Designer Handbag Tote', 'Structured leather tote bag with signature hardware and suede lining.', '/assets/images/products/designer-tote.jpg', 750.00, 899.00, 15, 4.8, 789, 1, 17),
(2, 'Linen Summer Blazer', 'Unstructured linen blazer in navy, perfect for warm weather occasions.', '/assets/images/products/linen-blazer.jpg', 349.00, 429.00, 35, 4.4, 456, 0, 19),
(2, 'Vintage Leather Messenger Bag', 'Distressed leather messenger bag with antique brass fittings.', '/assets/images/products/messenger-bag.jpg', 279.00, 349.00, 25, 4.6, 1234, 0, 20),
(2, 'Premium Cotton Dress Shirt', 'Egyptian cotton dress shirt with French cuffs and mother-of-pearl buttons.', '/assets/images/products/dress-shirt.jpg', 129.00, 159.00, 100, 4.5, 2345, 0, 19),
(2, 'Luxury Perfume Collection', 'Artisanal eau de parfum trio set with premium glass atomizers.', '/assets/images/products/perfume-set.jpg', 349.00, 449.00, 30, 4.7, 890, 0, 22),
(2, 'Suede Chelsea Boots', 'Premium suede Chelsea boots with crepe sole and elastic side panels.', '/assets/images/products/chelsea-boots.jpg', 329.00, 399.00, 40, 4.5, 1567, 0, 18),
(2, 'Diamond Stud Earrings', '0.5 carat total weight diamond studs in 14K white gold setting.', '/assets/images/products/diamond-studs.jpg', 899.00, 1199.00, 12, 4.9, 345, 1, 25),
(2, 'Premium Yoga Pants', 'High-waist yoga pants with 4-way stretch and moisture-wicking fabric.', '/assets/images/products/yoga-pants.jpg', 89.00, 119.00, 150, 4.6, 5678, 0, 25),
(2, 'Leather Wallet Bifold', 'RFID-blocking bifold wallet in full-grain leather with 8 card slots.', '/assets/images/products/leather-wallet.jpg', 69.00, 89.00, 200, 4.5, 4567, 0, 22),
(2, 'Silk Pajama Set', 'Luxurious mulberry silk pajama set with piping detail.', '/assets/images/products/silk-pajamas.jpg', 199.00, 259.00, 45, 4.7, 890, 0, 23),
(2, 'Running Shoes Pro Max', 'Carbon-plated running shoes with energy-return foam and breathable mesh.', '/assets/images/products/running-shoes.jpg', 249.00, 299.00, 70, 4.7, 3456, 1, 17),
(2, 'Pearl Necklace Classic', 'Freshwater pearl strand necklace with sterling silver clasp, 18 inches.', '/assets/images/products/pearl-necklace.jpg', 449.00, 599.00, 18, 4.8, 234, 0, 25),
(2, 'Trench Coat Classic Beige', 'Double-breasted cotton gabardine trench coat with storm shield.', '/assets/images/products/trench-coat.jpg', 599.00, 749.00, 20, 4.6, 567, 0, 20),
(2, 'Sports Bra High Impact', 'Encapsulation sports bra with adjustable straps and moisture management.', '/assets/images/products/sports-bra.jpg', 59.00, 79.00, 200, 4.4, 3456, 0, 25),
(2, 'Fedora Hat Premium', 'Wide-brim fedora in premium wool felt with grosgrain ribbon band.', '/assets/images/products/fedora-hat.jpg', 89.00, 119.00, 60, 4.3, 890, 0, 25),
(2, 'Titanium Cufflinks Set', 'Brushed titanium cufflinks with carbon fiber inlay in gift box.', '/assets/images/products/cufflinks.jpg', 149.00, 199.00, 35, 4.5, 456, 0, 25),
(2, 'Embroidered Polo Shirt', 'Premium pique cotton polo with embroidered logo and ribbed collar.', '/assets/images/products/polo-shirt.jpg', 79.00, 99.00, 150, 4.4, 2345, 0, 20),
(2, 'Cocktail Dress Sequin', 'Sequin-embellished cocktail dress with sweetheart neckline and tulle overlay.', '/assets/images/products/cocktail-dress.jpg', 459.00, 599.00, 15, 4.8, 345, 1, 23),
(2, 'Leather Loafers Italian', 'Penny loafers in butter-soft Italian calfskin with leather sole.', '/assets/images/products/leather-loafers.jpg', 389.00, 479.00, 25, 4.6, 678, 0, 19),
(2, 'Bamboo Fiber T-Shirt Pack', 'Pack of 3 ultra-soft bamboo fiber crew neck tees in neutral colors.', '/assets/images/products/bamboo-tshirts.jpg', 69.00, 89.00, 300, 4.5, 6789, 0, 22),
(2, 'Gold Chain Bracelet', '18K gold-plated curb chain bracelet with lobster clasp, 7.5 inches.', '/assets/images/products/gold-bracelet.jpg', 179.00, 229.00, 40, 4.6, 1234, 0, 22),
(2, 'Hiking Boots Waterproof', 'GORE-TEX waterproof hiking boots with Vibram outsole and ankle support.', '/assets/images/products/hiking-boots.jpg', 229.00, 289.00, 50, 4.7, 2345, 0, 21),
(2, 'Wrap Dress Floral', 'Flowing wrap dress in botanical print with flutter sleeves and self-tie belt.', '/assets/images/products/wrap-dress.jpg', 129.00, 169.00, 65, 4.5, 1567, 0, 24),
(2, 'Quilted Vest Down', 'Lightweight quilted down vest with stand collar and zip pockets.', '/assets/images/products/quilted-vest.jpg', 159.00, 199.00, 55, 4.4, 890, 0, 20),
(2, 'Crossbody Bag Leather', 'Compact crossbody bag in pebbled leather with adjustable chain strap.', '/assets/images/products/crossbody-bag.jpg', 219.00, 279.00, 40, 4.6, 1234, 0, 22),
(2, 'Chino Pants Slim Fit', 'Stretch cotton chinos in slim fit with flat front and tapered leg.', '/assets/images/products/chino-pants.jpg', 89.00, 109.00, 120, 4.4, 3456, 0, 18),
(2, 'Silk Pocket Square Set', 'Set of 4 hand-rolled silk pocket squares in complementary patterns.', '/assets/images/products/pocket-squares.jpg', 79.00, 99.00, 80, 4.3, 567, 0, 20);

-- HOME & LIVING (40 products)
INSERT INTO products (category_id, title, description, image_url, price, original_price, stock, rating, review_count, is_hot, discount_percent) VALUES
(3, 'Smart Robot Vacuum Pro', 'LiDAR navigation robot vacuum with auto-empty station and mopping.', '/assets/images/products/robot-vacuum.jpg', 699.99, 849.99, 35, 4.7, 3456, 1, 18),
(3, 'Espresso Machine Barista', 'Semi-automatic espresso machine with PID temperature control and 58mm portafilter.', '/assets/images/products/espresso-machine.jpg', 899.00, 1099.00, 20, 4.8, 1234, 1, 18),
(3, 'Air Purifier HEPA Pro', 'Medical-grade HEPA filtration covering 1,500 sq ft with real-time air quality monitor.', '/assets/images/products/air-purifier.jpg', 449.99, 549.99, 40, 4.6, 2345, 0, 18),
(3, 'Smart Thermostat Learning', 'AI-powered thermostat that learns your schedule and saves energy automatically.', '/assets/images/products/smart-thermostat.jpg', 249.99, 299.99, 60, 4.5, 4567, 0, 17),
(3, 'Egyptian Cotton Sheet Set', '1000 thread count Egyptian cotton sheet set, king size, in pearl white.', '/assets/images/products/cotton-sheets.jpg', 299.00, 399.00, 50, 4.7, 1890, 0, 25),
(3, 'Stand Mixer Professional', '7-quart professional stand mixer with 10 speeds and dough hook attachment.', '/assets/images/products/stand-mixer.jpg', 449.99, 549.99, 30, 4.8, 2345, 1, 18),
(3, 'Memory Foam Mattress King', '12-inch hybrid memory foam mattress with cooling gel and pocketed coils.', '/assets/images/products/memory-mattress.jpg', 1299.00, 1699.00, 15, 4.6, 3456, 1, 24),
(3, 'Smart Lock Deadbolt', 'Keyless smart deadbolt with fingerprint, PIN, and app access. Auto-lock feature.', '/assets/images/products/smart-lock.jpg', 199.99, 249.99, 70, 4.4, 1567, 0, 20),
(3, 'Sous Vide Precision Cooker', 'WiFi-enabled precision cooker with 1100W heating element and app control.', '/assets/images/products/sous-vide.jpg', 199.00, 249.00, 55, 4.5, 2345, 0, 20),
(3, 'Indoor Herb Garden Smart', 'Hydroponic indoor garden system with LED grow lights for 12 pods.', '/assets/images/products/herb-garden.jpg', 179.99, 229.99, 45, 4.3, 890, 0, 22),
(3, 'Weighted Blanket Premium', '20 lb weighted blanket with cooling bamboo cover and glass bead fill.', '/assets/images/products/weighted-blanket.jpg', 89.99, 119.99, 100, 4.6, 5678, 0, 25),
(3, 'Cast Iron Cookware Set', '7-piece enameled cast iron cookware set in French blue with lifetime warranty.', '/assets/images/products/cast-iron-set.jpg', 549.00, 699.00, 25, 4.8, 1234, 0, 21),
(3, 'Smart Security Camera', '2K indoor/outdoor security camera with color night vision and two-way audio.', '/assets/images/products/security-cam.jpg', 129.99, 169.99, 80, 4.4, 3456, 0, 24),
(3, 'Ergonomic Office Chair', 'Full mesh ergonomic chair with adjustable lumbar, armrests, and headrest.', '/assets/images/products/ergo-chair.jpg', 599.00, 749.00, 20, 4.7, 2345, 1, 20),
(3, 'Wine Cooler 24-Bottle', 'Dual-zone thermoelectric wine cooler with UV-protected glass door.', '/assets/images/products/wine-cooler.jpg', 299.99, 399.99, 30, 4.5, 890, 0, 25),
(3, 'Smart Light Bulb 4-Pack', 'Color-changing WiFi smart bulbs with 16 million colors and voice control.', '/assets/images/products/smart-bulbs.jpg', 49.99, 69.99, 200, 4.4, 6789, 0, 29),
(3, 'Ceramic Knife Set', '5-piece ceramic knife set with bamboo block, ultra-sharp and stain-resistant.', '/assets/images/products/ceramic-knives.jpg', 89.99, 129.99, 65, 4.5, 1567, 0, 31),
(3, 'Electric Fireplace Insert', '36-inch recessed electric fireplace with realistic flame effects and heat control.', '/assets/images/products/electric-fireplace.jpg', 449.00, 599.00, 15, 4.6, 678, 0, 25),
(3, 'Bamboo Bathroom Set', '6-piece bamboo bathroom accessory set including soap dispenser and towel rack.', '/assets/images/products/bamboo-bath.jpg', 59.99, 79.99, 120, 4.3, 2345, 0, 25),
(3, 'Instant Pot Duo Plus', '8-quart 9-in-1 pressure cooker with app connectivity and 13 smart programs.', '/assets/images/products/instant-pot.jpg', 119.99, 149.99, 90, 4.7, 8901, 0, 20),
(3, 'Turkish Cotton Towel Set', '8-piece Turkish cotton towel set in spa white, 700 GSM luxury weight.', '/assets/images/products/turkish-towels.jpg', 129.00, 169.00, 70, 4.6, 1234, 0, 24),
(3, 'Standing Desk Electric', '60-inch dual motor electric standing desk with memory presets and cable tray.', '/assets/images/products/standing-desk.jpg', 549.00, 699.00, 20, 4.5, 1567, 0, 21),
(3, 'Blender Pro High-Speed', '1450W professional blender with 64oz container, 10 speeds, and pulse.', '/assets/images/products/pro-blender.jpg', 179.99, 229.99, 50, 4.6, 3456, 0, 22),
(3, 'Scented Candle Luxury Set', 'Set of 6 hand-poured soy wax candles in artisan glass jars, 50-hour burn.', '/assets/images/products/luxury-candles.jpg', 79.99, 99.99, 150, 4.4, 2345, 0, 20),
(3, 'Velvet Throw Pillows 4-Pack', 'Set of 4 velvet decorative throw pillows with hidden zippers, 18x18 inches.', '/assets/images/products/throw-pillows.jpg', 49.99, 69.99, 200, 4.3, 4567, 0, 29),
(3, 'Cordless Stick Vacuum', 'Lightweight cordless vacuum with 60-minute runtime and HEPA filtration.', '/assets/images/products/stick-vacuum.jpg', 299.99, 399.99, 35, 4.5, 2345, 0, 25),
(3, 'Coffee Grinder Burr', 'Conical burr coffee grinder with 40 grind settings and anti-static technology.', '/assets/images/products/burr-grinder.jpg', 149.99, 199.99, 45, 4.6, 1890, 0, 25),
(3, 'Smart Smoke Detector', 'Photoelectric smoke and CO detector with voice alerts and phone notifications.', '/assets/images/products/smoke-detector.jpg', 99.99, 129.99, 100, 4.5, 3456, 0, 23),
(3, 'Outdoor Furniture Set', '4-piece rattan patio furniture set with cushions and tempered glass table.', '/assets/images/products/patio-set.jpg', 899.00, 1199.00, 10, 4.4, 567, 0, 25),
(3, 'Dehumidifier 50-Pint', 'Energy Star certified dehumidifier covering 4,500 sq ft with continuous drain.', '/assets/images/products/dehumidifier.jpg', 249.99, 299.99, 40, 4.3, 1234, 0, 17),
(3, 'Silk Area Rug 8x10', 'Hand-knotted silk and wool blend area rug in contemporary geometric pattern.', '/assets/images/products/silk-rug.jpg', 1299.00, 1799.00, 8, 4.8, 234, 0, 28),
(3, 'Electric Kettle Gooseneck', 'Temperature-controlled gooseneck electric kettle with hold function, 1200W.', '/assets/images/products/gooseneck-kettle.jpg', 79.99, 99.99, 80, 4.5, 2345, 0, 20),
(3, 'Wall Art Canvas Set', '3-piece abstract canvas wall art set in neutral tones, gallery-wrapped.', '/assets/images/products/canvas-art.jpg', 129.00, 179.00, 30, 4.4, 890, 0, 28),
(3, 'Bedside Lamp Touch', 'Dimmable touch-control bedside lamp with 3 color temperatures and USB port.', '/assets/images/products/bedside-lamp.jpg', 44.99, 59.99, 150, 4.3, 3456, 0, 25),
(3, 'Food Processor 14-Cup', '14-cup food processor with dicing kit, adjustable slicing, and dough blade.', '/assets/images/products/food-processor.jpg', 249.99, 299.99, 35, 4.6, 1567, 0, 17),
(3, 'Blackout Curtains Premium', 'Thermal insulated blackout curtains, 2 panels, 52x84 inches in charcoal.', '/assets/images/products/blackout-curtains.jpg', 39.99, 59.99, 200, 4.4, 5678, 0, 33),
(3, 'Cast Iron Skillet 12"', 'Pre-seasoned 12-inch cast iron skillet with helper handle, oven-safe to 500°F.', '/assets/images/products/cast-iron-skillet.jpg', 44.99, 59.99, 100, 4.8, 7890, 0, 25),
(3, 'Bathroom Scale Smart', 'WiFi body composition scale measuring 13 metrics with companion app.', '/assets/images/products/smart-scale.jpg', 49.99, 69.99, 120, 4.3, 2345, 0, 29),
(3, 'Closet Organizer System', 'Modular walk-in closet organization system with adjustable shelving and rods.', '/assets/images/products/closet-organizer.jpg', 349.00, 449.00, 15, 4.5, 890, 0, 22),
(3, 'Dutch Oven Enameled 7Qt', '7-quart enameled cast iron Dutch oven in sunset orange with self-basting lid.', '/assets/images/products/dutch-oven.jpg', 89.99, 119.99, 50, 4.7, 4567, 0, 25);

-- GAMING (40 products)
INSERT INTO products (category_id, title, description, image_url, price, original_price, stock, rating, review_count, is_hot, discount_percent) VALUES
(4, 'PlayStation 5 Pro Console', 'Next-gen gaming console with enhanced GPU, 2TB SSD, and ray tracing.', '/assets/images/products/ps5-pro.jpg', 699.99, 749.99, 20, 4.9, 5678, 1, 7),
(4, 'Xbox Series X Console', 'Most powerful Xbox ever with 12 teraflops of processing power and 1TB SSD.', '/assets/images/products/xbox-series-x.jpg', 499.99, 549.99, 30, 4.8, 4567, 1, 9),
(4, 'Nintendo Switch OLED', 'Vivid 7-inch OLED screen with enhanced audio and 64GB internal storage.', '/assets/images/products/switch-oled.jpg', 349.99, 379.99, 50, 4.7, 8901, 1, 8),
(4, 'Gaming Monitor 27" 4K 144Hz', '27-inch 4K IPS gaming monitor with 144Hz refresh and 1ms response time.', '/assets/images/products/gaming-monitor.jpg', 599.99, 749.99, 25, 4.6, 2345, 1, 20),
(4, 'Gaming PC RTX 4080', 'Pre-built gaming PC: RTX 4080, i7-14700K, 32GB DDR5, 2TB NVMe SSD.', '/assets/images/products/gaming-pc.jpg', 2499.99, 2899.99, 10, 4.8, 1234, 1, 14),
(4, 'DualSense Edge Controller', 'Pro-level PS5 controller with customizable buttons, triggers, and stick modules.', '/assets/images/products/dualsense-edge.jpg', 189.99, 199.99, 40, 4.5, 1567, 0, 5),
(4, 'Razer Viper V3 Pro Mouse', 'Ultra-lightweight 54g wireless gaming mouse with 35K DPI optical sensor.', '/assets/images/products/razer-viper.jpg', 149.99, 159.99, 60, 4.7, 2345, 0, 6),
(4, 'SteelSeries Arctis Nova Pro', 'Premium wireless gaming headset with Active Noise Cancellation and Hi-Res audio.', '/assets/images/products/arctis-nova.jpg', 349.99, 379.99, 35, 4.6, 1890, 0, 8),
(4, 'Gaming Chair Ergonomic Pro', '4D armrest gaming chair with lumbar support, memory foam, and reclining.', '/assets/images/products/gaming-chair.jpg', 449.99, 549.99, 20, 4.4, 3456, 0, 18),
(4, 'Corsair K100 RGB Keyboard', 'Premium mechanical keyboard with OPX optical switches and iCUE control.', '/assets/images/products/corsair-k100.jpg', 199.99, 229.99, 45, 4.6, 1567, 0, 13),
(4, 'Steam Deck OLED 1TB', 'Portable gaming PC with 7.4" HDR OLED display and 1TB NVMe SSD.', '/assets/images/products/steam-deck.jpg', 649.00, 699.00, 25, 4.7, 3456, 1, 7),
(4, 'VR Racing Wheel Set', 'Direct drive racing wheel with 10Nm force feedback, pedals, and shifter.', '/assets/images/products/racing-wheel.jpg', 899.99, 1099.99, 10, 4.5, 567, 0, 18),
(4, 'Capture Card 4K60 Pro', '4K60 HDR10 internal capture card for streaming and recording gameplay.', '/assets/images/products/capture-card.jpg', 199.99, 249.99, 50, 4.5, 1234, 0, 20),
(4, 'Gaming Desk 60" RGB', '60-inch carbon fiber surface gaming desk with RGB lighting and cable management.', '/assets/images/products/gaming-desk.jpg', 299.99, 399.99, 15, 4.3, 890, 0, 25),
(4, 'Flight Stick HOTAS Pro', 'Precision HOTAS flight stick with dual throttle and 120+ programmable buttons.', '/assets/images/products/flight-stick.jpg', 349.99, 449.99, 20, 4.4, 456, 0, 22),
(4, 'Gaming Mousepad XXL RGB', 'Extended RGB gaming mousepad, 36x12 inches, with micro-textured surface.', '/assets/images/products/rgb-mousepad.jpg', 39.99, 49.99, 200, 4.3, 4567, 0, 20),
(4, 'Retro Gaming Console 10,000+', 'Retro gaming console with 10,000+ built-in classic games and HDMI output.', '/assets/images/products/retro-console.jpg', 89.99, 119.99, 80, 4.2, 2345, 0, 25),
(4, 'PS5 Game: Spider-Man 2', 'Marvel\'s Spider-Man 2 for PS5 with enhanced ray tracing and dual protagonists.', '/assets/images/products/spiderman-2.jpg', 59.99, 69.99, 150, 4.8, 6789, 0, 14),
(4, 'Nintendo Pro Controller', 'Official Nintendo wireless controller with HD Rumble and amiibo support.', '/assets/images/products/nintendo-pro.jpg', 64.99, 69.99, 80, 4.6, 3456, 0, 7),
(4, 'Gaming Glasses Blue Light', 'Premium gaming glasses with blue light filter and anti-glare coating.', '/assets/images/products/gaming-glasses.jpg', 49.99, 69.99, 100, 4.2, 1567, 0, 29),
(4, 'Xbox Game Pass Ultimate 12M', 'Xbox Game Pass Ultimate 12-month digital subscription code.', '/assets/images/products/gamepass-12m.jpg', 179.99, 203.88, 500, 4.9, 8901, 1, 12),
(4, 'Elgato Key Light Air', 'Professional LED panel for streaming with 1400 lumens and app control.', '/assets/images/products/key-light.jpg', 119.99, 149.99, 60, 4.5, 2345, 0, 20),
(4, 'Gaming Router WiFi 6E', 'Tri-band WiFi 6E gaming router with DFS and game-optimized QoS.', '/assets/images/products/gaming-router.jpg', 299.99, 399.99, 25, 4.4, 890, 0, 25),
(4, 'Controller Charging Dock', 'Dual controller charging dock for PS5 with LED indicators and fast charge.', '/assets/images/products/charging-dock.jpg', 29.99, 39.99, 200, 4.3, 5678, 0, 25),
(4, 'Gaming Headset Stand RGB', 'Aluminum headset stand with USB hub, RGB lighting, and cable management.', '/assets/images/products/headset-stand.jpg', 34.99, 44.99, 150, 4.2, 1234, 0, 22),
(4, 'Portable Gaming Monitor 15.6"', '15.6" portable 1080p 144Hz gaming monitor with USB-C and mini-HDMI.', '/assets/images/products/portable-monitor.jpg', 199.99, 249.99, 35, 4.4, 1567, 0, 20),
(4, 'Sim Racing Cockpit', 'Full sim racing cockpit frame with seat, adjustable for wheel and pedals.', '/assets/images/products/racing-cockpit.jpg', 599.99, 799.99, 8, 4.5, 345, 0, 25),
(4, 'Zelda: Tears of Kingdom', 'The Legend of Zelda: Tears of the Kingdom for Nintendo Switch.', '/assets/images/products/zelda-totk.jpg', 54.99, 69.99, 100, 4.9, 9012, 0, 21),
(4, 'Gaming Webcam 4K', '4K webcam with autofocus, noise-canceling mic, and adjustable FOV for streaming.', '/assets/images/products/gaming-webcam.jpg', 129.99, 179.99, 45, 4.4, 2345, 0, 28),
(4, 'VR Fitness Game Bundle', 'Bundle of 5 VR fitness games for Meta Quest with workout tracking.', '/assets/images/products/vr-fitness.jpg', 89.99, 124.95, 200, 4.3, 1234, 0, 28),
(4, 'Custom Controller Skin Set', 'Set of 5 premium vinyl controller skins with anti-slip texture.', '/assets/images/products/controller-skins.jpg', 24.99, 34.99, 300, 4.1, 3456, 0, 29),
(4, 'Gaming Earbuds Low Latency', 'True wireless gaming earbuds with 45ms latency and ANC.', '/assets/images/products/gaming-earbuds.jpg', 79.99, 99.99, 80, 4.3, 2345, 0, 20),
(4, 'PS5 Media Remote', 'Official PlayStation 5 media remote for streaming and Blu-ray control.', '/assets/images/products/ps5-remote.jpg', 24.99, 29.99, 150, 4.2, 1567, 0, 17),
(4, 'Gaming Storage Tower', 'Video game storage tower holding 36 games with controller hooks.', '/assets/images/products/game-tower.jpg', 39.99, 54.99, 100, 4.1, 890, 0, 27),
(4, 'RGB LED Strip for Setup', '2x 3ft RGB LED light strips with remote and music sync for gaming setup.', '/assets/images/products/led-strips.jpg', 19.99, 29.99, 300, 4.3, 6789, 0, 33),
(4, 'Switch Carry Case Pro', 'Hard shell carry case for Nintendo Switch with 20 game card slots.', '/assets/images/products/switch-case.jpg', 19.99, 24.99, 200, 4.4, 4567, 0, 20),
(4, 'Arcade Fight Stick', 'Sanwa components arcade fight stick compatible with PS5, Xbox, and PC.', '/assets/images/products/fight-stick.jpg', 199.99, 249.99, 20, 4.5, 678, 0, 20),
(4, 'Game Capture Software Pro', 'Professional game recording and streaming software, 1-year license key.', '/assets/images/products/capture-sw.jpg', 39.99, 59.99, 999, 4.4, 2345, 0, 33),
(4, 'Thumb Grip Caps 8-Pack', 'Premium silicone thumb grip caps for PS5/Xbox controllers, 4 styles.', '/assets/images/products/thumb-grips.jpg', 9.99, 14.99, 500, 4.1, 3456, 0, 33),
(4, 'Nintendo eShop Card $50', 'Nintendo eShop digital gift card worth $50, instant delivery.', '/assets/images/products/eshop-card.jpg', 47.99, 50.00, 999, 4.7, 5678, 0, 4);

-- AUTOMOTIVE (40 products)
INSERT INTO products (category_id, title, description, image_url, price, original_price, stock, rating, review_count, is_hot, discount_percent) VALUES
(5, 'Dash Cam 4K Front & Rear', 'Dual 4K dash camera with GPS, night vision, parking mode, and 128GB storage.', '/assets/images/products/dash-cam-4k.jpg', 249.99, 329.99, 50, 4.7, 3456, 1, 24),
(5, 'Portable Jump Starter 3000A', '3000A peak portable jump starter with power bank and USB-C charging.', '/assets/images/products/jump-starter.jpg', 119.99, 159.99, 60, 4.6, 5678, 1, 25),
(5, 'Car Vacuum Cordless Pro', '12000PA cordless car vacuum with HEPA filter and LED light.', '/assets/images/products/car-vacuum.jpg', 59.99, 79.99, 100, 4.4, 2345, 0, 25),
(5, 'LED Headlight Bulbs H11', 'Ultra-bright LED headlight kit, 20000LM per pair, plug-and-play.', '/assets/images/products/led-headlights.jpg', 49.99, 69.99, 200, 4.5, 6789, 0, 29),
(5, 'Ceramic Coating Spray', 'SiO2 ceramic spray coating with 12-month hydrophobic protection.', '/assets/images/products/ceramic-coating.jpg', 34.99, 49.99, 150, 4.3, 3456, 0, 30),
(5, 'Tire Pressure Monitor System', 'Solar-powered TPMS with 4 external sensors and real-time color display.', '/assets/images/products/tpms.jpg', 39.99, 59.99, 80, 4.4, 1234, 0, 33),
(5, 'Car Phone Mount Magnetic', 'MagSafe-compatible magnetic car mount with 15W wireless charging.', '/assets/images/products/car-mount.jpg', 39.99, 49.99, 200, 4.5, 4567, 0, 20),
(5, 'OBD2 Scanner Bluetooth', 'Professional OBD2 diagnostic scanner with app connectivity and live data.', '/assets/images/products/obd2-scanner.jpg', 29.99, 44.99, 100, 4.3, 2345, 0, 33),
(5, 'All-Weather Floor Mats', 'Custom-fit all-weather floor mats, heavy-duty TPE, 3-piece front and rear.', '/assets/images/products/floor-mats.jpg', 79.99, 109.99, 80, 4.6, 3456, 0, 27),
(5, 'Radar Detector Max Range', 'Long-range radar detector with GPS lockout and false alert filtering.', '/assets/images/products/radar-detector.jpg', 299.99, 399.99, 30, 4.4, 1567, 0, 25),
(5, 'Car Seat Covers Premium', 'Premium leather car seat covers, universal fit, with airbag compatibility.', '/assets/images/products/seat-covers.jpg', 129.99, 179.99, 50, 4.3, 2345, 0, 28),
(5, 'Portable Air Compressor', 'Digital portable tire inflator with auto-shutoff and LED flashlight.', '/assets/images/products/air-compressor.jpg', 44.99, 59.99, 120, 4.5, 4567, 0, 25),
(5, 'Car Stereo Apple CarPlay', 'Double DIN car stereo with wireless Apple CarPlay, Android Auto, and backup cam.', '/assets/images/products/car-stereo.jpg', 249.99, 329.99, 25, 4.6, 1890, 1, 24),
(5, 'Steering Wheel Cover Leather', 'Genuine leather steering wheel cover with anti-slip grip, 14.5-15 inches.', '/assets/images/products/steering-cover.jpg', 24.99, 34.99, 200, 4.2, 3456, 0, 29),
(5, 'Car Trunk Organizer', 'Collapsible trunk organizer with multiple compartments and cooler section.', '/assets/images/products/trunk-organizer.jpg', 34.99, 44.99, 150, 4.3, 2345, 0, 22),
(5, 'Car Air Freshener Premium', 'Luxury car air freshener diffuser with 6 interchangeable scent pads.', '/assets/images/products/car-freshener.jpg', 19.99, 29.99, 300, 4.1, 5678, 0, 33),
(5, 'Emergency Roadside Kit', 'Complete 124-piece roadside emergency kit with first aid and safety gear.', '/assets/images/products/roadside-kit.jpg', 69.99, 89.99, 60, 4.5, 1234, 0, 22),
(5, 'Car Battery Charger Smart', 'Smart 12V/24V battery charger with desulfation mode and LCD display.', '/assets/images/products/battery-charger.jpg', 79.99, 99.99, 40, 4.4, 1567, 0, 20),
(5, 'Windshield Repair Kit', 'Professional windshield crack repair kit with UV-cure resin, fixes chips.', '/assets/images/products/windshield-kit.jpg', 14.99, 24.99, 200, 4.1, 3456, 0, 40),
(5, 'Car Cover All-Weather', 'Multi-layer all-weather car cover with UV protection and mirror pockets.', '/assets/images/products/car-cover.jpg', 59.99, 89.99, 70, 4.4, 2345, 0, 33),
(5, 'Backup Camera Wireless', 'HD wireless backup camera with 5" LCD monitor and night vision.', '/assets/images/products/backup-cam.jpg', 79.99, 109.99, 50, 4.3, 1890, 0, 27),
(5, 'Car Polisher Buffer Kit', 'Dual-action polisher with 6 buffing pads and carrying bag, 6" orbit.', '/assets/images/products/car-polisher.jpg', 89.99, 129.99, 35, 4.5, 1234, 0, 31),
(5, 'Snow Chains Universal', 'Premium universal snow tire chains, easy install, fits 195-235mm tires.', '/assets/images/products/snow-chains.jpg', 49.99, 69.99, 60, 4.3, 890, 0, 29),
(5, 'Car Bluetooth Adapter', 'Bluetooth 5.3 car adapter with bass boost, dual USB ports, and hands-free.', '/assets/images/products/bt-adapter.jpg', 19.99, 29.99, 300, 4.4, 6789, 0, 33),
(5, 'Headlight Restoration Kit', 'Complete headlight restoration kit with UV sealant for long-lasting clarity.', '/assets/images/products/headlight-restore.jpg', 24.99, 34.99, 150, 4.2, 3456, 0, 29),
(5, 'Car Roof Rack Cross Bars', 'Universal aluminum roof rack cross bars with anti-theft locks, 48 inches.', '/assets/images/products/roof-rack.jpg', 89.99, 129.99, 30, 4.4, 1567, 0, 31),
(5, 'Tonneau Cover Soft Roll-Up', 'Soft roll-up tonneau truck bed cover, weather-sealed, tool-free install.', '/assets/images/products/tonneau-cover.jpg', 199.99, 279.99, 15, 4.5, 678, 0, 29),
(5, 'LED Interior Light Kit', '16-piece LED interior light kit with Bluetooth app control and music sync.', '/assets/images/products/interior-led.jpg', 24.99, 39.99, 200, 4.2, 4567, 0, 38),
(5, 'Car Paint Touch-Up Kit', 'OEM color-matched paint touch-up kit with clear coat and primer pen.', '/assets/images/products/paint-touchup.jpg', 19.99, 29.99, 100, 4.0, 2345, 0, 33),
(5, 'Wheel Cleaning Brush Set', '5-piece wheel and tire cleaning brush set with long-reach barrel brushes.', '/assets/images/products/wheel-brushes.jpg', 14.99, 22.99, 200, 4.3, 3456, 0, 35),
(5, 'Auto Sun Shade Windshield', 'Retractable windshield sun shade with UV protection, universal fit.', '/assets/images/products/sun-shade.jpg', 29.99, 39.99, 150, 4.2, 5678, 0, 25),
(5, 'GPS Tracker for Cars', 'Real-time GPS tracker with geo-fencing, speed alerts, and 30-day battery.', '/assets/images/products/gps-tracker.jpg', 29.99, 49.99, 80, 4.3, 1234, 0, 40),
(5, 'Synthetic Motor Oil 5W-30', 'Full synthetic motor oil 5W-30, 5-quart jug, exceeds all industry standards.', '/assets/images/products/motor-oil.jpg', 29.99, 39.99, 200, 4.6, 8901, 0, 25),
(5, 'Car Tool Set 240-Piece', 'Professional 240-piece mechanic tool set in blow-molded case.', '/assets/images/products/tool-set.jpg', 149.99, 199.99, 30, 4.5, 1567, 0, 25),
(5, 'Blind Spot Mirror 2-Pack', 'HD convex blind spot mirrors with adjustable swivel, self-adhesive.', '/assets/images/products/blind-spot.jpg', 9.99, 14.99, 500, 4.1, 6789, 0, 33),
(5, 'Car Wash Kit Premium', '10-piece premium car wash kit with foam cannon, mitts, and microfiber towels.', '/assets/images/products/wash-kit.jpg', 49.99, 69.99, 80, 4.4, 2345, 0, 29),
(5, 'Electric Car Jack 5-Ton', '12V electric car jack with 5-ton capacity and tire change accessories.', '/assets/images/products/electric-jack.jpg', 89.99, 129.99, 40, 4.3, 890, 0, 31),
(5, 'Turbo Whistle Exhaust Tip', 'Stainless steel turbo whistle exhaust tip, universal fit, authentic sound.', '/assets/images/products/exhaust-tip.jpg', 14.99, 24.99, 200, 3.9, 1234, 0, 40),
(5, 'Car Trash Can Leakproof', 'Leakproof car trash can with lid and storage pockets, 2-pack.', '/assets/images/products/car-trash.jpg', 14.99, 19.99, 300, 4.2, 4567, 0, 25),
(5, 'Performance Air Filter', 'Washable high-flow performance air filter, universal fit, +15 HP gain.', '/assets/images/products/air-filter.jpg', 44.99, 59.99, 100, 4.4, 2345, 0, 25);

-- =============================================
-- Gift Cards
-- =============================================
INSERT INTO gift_cards (code, initial_value, remaining_value, status) VALUES
('SHOPX-GIFT-25AA', 25.00, 25.00, 'active'),
('SHOPX-GIFT-50BB', 50.00, 50.00, 'active'),
('SHOPX-GIFT-100CC', 100.00, 100.00, 'active'),
('SHOPX-WELCOME-50', 50.00, 50.00, 'active'),
('SHOPX-VIP-250DD', 250.00, 250.00, 'active');

-- =============================================
-- Virtual Stores
-- =============================================
INSERT INTO virtual_stores (owner_user_id, name, description, logo_url) VALUES
(1, 'TechHub Official', 'Your one-stop shop for the latest electronics and gadgets.', '/assets/images/stores/techhub.jpg'),
(1, 'Fashion Forward', 'Curated luxury fashion and accessories from around the world.', '/assets/images/stores/fashionforward.jpg'),
(1, 'GameZone Elite', 'Everything gaming — consoles, accessories, and digital content.', '/assets/images/stores/gamezone.jpg');

-- Link products to virtual stores
INSERT INTO virtual_store_products (store_id, product_id)
SELECT 1, id FROM products WHERE category_id = 1 LIMIT 20;

INSERT INTO virtual_store_products (store_id, product_id)
SELECT 2, id FROM products WHERE category_id = 2 LIMIT 20;

INSERT INTO virtual_store_products (store_id, product_id)
SELECT 3, id FROM products WHERE category_id = 4 LIMIT 20;
