<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;

class ShopController extends Controller
{
    public function index(): void
    {
        $filters = [
            'search' => $this->query('search', ''),
            'category_id' => $this->query('category', ''),
            'sort' => $this->query('sort', 'newest'),
        ];
        
        // If category is a slug, resolve to ID
        if (!empty($filters['category_id']) && !is_numeric($filters['category_id'])) {
            $cat = Category::findBySlug($filters['category_id']);
            $filters['category_id'] = $cat ? $cat['id'] : '';
        }
        
        $page = max(1, (int) $this->query('page', 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $products = Product::getFiltered($filters, $perPage, $offset);
        $totalProducts = Product::countFiltered($filters);
        $totalPages = ceil($totalProducts / $perPage);
        $categories = Category::getWithProductCount();
        
        $this->render('shop/index', [
            'pageTitle' => 'Shop — ShopX Global',
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters,
            'totalProducts' => $totalProducts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
    
    public function show(string $id): void
    {
        $product = Product::find((int)$id);
        if (!$product) {
            $this->redirect('/shop');
            return;
        }
        
        $related = Product::where(['category_id' => $product['category_id']], 'id', 'DESC', 4);
        $related = array_filter($related, fn($p) => $p['id'] != $product['id']);
        
        $category = Category::find($product['category_id']);
        $product['category_name'] = $category['name'] ?? '';
        
        $this->render('shop/show', [
            'pageTitle' => $product['title'] . ' — ShopX Global',
            'product' => $product,
            'related' => array_values($related),
        ]);
    }
    
    public function toggleWishlist(): void
    {
        if (!Auth::check()) {
            $this->json(['error' => 'Please sign in'], 401);
            return;
        }
        
        $productId = (int) $this->input('product_id');
        $isAdded = Wishlist::toggle(Auth::id(), $productId);
        $this->json(['wishlisted' => $isAdded]);
    }
}
