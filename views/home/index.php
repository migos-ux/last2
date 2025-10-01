<?php
// URLs d'images en ligne pour le carrousel (images publiques depuis Unsplash comme exemple)
$carouselImages = [
    [
        'src' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Chaussures modernes',
        'link' => BASE_URL . 'product/1'
    ],
    [
        'src' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Écouteurs sans fil',
        'link' => BASE_URL . 'product/2'
    ],
    [
        'src' => 'https://images.unsplash.com/photo-1503602642458-232111445657?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Sac à dos élégant',
        'link' => BASE_URL . 'product/3'
    ]
];
// Pour utiliser des produits réels avec des URLs en ligne, tu peux faire :
// $carouselImages = array_map(function($product) {
//     return [
//         'src' => $product['external_image_url'], // URL externe depuis ta base de données
//         'alt' => htmlspecialchars($product['name']),
//         'link' => BASE_URL . 'product/' . $product['id']
//     ];
// }, array_slice($featuredProducts, 0, 3));
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content animate-fade-in">
                    <h1 class="display-4 fw-bold mb-4">Bienvenue dans notre boutique</h1>
                    <p class="lead mb-4">Découvrez notre sélection de produits de qualité à des prix exceptionnels.</p>
                    <a href="<?= BASE_URL ?>products" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Découvrir nos produits
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <div class="carousel">
                        <?php foreach ($carouselImages as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <a href="<?= $image['link'] ?>" class="carousel-link">
                                    <img src="<?= $image['src'] ?>" 
                                         class="carousel-image" 
                                         alt="<?= htmlspecialchars($image['alt']) ?>">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Nos Catégories</h2>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card category-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="fas fa-tag fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                            <p class="text-muted"><?= $category['product_count'] ?> produits</p>
                            <a href="<?= BASE_URL ?>products?category=<?= $category['id'] ?>" class="btn btn-outline-primary">
                                Voir les produits
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Produits en Vedette</h2>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-container">
                            <?php if ($product['image']): ?>
                                <img src="<?= BASE_URL . UPLOAD_PATH . $product['image'] ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top product-image-placeholder d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="product-overlay">
                                <a href="<?= BASE_URL ?>product/<?= $product['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0"><?= number_format($product['price'], 2) ?>€</span>
                                <button class="btn btn-outline-primary btn-sm add-to-cart" 
                                        data-product-id="<?= $product['id'] ?>">
                                    <i class="fas fa-cart-plus me-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>products" class="btn btn-primary btn-lg">
                Voir tous les produits <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Top Selling Products -->
<?php if (!empty($topSellingProducts)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Meilleures Ventes</h2>
        <div class="row g-4">
            <?php foreach ($topSellingProducts as $product): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-container">
                            <?php if ($product['image']): ?>
                                <img src="<?= BASE_URL . UPLOAD_PATH . $product['image'] ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top product-image-placeholder d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="product-badge">
                                <span class="badge bg-success">Top vente</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0"><?= number_format($product['price'], 2) ?>€</span>
                                <small class="text-muted"><?= $product['total_sold'] ?? 0 ?> vendus</small>
                            </div>
                            <div class="mt-3">
                                <a href="<?= BASE_URL ?>product/<?= $product['id'] ?>" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                                <button class="btn btn-outline-primary btn-sm add-to-cart" 
                                        data-product-id="<?= $product['id'] ?>">
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

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Prêt à commander ?</h2>
        <p class="lead mb-4">Parcourez notre catalogue et contactez-nous directement via WhatsApp ou Messenger pour finaliser votre commande.</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-flex justify-content-center gap-3">
                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="btn btn-success btn-lg" target="_blank">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                    <a href="https://m.me/<?= FACEBOOK_PAGE ?>" class="btn btn-primary btn-lg" target="_blank">
                        <i class="fab fa-facebook-messenger me-2"></i>Messenger
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>