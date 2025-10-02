<?php
// Basic placeholder for products management view
?>
<div class="container mt-4">
    <h1 class="mb-4">Gestion des Produits</h1>
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>admin/products/create" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Ajouter un produit
    </a>

    <?php if (!empty($products)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                        <td><?= number_format($product['price'], 2) ?>€</td>
                        <td><?= htmlspecialchars($product['country'] ?? 'FR') ?></td>
                        <td><?= htmlspecialchars(ucfirst($product['status'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>admin/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="<?= BASE_URL ?>admin/products/delete/<?= $product['id'] ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun produit trouvé.</p>
    <?php endif; ?>
</div>
