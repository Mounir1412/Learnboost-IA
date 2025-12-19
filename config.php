<?php
define('SMTP_PASS', 'abcd efgh ijkl mnop');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('FROM_EMAIL', 'noreply@learnboost.com');
define('SMTP_USER', 'ammenezzi@gmail.com');

// If Composer autoload exists, include it so libraries such as PHPMailer are available via autoload
$vendorAutoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
} else {
    // If Composer autoload is not present, include small compat shims so calls don't fatal.
    $shim = __DIR__ . '/lib/compat/PHPMailer.php';
    if (file_exists($shim)) {
        require_once $shim;
    }
}

// Load .env-like file if present (simple KEY=VALUE pairs). Use a robust custom parser
function parse_dotenv($file)
{
    $env = [];
    if (!file_exists($file)) return $env;
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $lineNum => $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || $line[0] === ';') continue;
        // ignore malformed lines
        $pos = strpos($line, '=');
        if ($pos === false) continue;
        $key = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos + 1));
        // remove surrounding quotes if present
        if ((strlen($value) >= 2) && (($value[0] === '"' && $value[strlen($value)-1] === '"') || ($value[0] === "'" && $value[strlen($value)-1] === "'"))) {
            $value = substr($value, 1, -1);
        }
        $env[$key] = $value;
    }
    return $env;
}

if (file_exists(__DIR__ . '/.env')) {
    $env = parse_dotenv(__DIR__ . '/.env');
    if (is_array($env) && count($env) > 0) {
        // Export each entry to getenv()/putenv and define constants for backwards compatibility.
        foreach ($env as $k => $v) {
            $k = trim($k);
            $v = is_string($v) ? trim($v) : $v;
            if ($v === null || $v === '') continue;
            // set environment variable so getenv() works
            putenv("{$k}={$v}");
            // also populate $_ENV
            $_ENV[$k] = $v;
            // define a constant if not already defined
            if (!defined($k)) {
                define($k, $v);
            }
        }
    }
}

/**
 * Return AI credentials if available.
 * Priority: OPENAI_API_KEY then GROQ_API_KEY. Returns array or null.
 * Example return: ['provider' => 'openai', 'key' => 'sk-...']
 */
function get_ai_credentials()
{
    $openai = getenv('OPENAI_API_KEY');
    if ($openai === false || $openai === null || $openai === '') {
        if (defined('OPENAI_API_KEY')) {
            $openai = constant('OPENAI_API_KEY');
        } else {
            $openai = null;
        }
    }
    if (!empty($openai)) {
        return ['provider' => 'openai', 'key' => $openai];
    }

    $groq = getenv('GROQ_API_KEY');
    if ($groq === false || $groq === null || $groq === '') {
        if (defined('GROQ_API_KEY')) {
            $groq = constant('GROQ_API_KEY');
        } else {
            $groq = null;
        }
    }
    if (!empty($groq)) {
        return ['provider' => 'groq', 'key' => $groq];
    }

    return null;
}

class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            try {
                // Read DB settings from environment (.env file loaded above) with fallbacks
                $dbHost = getenv('DB_HOST') ?: (defined('DB_HOST') ? constant('DB_HOST') : '127.0.0.1');
                $dbPort = getenv('DB_PORT') ?: (defined('DB_PORT') ? constant('DB_PORT') : '3306');
                $dbName = getenv('DB_NAME') ?: (defined('DB_NAME') ? constant('DB_NAME') : 'z');
                $dbUser = getenv('DB_USER') ?: (defined('DB_USER') ? constant('DB_USER') : 'root');
                $dbPass = getenv('DB_PASS') ?: (defined('DB_PASS') ? constant('DB_PASS') : '');

                $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);

                self::$pdo = new PDO($dsn, $dbUser, $dbPass);

                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                // Give a helpful error message with next steps for the user
                $msg = "Database connection error: " . $e->getMessage();
                $msg .= "\nCheck that your DB server is running (XAMPP/MySQL) and that DB_HOST/DB_PORT/DB_USER/DB_PASS are correct.";
                die($msg);
            }
        }

        return self::$pdo;
    }
}
?>
