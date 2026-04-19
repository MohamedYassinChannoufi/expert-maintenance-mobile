<?php
/**
 * Expert Maintenance - API Backend
 * Handles synchronization between mobile app and MySQL database
 *
 * Endpoints:
 * - authenticate: POST - Employee login
 * - full_sync: GET - Full synchronization
 * - sync_employees: GET - Sync employees
 * - sync_clients: GET - Sync clients
 * - sync_sites: GET - Sync sites
 * - sync_interventions: GET - Sync interventions
 * - sync_tasks: GET - Sync tasks
 * - sync_priorities: GET - Sync priorities
 * - sync_images: GET - Sync images
 * - get_intervention: GET - Get intervention details
 * - update_intervention: POST - Update intervention
 * - get_intervention_history: GET - Get history by site
 * - upload_image: POST - Upload image (Base64 or multipart)
 * - get_images: GET - Get images for intervention
 * - get_image_binary: GET - Get image binary data
 * - delete_image: GET - Delete image
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// CORS Headers - Allow mobile app access
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'gem');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connect to database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}

/**
 * Logger class for debugging
 */
class ApiLogger {
    private static $logFile = 'api.log';
    private static $enabled = false;

    public static function info($message) {
        if (!self::$enabled) return;
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [INFO] $message\n";
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }

    public static function error($message) {
        if (!self::$enabled) return;
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [ERROR] $message\n";
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }

    public static function debug($message) {
        if (!self::$enabled) return;
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [DEBUG] $message\n";
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }
}

// Get request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Log the request
ApiLogger::info("=== Request: $method - action=$action ===");

