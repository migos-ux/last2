<!-- Breadcrumb -->
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>products">Produits</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>
</div>

<div class="container pb-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="product-image-zoom">
                <?php if ($product['image']): ?>
                    <img src="<?= BASE_URL . UPLOAD_PATH . $product['image'] ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         id="main-product-image">
                <?php else: ?>
                    <div class="product-image-placeholder d-flex align-items-center justify-content-center rounded shadow">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="product-details">
                <?php if ($product['featured']): ?>
                    <span class="badge bg-warning mb-3">Produit vedette</span>
                <?php endif; ?>
                
                <h1 class="h2 mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                
                <?php if ($product['category_name']): ?>
                    <p class="text-muted mb-3">
                        <i class="fas fa-tag me-2"></i>
                        <a href="<?= BASE_URL ?>products?category=<?= $product['category_id'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($product['category_name']) ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <div class="price-section mb-4">
                    <span class="h2 text-primary"><?= number_format($product['price'], 2) ?>€</span>
                </div>
                
                <div class="description mb-4">
                    <h5>Description</h5>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                
                <!-- Add to Cart Form -->
                <form class="add-to-cart-form mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-auto">
                            <label for="quantity" class="form-label">Quantité</label>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" id="decrease-qty">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary" type="button" id="increase-qty">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-lg" data-product-id="<?= $product['id'] ?>">
                                <i class="fas fa-cart-plus me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Quick Actions -->
                <div class="quick-actions mb-4">
                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Je suis intéressé par le produit: <?= urlencode($product['name']) ?>" 
                       class="btn btn-success me-2" target="_blank">
                        <i class="fab fa-whatsapp me-2"></i>Contacter via WhatsApp
                    </a>
                    <a href="https://m.me/<?= FACEBOOK_PAGE ?>?text=Je suis intéressé par le produit: <?= urlencode($product['name']) ?>" 
                       class="btn btn-primary" target="_blank">
                        <i class="fab fa-facebook-messenger me-2"></i>Contacter via Messenger
                    </a>
                </div>
                
                <!-- Product Info -->
                <div class="product-info">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-item">
                                <i class="fas fa-truck text-primary me-2"></i>
                                <small>Livraison disponible</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                <small>Produit garanti</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <i class="fas fa-headset text-info me-2"></i>
                                <small>Support client</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <i class="fas fa-undo text-warning me-2"></i>
                                <small>Retour possible</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="mb-4">Produits similaires</h3>
        <div class="row g-4">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-container">
                            <?php if ($relatedProduct['image']): ?>
                                <img src="<?= BASE_URL . UPLOAD_PATH . $relatedProduct['image'] ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?= htmlspecialchars($relatedProduct['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top product-image-placeholder d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="product-overlay">
                                <a href="<?= BASE_URL ?>product/<?= $relatedProduct['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($relatedProduct['name']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars(substr($relatedProduct['description'], 0, 80)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 text-primary mb-0"><?= number_format($relatedProduct['price'], 2) ?>€</span>
                                <button class="btn btn-outline-primary btn-sm add-to-cart" 
                                        data-product-id="<?= $relatedProduct['id'] ?>">
                                    <i class="fas fa-cart-plus me-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>