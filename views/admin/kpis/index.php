<!-- KPIs Avancés -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin">Dashboard</a></li>
                <li class="breadcrumb-item active">KPIs Avancés</li>
            </ol>
        </nav>
        <h1 class="h3 mb-4">
            <i class="fas fa-chart-line me-2"></i>KPIs Avancés
        </h1>
    </div>
</div>

<!-- Graphique d'évolution mensuelle -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Évolution Mensuelle des Ventes</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthly-sales-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Métriques mensuelles -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Détail Mensuel</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Transactions Totales</th>
                                <th>Transactions Terminées</th>
                                <th>Chiffre d'Affaires</th>
                                <th>Panier Moyen</th>
                                <th>Taux de Conversion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthlyAnalytics as $month): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($month['month']) ?></strong></td>
                                    <td><?= number_format($month['total_transactions'] ?? 0) ?></td>
                                    <td><?= number_format($month['completed_transactions'] ?? 0) ?></td>
                                    <td class="text-success fw-bold">
                                        <?= number_format($month['total_revenue'] ?? 0, 2) ?>€
                                    </td>
                                    <td>
                                        <?= $month['avg_order_value'] ? number_format($month['avg_order_value'], 2) . '€' : '-' ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= ($month['conversion_rate'] ?? 0) > 50 ? 'success' : (($month['conversion_rate'] ?? 0) > 25 ? 'warning' : 'danger') ?>">
                                            <?= number_format($month['conversion_rate'] ?? 0, 1) ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Produits -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top 10 Produits</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topProducts)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($topProducts as $index => $product): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary rounded-pill" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                            <?= $index + 1 ?>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($product['name']) ?></div>
                                        <small class="text-muted">
                                            <?= number_format($product['total_sold'] ?? 0) ?> unités vendues
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">
                                        <?= number_format($product['price'], 2) ?>€
                                    </div>
                                    <small class="text-muted">
                                        Revenus: <?= number_format(($product['price'] * ($product['total_sold'] ?? 0)), 2) ?>€
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune donnée de vente disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Performance par Canal -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Performance par Canal</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($channelPerformance)): ?>
                    <div class="chart-container mb-3">
                        <canvas id="channel-performance-chart" height="200"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Canal</th>
                                    <th>Contacts</th>
                                    <th>Ventes</th>
                                    <th>Revenus</th>
                                    <th>Taux</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($channelPerformance as $channel): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $channelIcons = [
                                                'whatsapp' => 'fab fa-whatsapp text-success',
                                                'messenger' => 'fab fa-facebook-messenger text-primary',
                                                'email' => 'fas fa-envelope text-info',
                                                'phone' => 'fas fa-phone text-warning'
                                            ];
                                            $icon = $channelIcons[$channel['contact_channel']] ?? 'fas fa-question';
                                            ?>
                                            <i class="<?= $icon ?> me-2"></i>
                                            <?= ucfirst($channel['contact_channel']) ?>
                                        </td>
                                        <td><?= number_format($channel['total_contacts']) ?></td>
                                        <td class="text-success fw-bold">
                                            <?= number_format($channel['completed_sales']) ?>
                                        </td>
                                        <td class="text-primary fw-bold">
                                            <?= number_format($channel['revenue'] ?? 0, 2) ?>€
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= (($channel['completed_sales'] / $channel['total_contacts']) * 100) > 50 ? 'success' : 'warning' ?>">
                                                <?= number_format(($channel['total_contacts'] > 0 ? ($channel['completed_sales'] / $channel['total_contacts']) * 100 : 0), 1) ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune donnée de canal disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Métriques calculées -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Métriques Calculées</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    // Calculer les métriques globales
                    $totalRevenue = array_sum(array_column($monthlyAnalytics, 'total_revenue'));
                    $totalTransactions = array_sum(array_column($monthlyAnalytics, 'total_transactions'));
                    $totalCompleted = array_sum(array_column($monthlyAnalytics, 'completed_transactions'));
                    $avgOrderValue = $totalCompleted > 0 ? $totalRevenue / $totalCompleted : 0;
                    $overallConversion = $totalTransactions > 0 ? ($totalCompleted / $totalTransactions) * 100 : 0;
                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-euro-sign fa-2x mb-2"></i>
                                <h4 class="mb-1"><?= number_format($totalRevenue, 2) ?>€</h4>
                                <small>Revenus Totaux (12 mois)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <h4 class="mb-1"><?= number_format($totalCompleted) ?></h4>
                                <small>Commandes Terminées</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-calculator fa-2x mb-2"></i>
                                <h4 class="mb-1"><?= number_format($avgOrderValue, 2) ?>€</h4>
                                <small>Panier Moyen</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-percentage fa-2x mb-2"></i>
                                <h4 class="mb-1"><?= number_format($overallConversion, 1) ?>%</h4>
                                <small>Taux de Conversion Global</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Données pour les graphiques
const monthlyData = <?= json_encode(array_reverse($monthlyAnalytics)) ?>;
const channelData = <?= json_encode($channelPerformance) ?>;

// Graphique d'évolution mensuelle
const monthlyCtx = document.getElementById('monthly-sales-chart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Chiffre d\'Affaires (€)',
            data: monthlyData.map(item => item.total_revenue || 0),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            yAxisID: 'y'
        }, {
            label: 'Nombre de Transactions',
            data: monthlyData.map(item => item.total_transactions || 0),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Chiffre d\'Affaires (€)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Nombre de Transactions'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});

// Graphique de performance par canal
const channelCtx = document.getElementById('channel-performance-chart');
if (channelCtx) {
    channelCtx = channelCtx.getContext('2d');
    new Chart(channelCtx, {
        type: 'doughnut',
        data: {
            labels: channelData.map(item => item.contact_channel.charAt(0).toUpperCase() + item.contact_channel.slice(1)),
            datasets: [{
                data: channelData.map(item => item.total_contacts),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}
</script>
