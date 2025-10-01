# Application E-commerce PHP MVC

Une application e-commerce complète développée en PHP avec architecture MVC, permettant aux clients de parcourir un catalogue, gérer un panier et contacter le vendeur via WhatsApp/Messenger.

## 🚀 Fonctionnalités

### Frontend Client
- **Catalogue produits** avec recherche et filtres avancés
- **Système de panier** persistant avec gestion des quantités
- **Intégration messagerie** (WhatsApp, Facebook Messenger)
- **Design responsive** avec animations modernes
- **Authentification** optionnelle pour les clients

### Backend Administrateur
- **Dashboard complet** avec KPIs et analytics
- **Gestion CRUD** des produits et catégories
- **Tracking des transactions** et suivi des ventes
- **Graphiques interactifs** (Chart.js)
- **Export de données** (CSV, PDF)
- **Upload d'images** pour les produits

## 📋 Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Extensions PHP : PDO, GD, JSON, Session

## 🛠️ Installation

1. **Cloner le projet**
```bash
git clone [url-du-repo]
cd ecommerce-mvc
```

2. **Configuration de la base de données**
```bash
# Créer la base de données MySQL
mysql -u root -p
CREATE DATABASE ecommerce_mvc;
exit

# Importer le schéma
mysql -u root -p ecommerce_mvc < database/schema.sql
```

3. **Configuration**
```php
// Modifier config/database.php
private $host = 'localhost';
private $username = 'votre_utilisateur';
private $password = 'votre_mot_de_passe';
private $database = 'ecommerce_mvc';

// Modifier les constantes dans config/database.php
define('BASE_URL', 'http://votre-domaine.com/');
define('WHATSAPP_NUMBER', '+33123456789');
define('FACEBOOK_PAGE', 'votre-page-facebook');
```

4. **Permissions**
```bash
# Créer le dossier uploads et définir les permissions
mkdir assets/uploads
chmod 755 assets/uploads
```

5. **Serveur web**
```apache
# Configuration Apache (.htaccess)
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

## 🏗️ Architecture

```
ecommerce-mvc/
├── config/
│   └── database.php          # Configuration BDD
├── core/
│   ├── Router.php           # Système de routage
│   ├── Controller.php       # Contrôleur de base
│   └── Model.php           # Modèle de base
├── controllers/
│   ├── HomeController.php   # Page d'accueil
│   ├── ProductController.php # Gestion produits
│   ├── CartController.php   # Gestion panier
│   ├── AuthController.php   # Authentification
│   ├── AdminController.php  # Administration
│   └── ApiController.php    # API AJAX
├── models/
│   ├── Product.php         # Modèle produit
│   ├── Category.php        # Modèle catégorie
│   ├── Transaction.php     # Modèle transaction
│   └── User.php           # Modèle utilisateur
├── views/
│   ├── layouts/           # Templates de base
│   ├── home/             # Vues accueil
│   ├── products/         # Vues produits
│   ├── cart/            # Vues panier
│   ├── auth/            # Vues authentification
│   ├── admin/           # Vues administration
│   └── errors/          # Pages d'erreur
├── assets/
│   ├── css/            # Styles CSS
│   ├── js/             # Scripts JavaScript
│   └── uploads/        # Images uploadées
├── database/
│   └── schema.sql      # Schéma de base de données
└── index.php          # Point d'entrée
```

## 🎯 Utilisation

### Comptes par défaut
- **Admin** : admin@boutique.com / password
- **Client** : client@test.com / password

### Workflow client
1. Parcourir le catalogue de produits
2. Ajouter des articles au panier
3. Finaliser et contacter via WhatsApp/Messenger
4. Le message contient automatiquement la liste des produits

### Workflow administrateur
1. Connexion au back-office (`/admin`)
2. Gestion des produits (CRUD complet)
3. Suivi des transactions et analytics
4. Mise à jour des statuts de vente

## 📊 Fonctionnalités Avancées

### Analytics & KPIs
- Taux de conversion
- Chiffre d'affaires par canal
- Évolution des ventes
- Produits les plus vendus
- Statistiques en temps réel

### Intégrations
- **WhatsApp Business API** pour messages automatiques
- **Facebook Messenger** pour contact direct
- **Chart.js** pour graphiques interactifs
- **Bootstrap 5** pour design responsive

### Sécurité
- Protection CSRF avec tokens
- Échappement XSS automatique
- Requêtes préparées (SQL Injection)
- Validation des données côté serveur
- Hachage sécurisé des mots de passe

## 🔧 Personnalisation

### Ajouter une nouvelle page
```php
// 1. Créer le contrôleur
class MonController extends Controller {
    public function index() {
        $this->view('ma-vue', ['data' => $data]);
    }
}

// 2. Ajouter la route dans index.php
$router->add('ma-route', 'MonController@index');

// 3. Créer la vue dans views/
```

### Modifier le design
```css
/* Personnaliser les couleurs dans assets/css/style.css */
:root {
    --primary-color: #votre-couleur;
    --secondary-color: #votre-couleur;
}
```

## 📱 Responsive Design

L'application est entièrement responsive avec :
- Design mobile-first
- Breakpoints Bootstrap 5
- Navigation adaptative
- Images optimisées
- Touch-friendly sur mobile

## 🚀 Déploiement

### Production
1. Configurer HTTPS obligatoire
2. Optimiser les images
3. Minifier CSS/JS
4. Configurer la mise en cache
5. Sauvegardes automatiques BDD

### Performance
- Mise en cache des requêtes
- Optimisation des images
- Compression GZIP
- CDN pour les assets statiques

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature
3. Commit les changements
4. Push vers la branche
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Support

Pour toute question ou problème :
- Email : contact@boutique.com
- Documentation : [lien-vers-docs]
- Issues GitHub : [lien-vers-issues]

## 🔄 Changelog

### v1.0.0
- Version initiale avec toutes les fonctionnalités
- Interface client complète
- Back-office administrateur
- Système de tracking des ventes
- Intégrations WhatsApp/Messenger

---

**Développé avec ❤️ en PHP MVC**