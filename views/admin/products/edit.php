<!-- En-tête de page -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Modifier le Produit</h1>
        <p class="text-muted">Modifiez les informations de "<?= htmlspecialchars($product['name']) ?>"</p>
    </div>
    <div class="btn-group">
        <a href="<?= BASE_URL ?>product/<?= $product['id'] ?>" class="btn btn-outline-info" target="_blank">
            <i class="fas fa-eye me-2"></i>Voir sur le site
        </a>
        <a href="<?= BASE_URL ?>admin/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Informations du Produit</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs détectées :</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" id="product-form">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du produit *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Prix (€) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Décrivez votre produit..."><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" 
                                                <?= ($_POST['category_id'] ?? $product['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?= ($_POST['status'] ?? $product['status']) == 'active' ? 'selected' : '' ?>>Actif</option>
                                    <option value="inactive" <?= ($_POST['status'] ?? $product['status']) == 'inactive' ? 'selected' : '' ?>>Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1"
                                   <?= ($_POST['featured'] ?? $product['featured']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="featured">
                                <i class="fas fa-star text-warning me-1"></i>Produit vedette
                                <small class="text-muted d-block">Ce produit sera mis en avant sur la page d'accueil</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>admin/products" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Image actuelle et upload -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-image me-2"></i>Image du Produit</h6>
            </div>
            <div class="card-body">
                <?php if ($product['image']): ?>
                    <div class="text-center mb-3">
                        <img src="<?= BASE_URL . UPLOAD_PATH . $product['image'] ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="img-fluid rounded" style="max-height: 200px;">
                        <div class="mt-2">
                            <small class="text-muted">Image actuelle</small>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="image" class="form-label">
                        <?= $product['image'] ? 'Changer l\'image' : 'Ajouter une image' ?>
                    </label>
                    <input type="file" class="form-control" id="product-image" name="image" 
                           accept="image/*">
                    <div class="form-text">
                        Formats acceptés : JPG, PNG, GIF, WEBP<br>
                        Taille maximale : 5MB
                    </div>
                </div>
                <div id="image-preview" style="display: none;" class="text-center">
                    <!-- L'aperçu sera inséré ici par JavaScript -->
                </div>
            </div>
        </div>

        <!-- Statistiques du produit -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">0</h5>
                            <small class="text-muted">Vues</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success mb-0">0</h5>
                        <small class="text-muted">Ventes</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <small class="text-muted">
                        Créé le <?= date('d/m/Y', strtotime($product['created_at'])) ?>
                        <?php if ($product['updated_at']): ?>
                            <br>Modifié le <?= date('d/m/Y', strtotime($product['updated_at'])) ?>
                        <?php endif; ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>