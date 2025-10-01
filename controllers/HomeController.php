<?php
class HomeController extends Controller {
    
    public function index() {
        $productModel = new Product();
        $categoryModel = new Category();

        $featuredProducts = [];
        $categories = [];
        $topSellingProducts = [];

        try {
            $featuredProducts = $productModel->getFeatured(8);
            $categories = $categoryModel->getWithProductCount();
            $topSellingProducts = $productModel->getTopSelling(6);
        } catch (Exception $e) {
            // Handle database errors gracefully
            error_log('Database error in HomeController: ' . $e->getMessage());
        }

        $this->view('home/index', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'topSellingProducts' => $topSellingProducts,
            'title' => 'Accueil - Boutique en ligne'
        ]);
    }
}
?>