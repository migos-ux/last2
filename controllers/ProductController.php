<?php
class ProductController extends Controller {
    
    public function index() {
        $productModel = new Product();
        $categoryModel = new Category();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        if ($categoryId) {
            $products = $productModel->findByCategory($categoryId, $limit);
            $totalProducts = $productModel->count("category_id = {$categoryId} AND status = 'active'");
        } else {
            $products = $productModel->findAll($limit, $offset);
            $totalProducts = $productModel->count("status = 'active'");
        }
        
        $categories = $categoryModel->getActive();
        $totalPages = ceil($totalProducts / $limit);
        
        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'selectedCategory' => $categoryId,
            'title' => 'Nos Produits'
        ]);
    }
    
    public function show($id) {
        $productModel = new Product();
        $product = $productModel->getWithCategory($id);
        
        if (!$product) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        // Produits similaires
        $relatedProducts = $productModel->findByCategory($product['category_id'], 4);
        $relatedProducts = array_filter($relatedProducts, function($p) use ($id) {
            return $p['id'] != $id;
        });
        
        $this->view('products/show', [
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 3),
            'title' => $product['name']
        ]);
    }
    
    public function search() {
        $productModel = new Product();
        $categoryModel = new Category();
        
        $query = isset($_GET['q']) ? $this->sanitize($_GET['q']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
        
        $products = $productModel->search($query, $categoryId, $minPrice, $maxPrice);
        $categories = $categoryModel->getActive();
        
        $this->view('products/search', [
            'products' => $products,
            'categories' => $categories,
            'query' => $query,
            'selectedCategory' => $categoryId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'title' => 'Recherche: ' . $query
        ]);
    }
}
?>