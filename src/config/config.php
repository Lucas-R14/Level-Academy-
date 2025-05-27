<?php
// Initialize session first
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 3600); // 1 hour in seconds
    session_set_cookie_params(3600);
    session_start();
}

// Load environment variables with fallback to default values
$envPath = __DIR__ . '/../.Env';
if (!file_exists($envPath)) {
    error_log("Warning: Could not find .Env file at $envPath");
    $env = [
        'DB_HOST' => 'localhost',
        'DB_USER' => 'root',
        'DB_PASS' => 'mysql',
        'DB_NAME' => 'level_academy'
    ];
} else {
    $env = parse_ini_file($envPath);
    if ($env === false) {
        error_log("Warning: Could not parse .Env file at $envPath");
        $env = [
            'DB_HOST' => 'localhost',
            'DB_USER' => 'root',
            'DB_PASS' => 'mysql',
            'DB_NAME' => 'level_academy'
        ];
    }
}

// Debug logging
error_log("Environment variables loaded:");
foreach ($env as $key => $value) {
    error_log("$key: $value");
}

// Database configuration
if (!defined('DB_HOST')) define('DB_HOST', $env['DB_HOST']);
if (!defined('DB_USER')) define('DB_USER', $env['DB_USER']);
if (!defined('DB_PASS')) define('DB_PASS', $env['DB_PASS']);
if (!defined('DB_NAME')) define('DB_NAME', $env['DB_NAME']);

// Base URL configuration
if (!defined('BASE_URL')) {
    // Try to detect the base URL automatically
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script_name);
    define('BASE_URL', $protocol . '://' . $host . $path . '/');
}

// Application configuration
if (!defined('APP_NAME')) define('APP_NAME', 'Level Academy');
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');

// Session lifetime configuration (moved after session_start)
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Criar ligação à base de dados
function getDatabaseConnection() {
    try {
        // Primeiro criar a base de dados se não existir
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
            DB_USER, 
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $pdo;
    } catch(PDOException $e) {
        die("Falha na configuração da base de dados: " . $e->getMessage());
    }
}

// Função de execução segura de consultas
function executeQuery($query, $params = []) {
    try {
        $pdo = getPDO();
        
        // Sanitizar parâmetros de entrada
        $sanitized_params = array_map(function($param) {
            if (is_string($param)) {
                // Remover caracteres perigosos
                $param = preg_replace('/[\x00-\x1F\x7F\x80-\x9F]/', '', $param);
                // Remover padrões de injeção SQL
                $param = preg_replace('/--|;|#|\/\*/', '', $param);
            }
            return $param;
        }, $params);
        
        // Preparar e executar a consulta
        $stmt = $pdo->prepare($query);
        $stmt->execute($sanitized_params);
        
        // Retornar resultado com base no tipo de consulta
        if (preg_match('/^(?:INSERT|UPDATE|DELETE)/i', $query)) {
            return [
                'success' => true,
                'affected_rows' => $stmt->rowCount(),
                'last_insert_id' => $pdo->lastInsertId()
            ];
        } else {
            return [
                'success' => true,
                'results' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        }
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => 'Erro de base de dados: ' . $e->getMessage()
        ];
    }
}

// Get PDO connection (singleton pattern)
function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = getDatabaseConnection();
            // Test the connection
            $pdo->query('SELECT 1');
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new Exception('Could not connect to the database. Please try again later.');
        }
    }
    return $pdo;
}

// Obter dados do utilizador atual
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        $result = executeQuery(
            "SELECT * FROM users WHERE id = ?",
            [$_SESSION['user_id']]
        );
        if ($result['success'] && !empty($result['results'])) {
            return $result['results'][0];
        }
    }
    return null;
}

// Verificar se o utilizador está autenticado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Verificar se o utilizador é administrador
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

// Função de redirecionamento
function redirect($path) {
    $fullPath = preg_match('/^https?:\/\//', $path) ? $path : BASE_URL . ltrim($path, '/');
    header("Location: $fullPath");
    exit();
}

// Tratamento de erros
function handleError($error) {
    echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
}

// Mensagem de sucesso
function showSuccess($message) {
    echo "<div class='success'>" . htmlspecialchars($message) . "</div>";
}
?>