<!-- Page Header -->
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>products">Produits</a></li>
                    <li class="breadcrumb-item active">Recherche</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">Résultats de recherche</h1>
                    <?php if ($query): ?>
                        <p class="text-muted">Recherche pour : "<strong><?= htmlspecialchars($query) ?></strong>"</p>
                    <?php endif; ?>
                </div>
                <div class="text-muted">
                    <?= count($products) ?> produit(s) trouvé(s)
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Affiner la recherche</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL ?>search">
                        <!-- Recherche textuelle -->
                        <div class="mb-4">
                            <label for="search-input" class="form-label">Mots-clés</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-input" name="q" 
                                       value="<?= htmlspecialchars($query) ?>" 
                                       placeholder="Rechercher un produit...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6>Catégories</h6>
                            <div class="list-group list-group-flush">
                                <label class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <input class="form-check-input me-2" type="radio" name="category" value="" 
                                               <?= !$selectedCategory ? 'checked' : '' ?>>
                                        Toutes les catégories
                                    </div>
                                </label>
                                <?php foreach ($categories as $category): ?>
                                    <label class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <input class="form-check-input me-2" type="radio" name="category" 
                                                   value="<?= $category['id'] ?>" 
                                                   <?= $selectedCategory == $category['id'] ? 'checked' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6>Fourchette de prix</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="min_price" placeholder="Min €" step="0.01" 
                                           value="<?= htmlspecialchars($minPrice ?? '') ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="max_price" placeholder="Max €" step="0.01"
                                           value="<?= htmlspecialchars($maxPrice ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Appliquer les filtres
                            </button>
                            <a href="<?= BASE_URL ?>search" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Effacer les filtres
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="col-lg-9">
            <!-- Search Summary -->
            <?php if ($query || $selectedCategory || $minPrice || $maxPrice): ?>
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div class="flex-grow-1">
                            <strong>Filtres actifs :</strong>
                            <?php if ($query): ?>
                                <span class="badge bg-primary ms-1">Recherche: "<?= htmlspecialchars($query) ?>"</span>
                            <?php endif; ?>
                            <?php if ($selectedCategory): ?>
                                <?php
                                $categoryName = '';
                                foreach ($categories as $cat) {
                                    if ($cat['id'] == $selectedCategory) {
                                        $categoryName = $cat['name'];
                                        break;
                                    }
                                }
                                ?>
                                <span class="badge bg-secondary ms-1">Catégorie: <?= htmlspecialchars($categoryName) ?></span>
                            <?php endif; ?>
                            <?php if ($minPrice): ?>
                                <span class="badge bg-success ms-1">Prix min: <?= $minPrice ?>€</span>
                            <?php endif; ?>
                            <?php if ($maxPrice): ?>
                                <span class="badge bg-warning ms-1">Prix max: <?= $maxPrice ?>€</span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= BASE_URL ?>search" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <strong><?= count($products) ?></strong> produit(s) trouvé(s)
                </div>
                <div class="d-flex align-items-center gap-3">
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
                    <select class="form-select form-select-sm" style="width: auto;" id="sort-select">
                        <option value="relevance">Pertinence</option>
                        <option value="price-asc">Prix croissant</option>
                        <option value="price-desc">Prix décroissant</option>
                        <option value="name-asc">Nom A-Z</option>
                        <option value="name-desc">Nom Z-A</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
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
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...
                                    </p>
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

                <!-- Load More Button (if needed) -->
                <?php if (count($products) >= 12): ?>
                    <div class="text-center mt-5">
                        <button class="btn btn-outline-primary btn-lg" id="load-more">
                            <i class="fas fa-plus me-2"></i>Charger plus de produits
                        </button>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- No Results -->
                <div class="text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-4"></i>
                    <h3>Aucun produit trouvé</h3>
                    <p class="text-muted mb-4">
                        <?php if ($query): ?>
                            Aucun produit ne correspond à votre recherche "<strong><?= htmlspecialchars($query) ?></strong>".
                        <?php else: ?>
                            Aucun produit ne correspond à vos critères de recherche.
                        <?php endif; ?>
                    </p>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Suggestions :</h5>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="fas fa-check text-success me-2"></i>Vérifiez l'orthographe des mots-clés</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Essayez des termes plus généraux</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Utilisez moins de filtres</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Parcourez nos catégories</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>products" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-th me-2"></i>Voir tous les produits
                        </a>
                        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Popular Searches (if no results) -->
<?php if (empty($products) && $query): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Recherches populaires</h3>
        <div class="text-center">
            <a href="<?= BASE_URL ?>search?q=smartphone" class="btn btn-outline-primary m-1">Smartphone</a>
            <a href="<?= BASE_URL ?>search?q=casque" class="btn btn-outline-primary m-1">Casque</a>
            <a href="<?= BASE_URL ?>search?q=vêtement" class="btn btn-outline-primary m-1">Vêtement</a>
            <a href="<?= BASE_URL ?>search?q=maison" class="btn btn-outline-primary m-1">Maison</a>
            <a href="<?= BASE_URL ?>search?q=sport" class="btn btn-outline-primary m-1">Sport</a>
            <a href="<?= BASE_URL ?>search?q=livre" class="btn btn-outline-primary m-1">Livre</a>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Sort functionality
document.getElementById('sort-select').addEventListener('change', function() {
    const sortValue = this.value;
    const products = Array.from(document.querySelectorAll('.product-item'));
    const container = document.getElementById('products-grid');
    
    products.sort((a, b) => {
        const priceA = parseFloat(a.querySelector('.h5').textContent.replace('€', ''));
        const priceB = parseFloat(b.querySelector('.h5').textContent.replace('€', ''));
        const nameA = a.querySelector('.card-title').textContent;
        const nameB = b.querySelector('.card-title').textContent;
        
        switch(sortValue) {
            case 'price-asc':
                return priceA - priceB;
            case 'price-desc':
                return priceB - priceA;
            case 'name-asc':
                return nameA.localeCompare(nameB);
            case 'name-desc':
                return nameB.localeCompare(nameA);
            default:
                return 0;
        }
    });
    
    // Clear and re-append sorted products
    container.innerHTML = '';
    products.forEach(product => container.appendChild(product));
});

// Auto-submit form on filter change
document.querySelectorAll('input[name="category"]').forEach(radio => {
    radio.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// Search suggestions
document.getElementById('search-input').addEventListener('input', function() {
    // Here you could implement search suggestions via AJAX
    // For now, we'll just add some visual feedback
    const value = this.value;
    if (value.length > 2) {
        this.style.borderColor = '#28a745';
    } else {
        this.style.borderColor = '';
    }
});
</script>