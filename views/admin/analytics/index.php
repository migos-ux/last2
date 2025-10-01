<!-- En-tête de page -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Analytics & KPIs</h1>
        <p class="text-muted">Analysez les performances de votre boutique</p>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>Imprimer
        </button>
        <button type="button" class="btn btn-outline-success export-btn" data-format="csv" data-type="analytics">
            <i class="fas fa-file-csv me-2"></i>Exporter
        </button>
    </div>
</div>

<!-- Filtres de période -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Date de début</label>
                <input type="date" class="form-control" id="start-date" name="start_date" 
                       value="<?= $startDate ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date de fin</label>
                <input type="date" class="form-control" id="end-date" name="end_date" 
                       value="<?= $endDate ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-chart-line me-2"></i>Actualiser
                </button>
            </div>
            <div class="col-md-3">
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange(7)">7j</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange(30)">30j</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange(90)">90j</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- KPIs principaux -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-number text-primary"><?= $analytics['total_transactions'] ?></div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up me-1"></i>+12% vs période précédente
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Ventes Terminées</div>
                        <div class="stat-number text-success"><?= $analytics['completed_transactions'] ?></div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up me-1"></i>+8% vs période précédente
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
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
                        <div class="stat-number text-warning"><?= number_format($analytics['total_revenue'], 2) ?>€</div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up me-1"></i>+15% vs période précédente
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-warning"></i>
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
                        <div class="stat-label">Taux de Conversion</div>
                        <div class="stat-number text-info"><?= number_format($analytics['conversion_rate'], 1) ?>%</div>
                        <div class="stat-change text-danger">
                            <i class="fas fa-arrow-down me-1"></i>-2% vs période précédente
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mb-4">
    <!-- Évolution des ventes -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Évolution des Ventes (30 derniers jours)</h6>
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
        <div class="card shadow">
            <div class="card-header">
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

<!-- Tableaux détaillés -->
<div class="row">
    <!-- Performance par canal -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Performance par Canal</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($channelStats)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Canal</th>
                                    <th>Contacts</th>
                                    <th>Ventes</th>
                                    <th>Conversion</th>
                                    <th>CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($channelStats as $stat): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $channelIcons = [
                                                'whatsapp' => 'fab fa-whatsapp text-success',
                                                'messenger' => 'fab fa-facebook-messenger text-primary',
                                                'email' => 'fas fa-envelope text-info',
                                                'phone' => 'fas fa-phone text-warning'
                                            ];
                                            $icon = $channelIcons[$stat['contact_channel']] ?? 'fas fa-question';
                                            ?>
                                            <i class="<?= $icon ?> me-2"></i>
                                            <?= ucfirst($stat['contact_channel']) ?>
                                        </td>
                                        <td><?= $stat['total_contacts'] ?></td>
                                        <td><?= $stat['completed_sales'] ?></td>
                                        <td>
                                            <?php 
                                            $conversion = $stat['total_contacts'] > 0 ? ($stat['completed_sales'] / $stat['total_contacts']) * 100 : 0;
                                            ?>
                                            <span class="badge bg-<?= $conversion > 50 ? 'success' : ($conversion > 25 ? 'warning' : 'danger') ?>">
                                                <?= number_format($conversion, 1) ?>%
                                            </span>
                                        </td>
                                        <td class="fw-bold text-success"><?= number_format($stat['revenue'], 2) ?>€</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune donnée disponible pour cette période</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistiques quotidiennes -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Activité Quotidienne (7 derniers jours)</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($dailyStats)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Transactions</th>
                                    <th>Terminées</th>
                                    <th>CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($dailyStats, 0, 7) as $stat): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($stat['date'])) ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $stat['transactions'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?= $stat['completed'] ?></span>
                                        </td>
                                        <td class="fw-bold text-success"><?= number_format($stat['revenue'], 2) ?>€</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune activité récente</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- KPIs avancés -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">KPIs Avancés</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="border-end">
                            <h4 class="text-primary"><?= number_format($analytics['avg_order_value'], 2) ?>€</h4>
                            <small class="text-muted">Panier Moyen</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border-end">
                            <h4 class="text-success">
                                <?= $analytics['total_transactions'] > 0 ? number_format($analytics['total_revenue'] / $analytics['total_transactions'], 2) : '0.00' ?>€
                            </h4>
                            <small class="text-muted">Revenus par Transaction</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border-end">
                            <h4 class="text-warning">
                                <?= count($dailyStats) > 0 ? number_format(array_sum(array_column($dailyStats, 'transactions')) / count($dailyStats), 1) : '0' ?>
                            </h4>
                            <small class="text-muted">Transactions/Jour</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="text-info">
                            <?= count($dailyStats) > 0 ? number_format(array_sum(array_column($dailyStats, 'revenue')) / count($dailyStats), 2) : '0.00' ?>€
                        </h4>
                        <small class="text-muted">CA Moyen/Jour</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setDateRange(days) {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - days);
    
    document.getElementById('start-date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end-date').value = endDate.toISOString().split('T')[0];
}

// Initialiser les graphiques avec les données PHP
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des ventes quotidiennes
    const dailyData = <?= json_encode($dailyStats) ?>;
    
    // Graphique des ventes quotidiennes
    const dailyCtx = document.getElementById('daily-sales-chart');
    if (dailyCtx && dailyData.length > 0) {
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => new Date(item.date).toLocaleDateString('fr-FR')),
                datasets: [{
                    label: 'Transactions',
                    data: dailyData.map(item => item.transactions),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Ventes terminées',
                    data: dailyData.map(item => item.completed),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
    
    // Graphique des canaux
    const channelData = <?= json_encode($channelStats) ?>;
    const channelCtx = document.getElementById('channel-chart');
    if (channelCtx && channelData.length > 0) {
        new Chart(channelCtx, {
            type: 'doughnut',
            data: {
                labels: channelData.map(item => {
                    const names = {
                        'whatsapp': 'WhatsApp',
                        'messenger': 'Messenger',
                        'email': 'Email',
                        'phone': 'Téléphone'
                    };
                    return names[item.contact_channel] || item.contact_channel;
                }),
                datasets: [{
                    data: channelData.map(item => item.total_contacts),
                    backgroundColor: [
                        '#25D366', // WhatsApp
                        '#1877F2', // Facebook
                        '#EA4335', // Email
                        '#FFC107'  // Phone
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>