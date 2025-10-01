<!-- Page Header -->
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>cart">Panier</a></li>
                    <li class="breadcrumb-item active">Contacter le vendeur</li>
                </ol>
            </nav>
            <h1 class="h2 mb-4">
                <i class="fas fa-comments me-2"></i>Contacter le vendeur
            </h1>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Commande préparée avec succès!</h5>
                        <p class="mb-0">Votre message de commande a été généré. Choisissez votre méthode de contact préférée ci-dessous.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Options -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Choisissez votre méthode de contact</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- WhatsApp -->
                        <div class="col-md-6">
                            <div class="contact-option text-center p-4 border rounded">
                                <i class="fab fa-whatsapp fa-4x text-success mb-3"></i>
                                <h5>WhatsApp</h5>
                                <p class="text-muted">Contactez-nous directement via WhatsApp avec votre commande pré-remplie.</p>
                                <a href="<?= $whatsappLink ?>" class="btn btn-success btn-lg" target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>Ouvrir WhatsApp
                                </a>
                            </div>
                        </div>

                        <!-- Messenger -->
                        <div class="col-md-6">
                            <div class="contact-option text-center p-4 border rounded">
                                <i class="fab fa-facebook-messenger fa-4x text-primary mb-3"></i>
                                <h5>Facebook Messenger</h5>
                                <p class="text-muted">Envoyez votre commande via Facebook Messenger pour un suivi facile.</p>
                                <a href="<?= $messengerLink ?>" class="btn btn-primary btn-lg" target="_blank">
                                    <i class="fab fa-facebook-messenger me-2"></i>Ouvrir Messenger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Preview -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Aperçu du message</h5>
                    <button class="btn btn-outline-secondary btn-sm" id="copy-message">
                        <i class="fas fa-copy me-1"></i>Copier
                    </button>
                </div>
                <div class="card-body">
                    <div class="message-preview bg-light p-3 rounded" id="message-content">
                        <?= nl2br(htmlspecialchars($message)) ?>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Ce message sera automatiquement copié dans votre application de messagerie.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Résumé de votre commande</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Numéro de transaction:</strong> #<?= $transactionId ?></p>
                            <p><strong>Date:</strong> <?= date('d/m/Y H:i') ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Total:</strong> <span class="text-primary h5"><?= number_format($total, 2) ?>€</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Prochaines étapes</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="step-icon mb-3">
                                <i class="fas fa-comments fa-3x text-primary"></i>
                            </div>
                            <h6>1. Contactez-nous</h6>
                            <p class="small text-muted">Utilisez WhatsApp ou Messenger pour nous envoyer votre commande.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="step-icon mb-3">
                                <i class="fas fa-handshake fa-3x text-success"></i>
                            </div>
                            <h6>2. Confirmation</h6>
                            <p class="small text-muted">Nous confirmerons votre commande et les détails de livraison.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="step-icon mb-3">
                                <i class="fas fa-truck fa-3x text-warning"></i>
                            </div>
                            <h6>3. Livraison</h6>
                            <p class="small text-muted">Recevez votre commande selon les modalités convenues.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Shopping -->
            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>products" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
                </a>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('copy-message').addEventListener('click', function() {
    const messageContent = document.getElementById('message-content').textContent;
    navigator.clipboard.writeText(messageContent).then(function() {
        const btn = document.getElementById('copy-message');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Copié!';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    });
});
</script>