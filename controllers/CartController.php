<?php
class CartController extends Controller {
    
    public function index() {
        $cart = $this->getCart();
        $cartItems = [];
        $total = 0;
        
        if (!empty($cart)) {
            $productModel = new Product();
            
            foreach ($cart as $productId => $quantity) {
                $product = $productModel->findById($productId);
                if ($product) {
                    $subtotal = $product['price'] * $quantity;
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ];
                    $total += $subtotal;
                }
            }
        }
        
        $this->view('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'title' => 'Mon Panier'
        ]);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            if ($productId > 0 && $quantity > 0) {
                $this->addToCart($productId, $quantity);

                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => true,
                        'message' => 'Produit ajouté au panier',
                        'cartCount' => $this->getCartCount()
                    ]);
                } else {
                    $_SESSION['flash_message'] = 'Produit ajouté au panier avec succès!';
                    $this->redirect('cart');
                }
            }
        }

        $this->redirect('products');
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            if ($quantity > 0) {
                $this->updateCartItem($productId, $quantity);
            } else {
                $this->removeFromCart($productId);
            }

            if (isset($_POST['ajax'])) {
                $this->json(['success' => true]);
            } else {
                $this->redirect('cart');
            }
        }
    }
    
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $productId = (int)$_POST['product_id'];
            $this->removeFromCart($productId);

            if (isset($_POST['ajax'])) {
                $this->json(['success' => true]);
            } else {
                $this->redirect('cart');
            }
        }
    }
    
    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
        }

        $cart = $this->getCart();

        if (empty($cart)) {
            $_SESSION['flash_error'] = 'Votre panier est vide!';
            $this->redirect('cart');
            return;
        }
        
        $cartItems = [];
        $total = 0;
        $productModel = new Product();
        
        foreach ($cart as $productId => $quantity) {
            $product = $productModel->findById($productId);
            if ($product) {
                $subtotal = $product['price'] * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }
        
        // Générer le message pour les plateformes de messagerie
        $message = $this->generateContactMessage($cartItems, $total);
        
        // Enregistrer la transaction
        $transactionModel = new Transaction();
        $transactionData = [
            'user_id' => isset($_SESSION['user']) ? $_SESSION['user']['id'] : null,
            'total_amount' => $total,
            'status' => 'pending',
            'contact_channel' => $_POST['channel'] ?? 'whatsapp',
            'customer_info' => json_encode([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $transactionItems = [];
        foreach ($cartItems as $item) {
            $transactionItems[] = [
                'product_id' => $item['product']['id'],
                'quantity' => $item['quantity'],
                'price' => $item['product']['price']
            ];
        }
        
        $transactionId = $transactionModel->createWithItems($transactionData, $transactionItems);
        
        // Générer les liens de contact
        $whatsappLink = $this->generateWhatsAppLink($message);
        $messengerLink = $this->generateMessengerLink($message);
        
        // Vider le panier après avoir initié la transaction
        unset($_SESSION['cart']);
        $_SESSION['cart_count'] = 0;
        
        $this->view('cart/contact', [
            'message' => $message,
            'whatsappLink' => $whatsappLink,
            'messengerLink' => $messengerLink,
            'transactionId' => $transactionId,
            'total' => $total,
            'title' => 'Contacter le vendeur'
        ]);
        exit; // Arrêter l'exécution après l'affichage de la vue
    }
    
    private function getCart() {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }
    
    private function addToCart($productId, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            $_SESSION['cart_count'] = 0;
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        // Mettre à jour le compteur
        $_SESSION['cart_count'] += $quantity;
    }
    
    private function updateCartItem($productId, $quantity) {
        if (isset($_SESSION['cart'][$productId])) {
            $oldQuantity = $_SESSION['cart'][$productId];
            $_SESSION['cart'][$productId] = $quantity;
            // Mettre à jour le compteur
            $_SESSION['cart_count'] += ($quantity - $oldQuantity);
        }
    }
    
    private function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            $quantity = $_SESSION['cart'][$productId];
            unset($_SESSION['cart'][$productId]);
            // Mettre à jour le compteur
            $_SESSION['cart_count'] -= $quantity;
            if ($_SESSION['cart_count'] < 0) {
                $_SESSION['cart_count'] = 0;
            }
        }
    }
    
    private function generateContactMessage($cartItems, $total) {
        $message = "🛒 *Nouvelle commande*\n\n";
        
        foreach ($cartItems as $item) {
            $message .= "• {$item['product']['name']}\n";
            $message .= "  Quantité: {$item['quantity']}\n";
            $message .= "  Prix unitaire: {$item['product']['price']}€\n";
            $message .= "  Sous-total: {$item['subtotal']}€\n\n";
        }
        
        $message .= "💰 *Total: {$total}€*\n\n";
        $message .= "Je suis intéressé(e) par cette commande. Pouvez-vous me contacter pour finaliser l'achat ?";
        
        return $message;
    }
    
    private function generateWhatsAppLink($message) {
        $encodedMessage = urlencode($message);
        return "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . $encodedMessage;
    }
    
    private function generateMessengerLink($message) {
        $encodedMessage = urlencode($message);
        return "https://m.me/" . FACEBOOK_PAGE . "?text=" . $encodedMessage;
    }

    private function getCartCount() {
        if (!isset($_SESSION['cart_count'])) {
            $cart = $this->getCart();
            $_SESSION['cart_count'] = 0;
            foreach ($cart as $quantity) {
                $_SESSION['cart_count'] += $quantity;
            }
        }
        return $_SESSION['cart_count'];
    }
}
?>