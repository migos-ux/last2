/*
  # Création de la base de données e-commerce complète

  1. Nouvelles Tables
    - `users` - Gestion des utilisateurs (clients et admins)
      - `id` (uuid, primary key)
      - `name` (text)
      - `email` (text, unique)
      - `password` (text)
      - `role` (text) - 'customer' ou 'admin'
      - `created_at` (timestamptz)
      - `updated_at` (timestamptz)
      - `last_login` (timestamptz)
    
    - `categories` - Catégories de produits
      - `id` (bigint, primary key)
      - `name` (text)
      - `description` (text)
      - `status` (text) - 'active' ou 'inactive'
      - `created_at` (timestamptz)
      - `updated_at` (timestamptz)
    
    - `products` - Produits du catalogue
      - `id` (bigint, primary key)
      - `name` (text)
      - `description` (text)
      - `price` (decimal)
      - `image` (text)
      - `category_id` (bigint)
      - `country` (text) - Pays de publication du produit
      - `featured` (boolean)
      - `status` (text) - 'active' ou 'inactive'
      - `created_at` (timestamptz)
      - `updated_at` (timestamptz)
    
    - `transactions` - Commandes/Contacts clients
      - `id` (bigint, primary key)
      - `user_id` (uuid)
      - `total_amount` (decimal)
      - `real_amount` (decimal)
      - `status` (text) - 'pending', 'completed', 'cancelled'
      - `contact_channel` (text) - 'whatsapp', 'messenger', 'email', 'phone'
      - `customer_info` (jsonb)
      - `notes` (text)
      - `created_at` (timestamptz)
      - `updated_at` (timestamptz)
    
    - `transaction_items` - Items des transactions
      - `id` (bigint, primary key)
      - `transaction_id` (bigint)
      - `product_id` (bigint)
      - `quantity` (integer)
      - `price` (decimal)
      - `created_at` (timestamptz)
    
    - `activity_logs` - Logs d'activité
      - `id` (bigint, primary key)
      - `user_id` (uuid)
      - `action` (text)
      - `table_name` (text)
      - `record_id` (bigint)
      - `old_values` (jsonb)
      - `new_values` (jsonb)
      - `ip_address` (text)
      - `user_agent` (text)
      - `created_at` (timestamptz)

  2. Sécurité
    - RLS activé sur toutes les tables
    - Policies pour admin et customer selon les besoins
    - Authentification via auth.users
*/

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text UNIQUE NOT NULL,
  password text NOT NULL,
  role text DEFAULT 'customer' CHECK (role IN ('customer', 'admin')),
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now(),
  last_login timestamptz
);

