<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(): void
    {
        $hotProducts = Product::getHot(8);
        $categories = Category::getWithProductCount();
        
        $this->render('home/index', [
            'pageTitle' => 'ShopX Global — Premium Global Marketplace',
            'hotProducts' => $hotProducts,
            'categories' => $categories,
        ]);
    }

    public function offline(): void
    {
        $this->render('offline', [
            'pageTitle' => 'Offline — ShopX Global'
        ]);
    }
}
