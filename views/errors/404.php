<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
            text-align: center;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }
        .error-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 2rem;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        .btn-home {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            display: inline-block;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
            color: white;
        }
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="error-container">
                    <div class="error-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    
                    <div class="error-code">404</div>
                    
                    <h2 class="mb-3">Page non trouvée</h2>
                    
                    <p class="text-muted mb-4">
                        Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
                        Elle a peut-être été supprimée, renommée ou est temporairement indisponible.
                    </p>
                    
                    <div class="mb-4">
                        <a href="<?= BASE_URL ?>" class="btn-home me-3">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        <a href="<?= BASE_URL ?>products" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Voir nos produits
                        </a>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-home fa-2x text-primary mb-2"></i>
                            <h6>Accueil</h6>
                            <small class="text-muted">Retournez à la page principale</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-th fa-2x text-success mb-2"></i>
                            <h6>Catalogue</h6>
                            <small class="text-muted">Parcourez nos produits</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-search fa-2x text-info mb-2"></i>
                            <h6>Recherche</h6>
                            <small class="text-muted">Trouvez ce que vous cherchez</small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            Si le problème persiste, 
                            <a href="mailto:contact@boutique.com" class="text-decoration-none">contactez-nous</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>