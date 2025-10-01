-- Base de données pour l'application e-commerce MVC
-- Créer la base de données
CREATE DATABASE IF NOT EXISTS ecommerce_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_mvc;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
);

-- Table des transactions (contacts/commandes)
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    real_amount DECIMAL(10,2) NULL, -- Montant réel payé (mis à jour manuellement)
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    contact_channel ENUM('whatsapp', 'messenger', 'email', 'phone') NOT NULL,
    customer_info JSON, -- Informations client (IP, user agent, etc.)
    notes TEXT, -- Notes administrateur
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_channel (contact_channel),
    INDEX idx_created_at (created_at)
);

-- Table des items de transaction
CREATE TABLE transaction_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL, -- Prix au moment de la transaction
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_transaction (transaction_id),
    INDEX idx_product (product_id)
);

-- Table des logs d'activité (optionnel pour tracking avancé)
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Insertion des données d'exemple

-- Utilisateur administrateur par défaut
INSERT INTO users (name, email, password, role) VALUES 
('Administrateur', 'admin@boutique.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Client Test', 'client@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');
-- Mot de passe par défaut: "password"

-- Catégories d'exemple
INSERT INTO categories (name, description) VALUES 
('Électronique', 'Appareils électroniques et gadgets'),
('Vêtements', 'Mode et accessoires vestimentaires'),
('Maison & Jardin', 'Articles pour la maison et le jardinage'),
('Sports & Loisirs', 'Équipements sportifs et de loisirs'),
('Livres & Médias', 'Livres, films, musique et médias'),
('Beauté & Santé', 'Produits de beauté et de santé');

-- Produits d'exemple
INSERT INTO products (name, description, price, category_id, featured) VALUES 
('Smartphone Galaxy Pro', 'Smartphone dernière génération avec écran OLED 6.5 pouces, 128GB de stockage, appareil photo 48MP et batterie longue durée.', 699.99, 1, TRUE),
('Casque Bluetooth Premium', 'Casque audio sans fil avec réduction de bruit active, autonomie 30h, son haute fidélité et microphone intégré.', 199.99, 1, TRUE),
('T-shirt Coton Bio', 'T-shirt 100% coton biologique, coupe moderne, disponible en plusieurs couleurs. Confortable et respectueux de l\'environnement.', 29.99, 2, FALSE),
('Jean Slim Fit', 'Jean coupe slim en denim stretch, taille haute, parfait pour un look décontracté ou habillé.', 79.99, 2, FALSE),
('Cafetière Expresso', 'Machine à café expresso automatique, 15 bars de pression, réservoir 1.5L, fonction vapeur pour cappuccino.', 299.99, 3, TRUE),
('Plante Monstera', 'Magnifique plante d\'intérieur Monstera Deliciosa, purificateur d\'air naturel, facile d\'entretien.', 39.99, 3, FALSE),
('Raquette Tennis Pro', 'Raquette de tennis professionnelle en graphite, poids 300g, cordage inclus, parfaite pour joueurs intermédiaires.', 149.99, 4, FALSE),
('Ballon Football', 'Ballon de football officiel taille 5, cuir synthétique, parfait pour l\'entraînement et les matchs.', 24.99, 4, FALSE),
('Roman Bestseller', 'Roman captivant de l\'auteur à succès, 400 pages d\'aventure et de suspense qui vous tiendront en haleine.', 19.99, 5, FALSE),
('Crème Hydratante Bio', 'Crème visage hydratante aux ingrédients naturels, convient à tous types de peau, sans parabènes.', 34.99, 6, TRUE);

-- Transactions d'exemple pour les statistiques
INSERT INTO transactions (user_id, total_amount, real_amount, status, contact_channel, customer_info) VALUES 
(2, 899.98, 899.98, 'completed', 'whatsapp', '{"ip": "192.168.1.1", "user_agent": "Mozilla/5.0"}'),
(2, 109.98, NULL, 'pending', 'messenger', '{"ip": "192.168.1.2", "user_agent": "Mozilla/5.0"}'),
(NULL, 299.99, 299.99, 'completed', 'whatsapp', '{"ip": "192.168.1.3", "user_agent": "Mozilla/5.0"}'),
(NULL, 54.98, NULL, 'cancelled', 'whatsapp', '{"ip": "192.168.1.4", "user_agent": "Mozilla/5.0"}'),
(2, 174.98, 174.98, 'completed', 'messenger', '{"ip": "192.168.1.5", "user_agent": "Mozilla/5.0"}');

-- Items de transaction d'exemple
INSERT INTO transaction_items (transaction_id, product_id, quantity, price) VALUES 
-- Transaction 1
(1, 1, 1, 699.99),
(1, 2, 1, 199.99),
-- Transaction 2
(2, 3, 2, 29.99),
(2, 4, 1, 79.99),
-- Transaction 3
(3, 5, 1, 299.99),
-- Transaction 4
(4, 6, 1, 39.99),
(4, 9, 1, 19.99),
-- Transaction 5
(5, 7, 1, 149.99),
(5, 8, 1, 24.99);

-- Vues pour les statistiques (optionnel)
CREATE VIEW v_transaction_stats AS
SELECT 
    DATE(created_at) as date,
    COUNT(*) as total_transactions,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_transactions,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_transactions,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_transactions,
    SUM(total_amount) as total_amount,
    SUM(CASE WHEN status = 'completed' THEN COALESCE(real_amount, total_amount) ELSE 0 END) as completed_revenue
FROM transactions 
GROUP BY DATE(created_at)
ORDER BY date DESC;

CREATE VIEW v_product_stats AS
SELECT 
    p.id,
    p.name,
    p.price,
    c.name as category_name,
    COALESCE(SUM(ti.quantity), 0) as total_sold,
    COALESCE(SUM(ti.quantity * ti.price), 0) as total_revenue,
    COUNT(DISTINCT t.id) as total_orders
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
LEFT JOIN transaction_items ti ON p.id = ti.product_id
LEFT JOIN transactions t ON ti.transaction_id = t.id AND t.status = 'completed'
WHERE p.status = 'active'
GROUP BY p.id, p.name, p.price, c.name
ORDER BY total_sold DESC;