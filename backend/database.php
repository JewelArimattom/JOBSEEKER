<?php
// --- DATABASE CONFIGURATION ---

/**
 * Load environment variables from .env at project root if present.
 */
function load_project_env_once() {
    static $loaded = false;
    if ($loaded) {
        return;
    }

    $loaded = true;
    $envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

    if (!is_readable($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '' || strpos($trimmed, '#') === 0) {
            continue;
        }

        $parts = explode('=', $trimmed, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $value = trim($parts[1]);

        if ($key === '') {
            continue;
        }

        if ((strpos($value, '"') === 0 && substr($value, -1) === '"') || (strpos($value, "'") === 0 && substr($value, -1) === "'")) {
            $value = substr($value, 1, -1);
        }

        if (getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

/**
 * Return env value with fallback.
 */
function env_or_default($key, $default = null) {
    $value = getenv($key);
    return ($value === false || $value === '') ? $default : $value;
}

load_project_env_once();

$appEnv = strtolower((string) env_or_default('APP_ENV', 'production'));
$appDebug = filter_var(env_or_default('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN);

// Keep legacy variable names for compatibility with existing endpoint scripts.
$servername = (string) env_or_default('DB_HOST', 'localhost');
$dbport = (int) env_or_default('DB_PORT', '3306');
$username = (string) env_or_default('DB_USERNAME', 'root');
$password = (string) env_or_default('DB_PASSWORD', '');
$dbname = (string) env_or_default('DB_DATABASE', 'jobfinder');

$appTimezone = (string) env_or_default('APP_TIMEZONE', 'Asia/Kolkata');
if ($appTimezone !== '') {
    date_default_timezone_set($appTimezone);
}

// Enable mysqli exception mode for consistent error handling.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname, $dbport);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');

    $message = 'Database connection failed. Please verify host configuration.';
    if ($appDebug || $appEnv === 'development') {
        $message .= ' Details: ' . $e->getMessage();
    }

    echo json_encode(['error' => $message]);
    exit();
}
?>
