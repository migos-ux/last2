<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Charger les variables d'environnement depuis .env
        $this->loadEnv();

        $host = $_ENV['SUPABASE_DB_HOST'] ?? 'aws-0-eu-central-1.pooler.supabase.com';
        $port = $_ENV['SUPABASE_DB_PORT'] ?? '6543';
        $database = $_ENV['SUPABASE_DB_NAME'] ?? 'postgres';
        $username = $_ENV['SUPABASE_DB_USER'] ?? 'postgres.myoumigeiktoeiexdsqt';
        $password = $_ENV['SUPABASE_DB_PASSWORD'] ?? 'Abdelaali1234';

        try {
            $this->connection = new PDO(
                "pgsql:host={$host};port={$port};dbname={$database}",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    private function loadEnv() {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                if (!array_key_exists($name, $_ENV)) {
                    $_ENV[$name] = $value;
                }
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// Configuration globale
define('BASE_URL', 'http://localhost/ecommerce-mvc/'); 
define('UPLOAD_PATH', 'assets/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Paramètres de messagerie
define('WHATSAPP_NUMBER', '+33123456789');
define('FACEBOOK_PAGE', 'votre-page-facebook');
?>