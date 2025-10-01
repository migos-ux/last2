<!-- Page Header -->
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
                    <li class="breadcrumb-item active">Produits</li>
                </ol>
            </nav>
            <h1 class="h2 mb-4">Nos Produits</h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL ?>products">
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6>Catégories</h6>
                            <div class="list-group list-group-flush">
                                <a href="<?= BASE_URL ?>products" 
                                   class="list-group-item list-group-item-action <?= !$selectedCategory ? 'active' : '' ?>">
                                    Toutes les catégories
                                </a>
                                <?php foreach ($categories as $category): ?>
                                    <a href="<?= BASE_URL ?>products?category=<?= $category['id'] ?>" 
                                       class="list-group-item list-group-item-action <?= $selectedCategory == $category['id'] ? 'active' : '' ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6>Prix</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="min_price" placeholder="Min" step="0.01">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="max_price" placeholder="Max" step="0.01">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">
                                Appliquer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Results Info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">
                    <?= count($products) ?> produit(s) trouvé(s)
                </p>
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="view" id="grid-view" checked>
                    <label class="btn btn-outline-secondary btn-sm" for="grid-view">
                        <i class="fas fa-th"></i>
                    </label>
                    <input type="radio" class="btn-check" name="view" id="list-view">
                    <label class="btn btn-outline-secondary btn-sm" for="list-view">
                        <i class="fas fa-list"></i>
                    </label>
                </div>
            </div>

            <!-- Products -->
            <?php if (!empty($products)): ?>
                <div class="row g-4" id="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-xl-4 product-item">
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
                                            <i class="fas fa-eye me-1"></i>Voir détails
                                        </a>
                                    </div>
                                    <?php if ($product['featured']): ?>
                                        <div class="product-badge">
                                            <span class="badge bg-warning">Vedette</span>
                                        </div>
                                    <?php endif; ?>
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

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Navigation des produits" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>products?page=<?= $currentPage - 1 ?><?= $selectedCategory ? '&category=' . $selectedCategory : '' ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>products?page=<?= $i ?><?= $selectedCategory ? '&category=' . $selectedCategory : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>products?page=<?= $currentPage + 1 ?><?= $selectedCategory ? '&category=' . $selectedCategory : '' ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>Aucun produit trouvé</h3>
                    <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                    <a href="<?= BASE_URL ?>products" class="btn btn-primary">Voir tous les produits</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>