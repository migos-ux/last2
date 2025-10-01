<!-- En-tête de page -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des Transactions</h1>
        <p class="text-muted">Suivez et gérez toutes les transactions de votre boutique</p>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary export-btn" data-format="csv" data-type="transactions">
            <i class="fas fa-file-csv me-2"></i>Exporter CSV
        </button>
        <button type="button" class="btn btn-outline-danger export-btn" data-format="pdf" data-type="transactions">
            <i class="fas fa-file-pdf me-2"></i>Exporter PDF
        </button>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-number text-primary"><?= count($transactions) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Terminées</div>
                        <div class="stat-number text-success">
                            <?= count(array_filter($transactions, function($t) { return $t['status'] === 'completed'; })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">En Attente</div>
                        <div class="stat-number text-warning">
                            <?= count(array_filter($transactions, function($t) { return $t['status'] === 'pending'; })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card danger h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Annulées</div>
                        <div class="stat-number text-danger">
                            <?= count(array_filter($transactions, function($t) { return $t['status'] === 'cancelled'; })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select class="form-select" name="status">
                    <option value="">Tous les statuts</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>En attente</option>
                    <option value="completed" <?= ($_GET['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Terminé</option>
                    <option value="cancelled" <?= ($_GET['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Canal</label>
                <select class="form-select" name="channel">
                    <option value="">Tous les canaux</option>
                    <option value="whatsapp" <?= ($_GET['channel'] ?? '') == 'whatsapp' ? 'selected' : '' ?>>WhatsApp</option>
                    <option value="messenger" <?= ($_GET['channel'] ?? '') == 'messenger' ? 'selected' : '' ?>>Messenger</option>
                    <option value="email" <?= ($_GET['channel'] ?? '') == 'email' ? 'selected' : '' ?>>Email</option>
                    <option value="phone" <?= ($_GET['channel'] ?? '') == 'phone' ? 'selected' : '' ?>>Téléphone</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date début</label>
                <input type="date" class="form-control" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date fin</label>
                <input type="date" class="form-control" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des transactions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Liste des Transactions</h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($transactions)): ?>
            <div class="table-responsive">
                <table class="table table-hover admin-table mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Canal</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td>
                                    <strong>#<?= $transaction['id'] ?></strong>
                                </td>
                                <td>
                                    <?php if ($transaction['customer_email']): ?>
                                        <div class="fw-bold"><?= htmlspecialchars($transaction['customer_email']) ?></div>
                                        <small class="text-muted">Client enregistré</small>
                                    <?php else: ?>
                                        <div class="text-muted">
                                            <i class="fas fa-user-secret me-1"></i>Client invité
                                        </div>
                                        <?php
                                        $customerInfo = json_decode($transaction['customer_info'], true);
                                        if ($customerInfo && isset($customerInfo['ip'])):
                                        ?>
                                            <small class="text-muted">IP: <?= htmlspecialchars($customerInfo['ip']) ?></small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary"><?= number_format($transaction['total_amount'], 2) ?>€</div>
                                    <?php if ($transaction['real_amount'] && $transaction['real_amount'] != $transaction['total_amount']): ?>
                                        <small class="text-success">Réel: <?= number_format($transaction['real_amount'], 2) ?>€</small>
                                    <?php endif; ?>
                                </td>
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
                                    <i class="<?= $icon ?> me-2"></i>
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
                                <td>
                                    <div><?= date('d/m/Y', strtotime($transaction['created_at'])) ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($transaction['created_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-info btn-sm" 
                                                data-bs-toggle="modal" data-bs-target="#detailModal<?= $transaction['id'] ?>"
                                                title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm update-status-btn"
                                                data-transaction-id="<?= $transaction['id'] ?>"
                                                data-current-status="<?= $transaction['status'] ?>"
                                                title="Modifier statut">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal détails transaction -->
                            <div class="modal fade" id="detailModal<?= $transaction['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Détails Transaction #<?= $transaction['id'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Informations générales</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td><strong>ID:</strong></td>
                                                            <td>#<?= $transaction['id'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Date:</strong></td>
                                                            <td><?= date('d/m/Y H:i:s', strtotime($transaction['created_at'])) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Statut:</strong></td>
                                                            <td>
                                                                <span class="status-badge <?= $statusClasses[$transaction['status']] ?>">
                                                                    <?= $statusLabels[$transaction['status']] ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Canal:</strong></td>
                                                            <td>
                                                                <i class="<?= $icon ?> me-1"></i>
                                                                <?= ucfirst($transaction['contact_channel']) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Montant:</strong></td>
                                                            <td class="fw-bold text-primary"><?= number_format($transaction['total_amount'], 2) ?>€</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Informations client</h6>
                                                    <?php if ($transaction['customer_email']): ?>
                                                        <p><strong>Email:</strong> <?= htmlspecialchars($transaction['customer_email']) ?></p>
                                                    <?php else: ?>
                                                        <p class="text-muted">Client invité</p>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($customerInfo): ?>
                                                        <p><strong>IP:</strong> <?= htmlspecialchars($customerInfo['ip'] ?? 'N/A') ?></p>
                                                        <p><strong>Navigateur:</strong> 
                                                           <small><?= htmlspecialchars(substr($customerInfo['user_agent'] ?? 'N/A', 0, 50)) ?>...</small>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <?php if ($transaction['notes']): ?>
                                                <hr>
                                                <h6>Notes</h6>
                                                <p><?= nl2br(htmlspecialchars($transaction['notes'])) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="button" class="btn btn-primary update-status-btn"
                                                    data-transaction-id="<?= $transaction['id'] ?>"
                                                    data-current-status="<?= $transaction['status'] ?>"
                                                    data-bs-dismiss="modal">
                                                Modifier le statut
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="card-footer">
                    <nav aria-label="Navigation des transactions">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4>Aucune transaction trouvée</h4>
                <p class="text-muted">Les transactions apparaîtront ici lorsque les clients contactent via le panier.</p>
            </div>
        <?php endif; ?>
    </div>
</div>