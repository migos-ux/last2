<?php
class ApiController extends Controller {
    
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->json(['success' => false, 'message' => 'Token CSRF invalide']);
            return;
        }

        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        if ($productId <= 0 || $quantity <= 0) {
            $this->json(['success' => false, 'message' => 'Données invalides']);
            return;
        }

        // Vérifier que le produit existe
        $productModel = new Product();
        $product = $productModel->findById($productId);

        if (!$product) {
            $this->json(['success' => false, 'message' => 'Produit non trouvé']);
            return;
        }

        // Ajouter au panier
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        // Calculer le nombre total d'articles dans le panier
        $cartCount = array_sum($_SESSION['cart']);

        $this->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'cartCount' => $cartCount
        ]);
    }
    
    public function updateTransaction() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $transactionId = (int)$_POST['transaction_id'];
        $status = $this->sanitize($_POST['status']);
        $realAmount = isset($_POST['real_amount']) ? (float)$_POST['real_amount'] : null;
        
        $allowedStatuses = ['pending', 'completed', 'cancelled'];
        if (!in_array($status, $allowedStatuses)) {
            $this->json(['success' => false, 'message' => 'Statut invalide']);
            return;
        }
        
        $transactionModel = new Transaction();
        $updateData = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($realAmount !== null && $status === 'completed') {
            $updateData['real_amount'] = $realAmount;
        }
        
        if ($transactionModel->update($transactionId, $updateData)) {
            $this->json(['success' => true, 'message' => 'Transaction mise à jour']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
    
    public function analyticsData() {
        $this->requireAdmin();
        
        $type = isset($_GET['type']) ? $_GET['type'] : 'daily';
        $days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
        
        $transactionModel = new Transaction();
        
        switch ($type) {
            case 'daily':
                $data = $transactionModel->getDailyStats($days);
                break;
            case 'channel':
                $data = $transactionModel->getChannelStats();
                break;
            default:
                $data = [];
        }
        
        $this->json(['success' => true, 'data' => $data]);
    }
}
?>