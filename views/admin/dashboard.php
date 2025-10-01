<!-- Dashboard Admin -->
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-number" id="total-transactions"><?= $analytics['total_transactions'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Ventes Terminées</div>
                        <div class="stat-number" id="completed-sales"><?= $analytics['completed_transactions'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Chiffre d'Affaires</div>
                        <div class="stat-number" id="total-revenue"><?= number_format($analytics['total_revenue'] ?? 0, 2) ?>€</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card danger h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Taux de Conversion</div>
                        <div class="stat-number" id="conversion-rate"><?= number_format($analytics['conversion_rate'], 1) ?>%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line stat-icon text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique des ventes -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Évolution des Ventes</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="daily-sales-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par canal -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Canaux de Contact</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="channel-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Transactions récentes -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Transactions Récentes</h6>
                <a href="<?= BASE_URL ?>admin/transactions" class="btn btn-primary btn-sm">
                    <i class="fas fa-list me-1"></i>Voir tout
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Canal</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recentTransactions, 0, 10) as $transaction): ?>
                                    <tr>
                                        <td>#<?= $transaction['id'] ?></td>
                                        <td>
                                            <?php if ($transaction['customer_email']): ?>
                                                <?= htmlspecialchars($transaction['customer_email']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Invité</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($transaction['total_amount'], 2) ?>€</td>
                                        <td>
                                            <?php
                                            $channelIcons = [
                                                'whatsapp' => 'fab fa-whatsapp text-success',
                                                'messenger' => 'fab fa-facebook-messenger text-primary',
                                                'email' => 'fas fa-envelope text-info',
                                                'phone' => 'fas fa-phone text-warning'
                                            ];
                                            $icon = $channelIcons[$transaction['contact_channel']] ?? 'fas fa-question';
                                            ?>
                                            <i class="<?= $icon ?> me-1"></i>
                                            <?= ucfirst($transaction['contact_channel']) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClasses = [
                                                'pending' => 'status-pending',
                                                'completed' => 'status-completed',
                                                'cancelled' => 'status-cancelled'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'completed' => 'Terminé',
                                                'cancelled' => 'Annulé'
                                            ];
                                            ?>
                                            <span class="status-badge <?= $statusClasses[$transaction['status']] ?>">
                                                <?= $statusLabels[$transaction['status']] ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune transaction récente</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top produits -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Produits</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($topProducts)): ?>
                    <?php foreach (array_slice($topProducts, 0, 5) as $index => $product): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                    <?= $index + 1 ?>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= htmlspecialchars($product['name']) ?></div>
                                <small class="text-muted"><?= $product['total_sold'] ?? 0 ?> vendus</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success"><?= number_format($product['price'], 2) ?>€</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune donnée de vente</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>admin/products/create" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div>Ajouter Produit</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>admin/transactions" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <div>Voir Transactions</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>admin/analytics" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <div>Analytics</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>" class="btn btn-primary btn-lg w-100" target="_blank">
                            <i class="fas fa-external-link-alt fa-2x mb-2"></i>
                            <div>Voir le Site</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>