// Route requests
try {
    switch ($action) {
        case 'authenticate':
            authenticate($pdo);
            break;
        case 'full_sync':
            fullSync($pdo);
            break;
        case 'sync_employees':
            syncEmployees($pdo);
            break;
        case 'sync_clients':
            syncClients($pdo);
            break;
        case 'sync_sites':
            syncSites($pdo);
            break;
        case 'sync_interventions':
            syncInterventions($pdo);
            break;
        case 'sync_tasks':
            syncTasks($pdo);
            break;
        case 'sync_priorities':
            syncPriorities($pdo);
            break;
        case 'sync_images':
            syncImages($pdo);
            break;
        case 'get_intervention':
            getIntervention($pdo);
            break;
        case 'update_intervention':
            updateIntervention($pdo);
            break;
        case 'get_intervention_history':
            getInterventionHistory($pdo);
            break;
        case 'upload_image':
            uploadImage($pdo);
            break;
        case 'get_images':
            getImages($pdo);
            break;
        case 'get_image_binary':
            getImageBinary($pdo);
            break;
        case 'delete_image':
            deleteImage($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action. Available actions: authenticate, full_sync, sync_employees, sync_clients, sync_sites, sync_interventions, sync_tasks, sync_priorities, sync_images, get_intervention, update_intervention, get_intervention_history, upload_image, get_images, get_image_binary, delete_image'
            ]);
    }
} catch (Exception $e) {
    ApiLogger::error("Exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}

// ============================================================================
// AUTHENTICATION
// ============================================================================

/**
 * Authenticate employee
 * POST /api.php?action=authenticate
 * Body: {"login": "username", "password": "password"}
 */
function authenticate($pdo) {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }

    if (!isset($data['login']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Login and password required'
        ]);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM employes WHERE login = ? AND pwd = ? AND actif = 1");
        $stmt->execute([$data['login'], $data['password']]);
        $employee = $stmt->fetch();

        if ($employee) {
            ApiLogger::info("Authentication successful for: " . $data['login']);
            echo json_encode([
                'success' => true,
                'employee' => $employee,
                'token' => bin2hex(random_bytes(32))
            ]);
        } else {
            http_response_code(401);
            ApiLogger::error("Authentication failed for: " . $data['login']);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid credentials or inactive account'
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

// ============================================================================
// SYNCHRONIZATION
// ============================================================================

/**
 * Full synchronization - returns all data with valsync greater than provided value
 * GET /api.php?action=full_sync&last_sync=0&employee_id=1
 */
function fullSync($pdo) {
    try {
        ApiLogger::info("=== Début fullSync ===");
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $employeeId = isset($_GET['employee_id']) ? (int)$_GET['employee_id'] : 0;

        ApiLogger::debug("lastSync: $lastSync, employeeId: $employeeId");

        $data = [];

        // Get employees (parent table - no foreign keys)
        $stmt = $pdo->prepare("SELECT * FROM employes WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        $data['employees'] = $stmt->fetchAll();
        ApiLogger::debug("Employees: " . count($data['employees']));

        // Get clients (parent table - no foreign keys)
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        $data['clients'] = $stmt->fetchAll();
        ApiLogger::debug("Clients: " . count($data['clients']));

        // Get sites (references clients)
        $stmt = $pdo->prepare("SELECT * FROM sites WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        $data['sites'] = $stmt->fetchAll();
        ApiLogger::debug("Sites: " . count($data['sites']));

        // Get priorities (parent table - no foreign keys)
        $stmt = $pdo->prepare("SELECT * FROM priorites WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        $data['priorities'] = $stmt->fetchAll();
        ApiLogger::debug("Priorities: " . count($data['priorities']));

        // Get interventions for this employee (references sites and priorities)
        if ($employeeId > 0) {
            ApiLogger::debug("Recherche interventions pour employee_id=$employeeId");
            $stmt = $pdo->prepare("
                SELECT i.* FROM interventions i
                INNER JOIN employes_interventions ei ON i.id = ei.intervention_id
                WHERE ei.employe_id = ? AND i.valsync > ?
            ");
            $stmt->execute([$employeeId, $lastSync]);
            $data['interventions'] = $stmt->fetchAll();
            ApiLogger::debug("Interventions: " . count($data['interventions']));

            // Get tasks for these interventions (references interventions)
            $interventionIds = array_column($data['interventions'], 'id');
            if (!empty($interventionIds)) {
                $placeholders = implode(',', array_fill(0, count($interventionIds), '?'));
                $stmt = $pdo->prepare("SELECT * FROM taches WHERE intervention_id IN ($placeholders) AND valsync > ?");
                $params = array_merge($interventionIds, [$lastSync]);
                $stmt->execute($params);
                $data['tasks'] = $stmt->fetchAll();
                ApiLogger::debug("Tasks: " . count($data['tasks']));
            } else {
                $data['tasks'] = [];
                ApiLogger::debug("Aucune intervention, donc aucune tâche");
            }
        } else {
            $data['interventions'] = [];
            $data['tasks'] = [];
            ApiLogger::debug("employeeId=0, pas d'interventions");
        }

        // Get images (references interventions)
        $stmt = $pdo->prepare("SELECT id, nom, dateCapture, intervention_id, valsync FROM images WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        $data['images'] = $stmt->fetchAll();
        ApiLogger::debug("Images: " . count($data['images']));

        // Prepare response
        $response = [
            'success' => true,
            'data' => $data,
            'timestamp' => time()
        ];

        ApiLogger::info("JSON généré: " . strlen(json_encode($response)) . " caractères");
        echo json_encode($response);

    } catch (Exception $e) {
        ApiLogger::error("Exception dans fullSync: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Sync employees
 * GET /api.php?action=sync_employees&last_sync=0
 */
function syncEmployees($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $stmt = $pdo->prepare("SELECT * FROM employes WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        echo json_encode([
            'success' => true,
            'employees' => $stmt->fetchAll()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Sync clients
 * GET /api.php?action=sync_clients&last_sync=0
 */
function syncClients($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        echo json_encode([
            'success' => true,
            'clients' => $stmt->fetchAll()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Sync sites
 * GET /api.php?action=sync_sites&last_sync=0
 */
function syncSites($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $stmt = $pdo->prepare("SELECT * FROM sites WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        echo json_encode([
            'success' => true,
            'sites' => $stmt->fetchAll()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Sync interventions
 * GET /api.php?action=sync_interventions&last_sync=0&employee_id=1
 */
function syncInterventions($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $employeeId = isset($_GET['employee_id']) ? (int)$_GET['employee_id'] : 0;

        if ($employeeId > 0) {
            $stmt = $pdo->prepare("
                SELECT i.* FROM interventions i
                INNER JOIN employes_interventions ei ON i.id = ei.intervention_id
                WHERE ei.employe_id = ? AND i.valsync > ?
            ");
            $stmt->execute([$employeeId, $lastSync]);
            echo json_encode([
                'success' => true,
                'interventions' => $stmt->fetchAll()
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'interventions' => []
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Sync tasks
 * GET /api.php?action=sync_tasks&last_sync=0&intervention_id=1
 */
function syncTasks($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $interventionId = isset($_GET['intervention_id']) ? (int)$_GET['intervention_id'] : null;

        if ($interventionId) {
            $stmt = $pdo->prepare("SELECT * FROM taches WHERE intervention_id = ? AND valsync > ?");
            $stmt->execute([$interventionId, $lastSync]);
            echo json_encode([
                'success' => true,
                'tasks' => $stmt->fetchAll()
            ]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM taches WHERE valsync > ?");
            $stmt->execute([$lastSync]);
            echo json_encode([
                'success' => true,
                'tasks' => $stmt->fetchAll()
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Sync priorities
 * GET /api.php?action=sync_priorities&last_sync=0
 */
function syncPriorities($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $stmt = $pdo->prepare("SELECT * FROM priorites WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        echo json_encode([
            'success' => true,
            'priorities' => $stmt->fetchAll()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Sync images
 * GET /api.php?action=sync_images&last_sync=0
 */
function syncImages($pdo) {
    try {
        $lastSync = isset($_GET['last_sync']) ? (int)$_GET['last_sync'] : 0;
        $stmt = $pdo->prepare("SELECT id, nom, dateCapture, intervention_id, valsync FROM images WHERE valsync > ?");
        $stmt->execute([$lastSync]);
        echo json_encode([
            'success' => true,
            'images' => $stmt->fetchAll()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

// ============================================================================
// INTERVENTIONS
// ============================================================================

/**
 * Get intervention details
 * GET /api.php?action=get_intervention&id=1
 */
function getIntervention($pdo) {
    try {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid intervention ID'
            ]);
            return;
        }

        $stmt = $pdo->prepare("
            SELECT i.*,
                   p.nom as priorite_nom,
                   s.adresse as site_adresse,
                   s.rue,
                   s.codepostal,
                   s.ville,
                   s.longitude,
                   s.latitude,
                   s.contact as site_contact,
                   s.telcontact as site_telcontact,
                   c.nom as client_nom,
                   c.adresse as client_adresse,
                   c.tel as client_tel,
                   c.email as client_email,
                   c.contact as client_contact,
                   c.telcontact as client_telcontact
            FROM interventions i
            LEFT JOIN priorites p ON i.priorite_id = p.id
            LEFT JOIN sites s ON i.site_id = s.id
            LEFT JOIN clients c ON s.client_id = c.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        $intervention = $stmt->fetch();

        if ($intervention) {
            // Get tasks for this intervention
            $stmt = $pdo->prepare("SELECT * FROM taches WHERE intervention_id = ?");
            $stmt->execute([$id]);
            $intervention['taches'] = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'intervention' => $intervention
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Intervention not found'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Update intervention
 * POST /api.php?action=update_intervention
 * Body: {"id": 1, "titre": "...", "terminee": true, ...}
 */
function updateIntervention($pdo) {
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Intervention ID required'
            ]);
            return;
        }

        // Build dynamic update query based on provided fields
        $updateFields = [];
        $params = [];

        $allowedFields = [
            'titre', 'datedebut', 'datefin', 'heuredebutplan', 'heurefinplan',
            'commentaires', 'dateplanification', 'heuredebuteffect', 'heurefineffect',
            'terminee', 'dateterminaison', 'validee', 'datevalidation',
            'priorite_id', 'site_id'
        ];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateFields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        // Increment valsync
        $updateFields[] = "valsync = valsync + 1";

        if (empty($updateFields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'No fields to update'
            ]);
            return;
        }

        $params[] = $data['id'];

        $sql = "UPDATE interventions SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            ApiLogger::info("Intervention {$data['id']} mise à jour");
            echo json_encode([
                'success' => true,
                'message' => 'Intervention updated successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Intervention not found or no changes made'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Get intervention history for a site
 * GET /api.php?action=get_intervention_history&site_id=1&limit=50
 */
function getInterventionHistory($pdo) {
    try {
        $siteId = isset($_GET['site_id']) ? (int)$_GET['site_id'] : 0;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

        if ($siteId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid site ID'
            ]);
            return;
        }

        $stmt = $pdo->prepare("
            SELECT * FROM interventions
            WHERE site_id = ?
            ORDER BY datedebut DESC
            LIMIT ?
        ");
        $stmt->execute([$siteId, $limit]);

        echo json_encode([
            'success' => true,
            'history' => $stmt->fetchAll()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

// ============================================================================
// IMAGES
// ============================================================================

/**
 * Upload image - Supports both JSON (Base64) and multipart/form-data
 * POST /api.php?action=upload_image
 */
function uploadImage($pdo) {
    try {
        ApiLogger::info("=== Début uploadImage ===");

        // Check content type to determine upload method
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        ApiLogger::debug("Content-Type: $contentType");

        $interventionId = null;
        $imageName = 'IMG_' . time() . '.jpg';
        $dateCapture = date('Y-m-d');
        $imageData = null;

        if (strpos($contentType, 'application/json') !== false) {
            // JSON request with Base64 encoded image (from Android app)
            ApiLogger::debug("Traitement requête JSON/Base64");
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (!$data) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Invalid JSON data'
                ]);
                return;
            }

            if (!isset($data['intervention_id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'intervention_id required'
                ]);
                return;
            }

            $interventionId = (int)$data['intervention_id'];

            if (isset($data['nom'])) {
                $imageName = $data['nom'];
            }

            if (isset($data['dateCapture'])) {
                $dateCapture = $data['dateCapture'];
            }

            if (!isset($data['img'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Image data (img) required'
                ]);
                return;
            }

            // Decode Base64 image
            $imageData = base64_decode($data['img']);

            if ($imageData === false) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Invalid Base64 image data'
                ]);
                ApiLogger::error("Échec décodage Base64");
                return;
            }

            ApiLogger::info("Upload image (JSON): intervention_id=$interventionId, nom=$imageName, taille=" . strlen($imageData) . " bytes");

        } else {
            // Multipart/form-data request (traditional file upload)
            ApiLogger::debug("Traitement requête Multipart");

            if (!isset($_FILES['image']) && !isset($_FILES['file'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Image file required'
                ]);
                ApiLogger::error("Aucun fichier trouvé");
                return;
            }

            if (!isset($_POST['intervention_id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'intervention_id required'
                ]);
                return;
            }

            $interventionId = (int)$_POST['intervention_id'];
            $image = isset($_FILES['image']) ? $_FILES['image'] : $_FILES['file'];

            if (isset($_POST['nom'])) {
                $imageName = $_POST['nom'];
            } else {
                $imageName = $image['name'];
            }

            if (isset($_POST['dateCapture'])) {
                $dateCapture = $_POST['dateCapture'];
            }

            // Read image data from temp file
            $imageData = file_get_contents($image['tmp_name']);

            ApiLogger::info("Upload image (Multipart): intervention_id=$interventionId, nom=$imageName, taille=" . strlen($imageData) . " bytes");
        }

        // Validate image data
        if (empty($imageData)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Image data is empty'
            ]);
            ApiLogger::error("Image data est vide");
            return;
        }

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO images (nom, img, dateCapture, intervention_id, valsync)
            VALUES (?, ?, ?, ?, 1)
        ");
        $stmt->execute([$imageName, $imageData, $dateCapture, $interventionId]);

        $imageId = $pdo->lastInsertId();

        ApiLogger::info("✅ Image uploadée avec succès, id=$imageId");

        echo json_encode([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'image_id' => (int)$imageId
        ]);

    } catch (Exception $e) {
        ApiLogger::error("❌ Erreur upload image: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Get images for intervention (with Base64 encoded data)
 * GET /api.php?action=get_images&intervention_id=1
 */
function getImages($pdo) {
    try {
        $interventionId = isset($_GET['intervention_id']) ? (int)$_GET['intervention_id'] : 0;

        if ($interventionId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid intervention ID'
            ]);
            return;
        }

        $stmt = $pdo->prepare("SELECT id, nom, img, dateCapture FROM images WHERE intervention_id = ?");
        $stmt->execute([$interventionId]);
        $images = $stmt->fetchAll();

        // Encode images as Base64 for JSON transmission
        $imagesData = [];
        foreach ($images as $image) {
            $imageData = $image;
            if (!empty($image['img'])) {
                $imageData['img_base64'] = base64_encode($image['img']);
                unset($imageData['img']); // Remove binary data from response
            }
            $imagesData[] = $imageData;
        }

        echo json_encode([
            'success' => true,
            'images' => $imagesData
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Get image binary data
 * GET /api.php?action=get_image_binary&id=1
 */
function getImageBinary($pdo) {
    try {
        $imageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($imageId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid image ID'
            ]);
            return;
        }

        $stmt = $pdo->prepare("SELECT nom, img FROM images WHERE id = ?");
        $stmt->execute([$imageId]);
        $image = $stmt->fetch();

        if ($image && !empty($image['img'])) {
            // Return binary image data with appropriate headers
            header('Content-Type: image/jpeg');
            header('Content-Length: ' . strlen($image['img']));
            header('Content-Disposition: inline; filename="' . $image['nom'] . '"');
            echo $image['img'];
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Image not found'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Delete image
 * GET /api.php?action=delete_image&id=1
 */
function deleteImage($pdo) {
    try {
        $imageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($imageId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid image ID'
            ]);
            return;
        }

        $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
        $stmt->execute([$imageId]);

        if ($stmt->rowCount() > 0) {
            ApiLogger::info("Image $imageId supprimée");
            echo json_encode([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Image not found'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
