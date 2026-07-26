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

    public function download(): void
    {
        // Ensure apk exists in public
        if (file_exists(ROOT_PATH . '/app-release.apk') && !file_exists(PUBLIC_PATH . '/app-release.apk')) {
            @copy(ROOT_PATH . '/app-release.apk', PUBLIC_PATH . '/app-release.apk');
        }

        $this->render('download/index', [
            'pageTitle' => 'Download Mobile App — ShopX Global'
        ]);
    }

    public function downloadApk(): void
    {
        $file = PUBLIC_PATH . '/app-release.apk';
        if (!file_exists($file)) {
            $file = ROOT_PATH . '/app-release.apk';
        }
        
        if (file_exists($file)) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.android.package-archive');
            header('Content-Disposition: attachment; filename="app-release.apk"');
            header('Content-Length: ' . filesize($file));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Expires: 0');
            readfile($file);
            exit;
        }

        http_response_code(404);
        echo 'File not found.';
        exit;
    }
}
