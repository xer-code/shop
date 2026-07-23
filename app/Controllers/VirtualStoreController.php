<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\VirtualStore;

class VirtualStoreController extends Controller
{
    public function index(): void
    {
        $stores = VirtualStore::getWithProductCount();
        $this->render('virtualstore/index', [
            'pageTitle' => 'Virtual Stores — ShopX Global',
            'stores' => $stores,
        ]);
    }
    
    public function show(string $id): void
    {
        $store = VirtualStore::find((int) $id);
        if (!$store) { $this->redirect('/virtual-stores'); return; }
        
        $products = VirtualStore::getProducts((int) $id);
        $this->render('virtualstore/show', [
            'pageTitle' => $store['name'] . ' — ShopX Global',
            'store' => $store,
            'products' => $products,
        ]);
    }
}