ALTER TABLE users ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read own data"
  ON users FOR SELECT
  TO authenticated
  USING (auth.uid() = id OR EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

CREATE POLICY "Admins can manage all users"
  ON users FOR ALL
  TO authenticated
  USING (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'))
  WITH CHECK (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
  id bigserial PRIMARY KEY,
  name text NOT NULL,
  description text,
  status text DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now()
);

ALTER TABLE categories ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can read active categories"
  ON categories FOR SELECT
  TO public
  USING (status = 'active');

CREATE POLICY "Admins can manage categories"
  ON categories FOR ALL
  TO authenticated
  USING (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'))
  WITH CHECK (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

-- Table des produits avec champ pays
CREATE TABLE IF NOT EXISTS products (
  id bigserial PRIMARY KEY,
  name text NOT NULL,
  description text,
  price decimal(10,2) NOT NULL,
  image text,
  category_id bigint REFERENCES categories(id) ON DELETE SET NULL,
  country text DEFAULT 'FR',
  featured boolean DEFAULT false,
  status text DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id);
CREATE INDEX IF NOT EXISTS idx_products_status ON products(status);
CREATE INDEX IF NOT EXISTS idx_products_featured ON products(featured);
CREATE INDEX IF NOT EXISTS idx_products_country ON products(country);

ALTER TABLE products ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Anyone can read active products"
  ON products FOR SELECT
  TO public
  USING (status = 'active');

CREATE POLICY "Admins can manage products"
  ON products FOR ALL
  TO authenticated
  USING (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'))
  WITH CHECK (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

-- Table des transactions
CREATE TABLE IF NOT EXISTS transactions (
  id bigserial PRIMARY KEY,
  user_id uuid REFERENCES users(id) ON DELETE SET NULL,
  total_amount decimal(10,2) NOT NULL,
  real_amount decimal(10,2),
  status text DEFAULT 'pending' CHECK (status IN ('pending', 'completed', 'cancelled')),
  contact_channel text NOT NULL CHECK (contact_channel IN ('whatsapp', 'messenger', 'email', 'phone')),
  customer_info jsonb,
  notes text,
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_transactions_status ON transactions(status);
CREATE INDEX IF NOT EXISTS idx_transactions_channel ON transactions(contact_channel);
CREATE INDEX IF NOT EXISTS idx_transactions_created_at ON transactions(created_at);

ALTER TABLE transactions ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read own transactions"
  ON transactions FOR SELECT
  TO authenticated
  USING (auth.uid() = user_id OR EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

CREATE POLICY "Admins can manage all transactions"
  ON transactions FOR ALL
  TO authenticated
  USING (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'))
  WITH CHECK (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

-- Table des items de transaction
CREATE TABLE IF NOT EXISTS transaction_items (
  id bigserial PRIMARY KEY,
  transaction_id bigint NOT NULL REFERENCES transactions(id) ON DELETE CASCADE,
  product_id bigint NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  quantity integer NOT NULL DEFAULT 1,
  price decimal(10,2) NOT NULL,
  created_at timestamptz DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_transaction_items_transaction ON transaction_items(transaction_id);
CREATE INDEX IF NOT EXISTS idx_transaction_items_product ON transaction_items(product_id);

ALTER TABLE transaction_items ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read own transaction items"
  ON transaction_items FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM transactions t 
      WHERE t.id = transaction_items.transaction_id 
      AND (t.user_id = auth.uid() OR EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'))
    )
  );

CREATE POLICY "Admins can manage transaction items"
  ON transaction_items FOR ALL
  TO authenticated
  USING (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'))
  WITH CHECK (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

-- Table des logs d'activité
CREATE TABLE IF NOT EXISTS activity_logs (
  id bigserial PRIMARY KEY,
  user_id uuid REFERENCES users(id) ON DELETE SET NULL,
  action text NOT NULL,
  table_name text,
  record_id bigint,
  old_values jsonb,
  new_values jsonb,
  ip_address text,
  user_agent text,
  created_at timestamptz DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_activity_logs_action ON activity_logs(action);
CREATE INDEX IF NOT EXISTS idx_activity_logs_created_at ON activity_logs(created_at);

ALTER TABLE activity_logs ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Admins can read activity logs"
  ON activity_logs FOR SELECT
  TO authenticated
  USING (EXISTS (SELECT 1 FROM users WHERE id = auth.uid() AND role = 'admin'));

-- Insertion des données d'exemple
INSERT INTO users (name, email, password, role) VALUES 
('Administrateur', 'admin@boutique.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Client Test', 'client@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer')
ON CONFLICT (email) DO NOTHING;

INSERT INTO categories (name, description) VALUES 
('Électronique', 'Appareils électroniques et gadgets'),
('Vêtements', 'Mode et accessoires vestimentaires'),
('Maison & Jardin', 'Articles pour la maison et le jardinage'),
('Sports & Loisirs', 'Équipements sportifs et de loisirs'),
('Livres & Médias', 'Livres, films, musique et médias'),
('Beauté & Santé', 'Produits de beauté et de santé')
ON CONFLICT DO NOTHING;

INSERT INTO products (name, description, price, category_id, country, featured) VALUES 
('Smartphone Galaxy Pro', 'Smartphone dernière génération avec écran OLED 6.5 pouces, 128GB de stockage, appareil photo 48MP et batterie longue durée.', 699.99, 1, 'FR', TRUE),
('Casque Bluetooth Premium', 'Casque audio sans fil avec réduction de bruit active, autonomie 30h, son haute fidélité et microphone intégré.', 199.99, 1, 'FR', TRUE),
('T-shirt Coton Bio', 'T-shirt 100% coton biologique, coupe moderne, disponible en plusieurs couleurs. Confortable et respectueux de l''environnement.', 29.99, 2, 'FR', FALSE),
('Jean Slim Fit', 'Jean coupe slim en denim stretch, taille haute, parfait pour un look décontracté ou habillé.', 79.99, 2, 'BE', FALSE),
('Cafetière Expresso', 'Machine à café expresso automatique, 15 bars de pression, réservoir 1.5L, fonction vapeur pour cappuccino.', 299.99, 3, 'FR', TRUE),
('Plante Monstera', 'Magnifique plante d''intérieur Monstera Deliciosa, purificateur d''air naturel, facile d''entretien.', 39.99, 3, 'BE', FALSE),
('Raquette Tennis Pro', 'Raquette de tennis professionnelle en graphite, poids 300g, cordage inclus, parfaite pour joueurs intermédiaires.', 149.99, 4, 'CH', FALSE),
('Ballon Football', 'Ballon de football officiel taille 5, cuir synthétique, parfait pour l''entraînement et les matchs.', 24.99, 4, 'FR', FALSE),
('Roman Bestseller', 'Roman captivant de l''auteur à succès, 400 pages d''aventure et de suspense qui vous tiendront en haleine.', 19.99, 5, 'CA', FALSE),
('Crème Hydratante Bio', 'Crème visage hydratante aux ingrédients naturels, convient à tous types de peau, sans parabènes.', 34.99, 6, 'FR', TRUE)
ON CONFLICT DO NOTHING;
