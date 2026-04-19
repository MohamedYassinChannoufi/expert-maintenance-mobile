<?php
/**
 * Expert Maintenance - Configuration File
 *
 * This file contains all configuration settings for the backend API.
 *
 * INSTRUCTIONS:
 * 1. Copy this file and rename it to 'config.php'
 * 2. Update the database credentials below
 * 3. Save the file in the same directory as api.php
 *
 * IMPORTANT: Never commit config.php to version control!
 * This file (config.example.php) is safe to commit.
 */

// ============================================================================
// DATABASE CONFIGURATION
// ============================================================================

/**
 * Database host
 * Usually 'localhost' for XAMPP
 */
define('DB_HOST', 'localhost');

/**
 * Database name
 * Must match the database you created in phpMyAdmin
 */
define('DB_NAME', 'gem');

/**
 * Database username
 * Default XAMPP username is 'root'
 */
define('DB_USER', 'root');

/**
 * Database password
 * Default XAMPP password is empty (no password)
 */
define('DB_PASS', '');

/**
 * Database charset
 * Use utf8mb4 for full Unicode support (including emojis)
 */
define('DB_CHARSET', 'utf8mb4');

// ============================================================================
// API CONFIGURATION
// ============================================================================

/**
 * API version
 * Useful for versioning your API
 */
define('API_VERSION', '1.0.0');

/**
 * Default timezone
 * Used for date/time operations
 * See: https://www.php.net/manual/en/timezones.php
 */
define('API_TIMEZONE', 'Europe/Paris');

/**
 * Enable/disable CORS
 * Set to false in production for better security
 */
define('API_ENABLE_CORS', true);

/**
 * CORS allowed origins
 * Use '*' for all origins (development only)
 * In production, specify exact origins: 'https://yourdomain.com'
 */
define('API_CORS_ORIGIN', '*');

// ============================================================================
// LOGGING CONFIGURATION
// ============================================================================

/**
 * Enable/disable logging
 * Set to false in production for better performance
 */
define('LOG_ENABLED', true);

/**
 * Log file path
 * Relative to the backend directory
 */
define('LOG_FILE', 'api.log');

/**
 * Log level
 * Options: 'debug', 'info', 'warning', 'error'
 */
define('LOG_LEVEL', 'debug');

/**
 * Log to console (browser)
 * Useful for development
 */
define('LOG_TO_CONSOLE', false);

// ============================================================================
// FILE UPLOAD CONFIGURATION
// ============================================================================

/**
 * Maximum file size for image uploads (in bytes)
 * Default: 5MB
 */
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);

/**
 * Allowed image mime types
 */
define('UPLOAD_ALLOWED_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'
]);

/**
 * Upload directory
 * Relative to the backend directory
 * Must be writable by the web server
 */
define('UPLOAD_DIR', 'uploads');

// ============================================================================
// SECURITY CONFIGURATION
// ============================================================================

/**
 * Enable/disable authentication token generation
 * Note: This is a simple token system for development only
 * For production, implement JWT or OAuth2
 */
define('AUTH_ENABLED', true);

/**
 * Token expiration time (in seconds)
 * Default: 24 hours
 */
define('AUTH_TOKEN_EXPIRY', 86400);

/**
 * Enable rate limiting
 * Prevents API abuse
 */
define('RATE_LIMIT_ENABLED', false);

/**
 * Maximum requests per minute
 */
define('RATE_LIMIT_MAX_REQUESTS', 60);

// ============================================================================
// SYNC CONFIGURATION
// ============================================================================

/**
 * Default synchronization limit
 * Maximum number of records to return per sync
 */
define('SYNC_DEFAULT_LIMIT', 1000);

/**
 * Include binary image data in sync
 * Set to false to only sync metadata (faster)
 */
define('SYNC_INCLUDE_IMAGES', true);

// ============================================================================
// ERROR HANDLING
// ============================================================================

/**
 * Display errors
 * Set to false in production
 */
define('DISPLAY_ERRORS', true);

/**
 * Log errors
 * Always keep this true
 */
define('LOG_ERRORS', true);

/**
 * Error log file
 */
define('ERROR_LOG_FILE', 'error.log');

// ============================================================================
// PERFORMANCE
// ============================================================================

/**
 * Enable output compression
 * Reduces response size
 */
define('ENABLE_COMPRESSION', true);

/**
 * Cache control headers
 * Set appropriate values for your use case
 */
define('CACHE_CONTROL', 'no-cache, no-store, must-revalidate');

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Get database connection
 *
 * @return PDO Database connection
 * @throws PDOException If connection fails
 */
function getDbConnection() {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

/**
 * Get API base URL
 *
 * @return string Base URL
 */
function getApiBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);

    return $protocol . '://' . $host . $path;
}

/**
 * Get client IP address
 *
 * @return string IP address
 */
function getClientIp() {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }
    return '0.0.0.0';
}

/**
 * Send JSON response
 *
 * @param mixed $data Data to send
 * @param int $statusCode HTTP status code
 * @return void
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');

    if (ENABLE_COMPRESSION) {
        ob_start('ob_gzhandler');
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Log message
 *
 * @param string $message Message to log
 * @param string $level Log level (debug, info, warning, error)
 * @return void
 */
function logMessage($message, $level = 'info') {
    if (!LOG_ENABLED) {
        return;
    }

    $logLevels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];

    if ($logLevels[$level] < $logLevels[LOG_LEVEL]) {
        return;
    }

    $timestamp = date('Y-m-d H:i:s');
    $ip = getClientIp();
    $logEntry = "[$timestamp] [$level] [$ip] $message\n";

    // Log to file
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);

    // Log to console (for development)
    if (LOG_TO_CONSOLE) {
        error_log($logEntry);
    }
}

// ============================================================================
// INITIALIZATION
// ============================================================================

// Set timezone
date_default_timezone_set(API_TIMEZONE);

// Set error display
ini_set('display_errors', DISPLAY_ERRORS ? '1' : '0');
ini_set('log_errors', LOG_ERRORS ? '1' : '0');
ini_set('error_log', ERROR_LOG_FILE);

// Set default headers
if (API_ENABLE_CORS) {
    header('Access-Control-Allow-Origin: ' . API_CORS_ORIGIN);
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400');
}

header('Cache-Control: ' . CACHE_CONTROL);
header('X-API-Version: ' . API_VERSION);
header('X-Content-Type-Options: nosniff');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}
