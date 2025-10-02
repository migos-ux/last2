<!-- En-tête de page -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Ajouter un Produit</h1>
        <p class="text-muted">Créez un nouveau produit pour votre catalogue</p>
    </div>
    <a href="<?= BASE_URL ?>admin/products" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Informations du Produit</h5>
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
                                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Prix (€) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Décrivez votre produit..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                                <?= ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
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
                                    <option value="active" <?= ($_POST['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Actif</option>
                                    <option value="inactive" <?= ($_POST['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Pays de publication *</label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="FR" <?= ($_POST['country'] ?? 'FR') == 'FR' ? 'selected' : '' ?>>France</option>
                            <option value="BE" <?= ($_POST['country'] ?? '') == 'BE' ? 'selected' : '' ?>>Belgique</option>
                            <option value="CH" <?= ($_POST['country'] ?? '') == 'CH' ? 'selected' : '' ?>>Suisse</option>
                            <option value="CA" <?= ($_POST['country'] ?? '') == 'CA' ? 'selected' : '' ?>>Canada</option>
                            <option value="LU" <?= ($_POST['country'] ?? '') == 'LU' ? 'selected' : '' ?>>Luxembourg</option>
                            <option value="MC" <?= ($_POST['country'] ?? '') == 'MC' ? 'selected' : '' ?>>Monaco</option>
                        </select>
                        <div class="form-text">Choisissez le pays où ce produit sera visible</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1"
                                   <?= ($_POST['featured'] ?? '') ? 'checked' : '' ?>>
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
                            <i class="fas fa-save me-2"></i>Créer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Upload d'image -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-image me-2"></i>Image du Produit</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="image" class="form-label">Choisir une image</label>
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

        <!-- Conseils -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Utilisez des noms de produits descriptifs</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Ajoutez une description détaillée</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Choisissez une image de haute qualité</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Définissez un prix compétitif</small>
                    </li>
                    <li>
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Marquez comme vedette vos meilleurs produits</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>