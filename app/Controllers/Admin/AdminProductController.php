<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\Category;

class AdminProductController extends Controller
{
    public function index(): void
    {
        $products = Product::all('created_at', 'DESC');
        // Map category names
        $categories = Category::all();
        $catMap = [];
        foreach ($categories as $c) {
            $catMap[$c['id']] = $c['name'];
        }
        foreach ($products as &$p) {
            $p['category_name'] = $catMap[$p['category_id']] ?? 'Unknown';
        }

        $this->render('admin/products/index', [
            'pageTitle' => 'Inventory Management',
            'products' => $products,
        ], 'admin');
    }

    public function create(): void
    {
        $categories = Category::all();
        $this->render('admin/products/create', [
            'pageTitle' => 'Add New Product',
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void
    {
        $this->validateCsrf();

        $title = trim($this->input('title', ''));
        $description = trim($this->input('description', ''));
        $price = (float) $this->input('price', 0);
        $originalPrice = $this->input('original_price', null);
        $originalPrice = $originalPrice !== '' ? (float) $originalPrice : null;
        $stock = (int) $this->input('stock', 0);
        $categoryId = (int) $this->input('category_id', 0);
        $isHot = $this->input('is_hot', 0) ? 1 : 0;
        
        // Process uploaded image file if present
        $uploadedPath = $this->handleImageUpload('/admin/products/create');
        if ($uploadedPath === false) {
            return;
        }
        $imageUrl = $uploadedPath ?: trim($this->input('image_url', ''));

        // Basic validations
        if (empty($title) || $price <= 0 || $categoryId <= 0) {
            Session::flash('error', 'Title, price, and category are required.');
            $this->redirect('/admin/products/create');
            return;
        }

        // Calculate discount percent
        $discountPercent = 0;
        if ($originalPrice && $originalPrice > $price) {
            $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
        }

        Product::create([
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'original_price' => $originalPrice,
            'stock' => $stock,
            'image_url' => $imageUrl,
            'is_hot' => $isHot,
            'discount_percent' => $discountPercent,
            'is_active' => 1
        ]);

        Session::flash('success', 'Product created successfully.');
        $this->redirect('/admin/products');
    }

    public function edit(string $id): void
    {
        $product = Product::find((int) $id);
        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect('/admin/products');
            return;
        }

        $categories = Category::all();
        $this->render('admin/products/edit', [
            'pageTitle' => 'Edit Product #' . $id,
            'product' => $product,
            'categories' => $categories,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $product = Product::find((int) $id);
        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect('/admin/products');
            return;
        }

        $title = trim($this->input('title', ''));
        $description = trim($this->input('description', ''));
        $price = (float) $this->input('price', 0);
        $originalPrice = $this->input('original_price', null);
        $originalPrice = $originalPrice !== '' ? (float) $originalPrice : null;
        $stock = (int) $this->input('stock', 0);
        $categoryId = (int) $this->input('category_id', 0);
        $isHot = $this->input('is_hot', 0) ? 1 : 0;

        // Process uploaded image file if present
        $uploadedPath = $this->handleImageUpload('/admin/products/edit/' . $id);
        if ($uploadedPath === false) {
            return;
        }
        $inputImageUrl = trim($this->input('image_url', ''));
        $imageUrl = $uploadedPath ?: ($inputImageUrl !== '' ? $inputImageUrl : $product['image_url']);

        if (empty($title) || $price <= 0 || $categoryId <= 0) {
            Session::flash('error', 'Title, price, and category are required.');
            $this->redirect('/admin/products/edit/' . $id);
            return;
        }

        $discountPercent = 0;
        if ($originalPrice && $originalPrice > $price) {
            $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
        }

        Product::update((int) $id, [
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'original_price' => $originalPrice,
            'stock' => $stock,
            'image_url' => $imageUrl,
            'is_hot' => $isHot,
            'discount_percent' => $discountPercent
        ]);

        Session::flash('success', 'Product updated successfully.');
        $this->redirect('/admin/products');
    }

    private function handleImageUpload(?string $redirectUrl = null): string|bool|null
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $filename = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowed = ['png', 'jpg', 'jpeg', 'webp', 'gif'];

            if (in_array($ext, $allowed)) {
                $uploadDir = PUBLIC_PATH . '/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $newFilename = 'product_' . uniqid('', true) . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                    return 'uploads/products/' . $newFilename;
                }
            } else {
                Session::flash('error', 'Product image must be in PNG, JPG, JPEG, WEBP, or GIF format.');
                if ($redirectUrl) {
                    $this->redirect($redirectUrl);
                }
                return false;
            }
        }
        return null;
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        Product::delete((int) $id);
        Session::flash('success', 'Product deleted successfully.');
        $this->redirect('/admin/products');
    }
}
