<!-- Page Header -->
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
                    <li class="breadcrumb-item active">Mon Panier</li>
                </ol>
            </nav>
            <h1 class="h2 mb-4">
                <i class="fas fa-shopping-cart me-2"></i>Mon Panier
            </h1>
        </div>
    </div>
</div>

<div class="container pb-5">
    <?php if (!empty($cartItems)): ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Articles dans votre panier (<?= count($cartItems) ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($cartItems as $index => $item): ?>
                            <div class="cart-item p-4 <?= $index < count($cartItems) - 1 ? 'border-bottom' : '' ?>">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2">
                                        <?php if ($item['product']['image']): ?>
                                            <img src="<?= BASE_URL . UPLOAD_PATH . $item['product']['image'] ?>" 
                                                 class="img-fluid rounded" 
                                                 alt="<?= htmlspecialchars($item['product']['name']) ?>"
                                                 style="max-height: 80px;">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="col-md-4">
                                        <h6 class="mb-1">
                                            <a href="<?= BASE_URL ?>product/<?= $item['product']['id'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($item['product']['name']) ?>
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <?= htmlspecialchars(substr($item['product']['description'], 0, 60)) ?>...
                                        </p>
                                        <small class="text-primary"><?= number_format($item['product']['price'], 2) ?>€ / unité</small>
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="col-md-3">
                                        <div class="input-group" style="max-width: 130px;">
                                            <button class="btn btn-outline-secondary btn-sm update-quantity" 
                                                    data-product-id="<?= $item['product']['id'] ?>" 
                                                    data-action="decrease">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm text-center quantity-input" 
                                                   value="<?= $item['quantity'] ?>" 
                                                   min="1" 
                                                   data-product-id="<?= $item['product']['id'] ?>">
                                            <button class="btn btn-outline-secondary btn-sm update-quantity" 
                                                    data-product-id="<?= $item['product']['id'] ?>" 
                                                    data-action="increase">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal and Remove -->
                                    <div class="col-md-2 text-end">
                                        <div class="fw-bold text-primary mb-2">
                                            <?= number_format($item['subtotal'], 2) ?>€
                                        </div>
                                        <button class="btn btn-outline-danger btn-sm remove-item" 
                                                data-product-id="<?= $item['product']['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Continue Shopping -->
                <div class="mt-4">
                    <a href="<?= BASE_URL ?>products" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
                    </a>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Résumé de la commande</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Sous-total:</span>
                            <span><?= number_format($total, 2) ?>€</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Livraison:</span>
                            <span class="text-muted">À définir</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong class="text-primary"><?= number_format($total, 2) ?>€</strong>
                        </div>
                        
                        <!-- Contact Buttons -->
                        <div class="d-grid gap-2">
                            <form method="POST" action="<?= BASE_URL ?>cart/contact">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="channel" value="whatsapp">
                                <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                    <i class="fab fa-whatsapp me-2"></i>Commander via WhatsApp
                                </button>
                            </form>

                            <form method="POST" action="<?= BASE_URL ?>cart/contact">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="channel" value="messenger">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fab fa-facebook-messenger me-2"></i>Commander via Messenger
                                </button>
                            </form>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Vous serez redirigé vers votre application de messagerie préférée avec le détail de votre commande.
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Security Info -->
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <div class="row g-3">
                            <div class="col-6">
                                <i class="fas fa-shield-alt text-success fa-2x mb-2"></i>
                                <div class="small">Paiement sécurisé</div>
                            </div>
                            <div class="col-6">
                                <i class="fas fa-truck text-primary fa-2x mb-2"></i>
                                <div class="small">Livraison rapide</div>
                            </div>
                            <div class="col-6">
                                <i class="fas fa-undo text-warning fa-2x mb-2"></i>
                                <div class="small">Retour gratuit</div>
                            </div>
                            <div class="col-6">
                                <i class="fas fa-headset text-info fa-2x mb-2"></i>
                                <div class="small">Support 24/7</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h3>Votre panier est vide</h3>
            <p class="text-muted mb-4">Découvrez nos produits et ajoutez-les à votre panier pour commencer vos achats.</p>
            <a href="<?= BASE_URL ?>products" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Découvrir nos produits
            </a>
        </div>
    <?php endif; ?>
</div>