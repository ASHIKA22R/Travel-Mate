<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Turn off automatic mysqli exception throwing to catch connection errors gracefully
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
$database = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'travel_website';
$port = (int)(getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306);

// Parse DATABASE_URL / MYSQL_URL if set (e.g. mysql://user:pass@host:port/dbname)
$dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');
if ($dbUrl) {
    $parsed = parse_url($dbUrl);
    if ($parsed) {
        $host = $parsed['host'] ?? $host;
        $user = $parsed['user'] ?? $user;
        $password = $parsed['pass'] ?? $password;
        $port = isset($parsed['port']) ? (int)$parsed['port'] : $port;
        if (!empty($parsed['path'])) {
            $database = ltrim($parsed['path'], '/');
        }
    }
}

$conn = null;
$useMysql = false;

// Attempt MySQL connection
try {
    $conn = @new mysqli($host, $user, $password, $database, $port);
    if ($conn && !$conn->connect_error) {
        $useMysql = true;
    }
} catch (Throwable $e) {
    $conn = null;
}

// If MySQL is not available, transparently use SQLite for zero-config Render deployment!
if (!$useMysql) {
    class SQLiteResultAdapter {
        public int $num_rows;
        private array $rows;
        private int $pointer = 0;

        public function __construct(array $rows) {
            $this->rows = array_values($rows);
            $this->num_rows = count($rows);
        }

        public function fetch_assoc(): ?array {
            if ($this->pointer < $this->num_rows) {
                return $this->rows[$this->pointer++];
            }
            return null;
        }
    }

    class SQLiteStmtAdapter {
        private PDO $pdo;
        private string $sql;
        private array $params = [];
        public ?int $insert_id = null;
        private ?array $executedRows = null;

        public function __construct(PDO $pdo, string $sql) {
            $this->pdo = $pdo;
            $this->sql = $sql;
        }

        public function bind_param(string $types, &...$vars): bool {
            $this->params = $vars;
            return true;
        }

        public function execute(): bool {
            $stmt = $this->pdo->prepare($this->sql);
            $res = $stmt->execute($this->params);
            if (stristr($this->sql, 'INSERT')) {
                $this->insert_id = (int)$this->pdo->lastInsertId();
            } else {
                $this->executedRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return $res;
        }

        public function get_result(): SQLiteResultAdapter {
            return new SQLiteResultAdapter($this->executedRows ?? []);
        }
    }

    class SQLiteDbAdapter {
        public ?string $connect_error = null;
        private PDO $pdo;

        public function __construct(string $filepath) {
            $this->pdo = new PDO('sqlite:' . $filepath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        public function set_charset(string $charset): bool {
            return true;
        }

        public function query(string $sql) {
            if (preg_match('/^\s*(CREATE|INSERT|UPDATE|DELETE|DROP|ALTER)/i', $sql)) {
                $sqliteSql = str_replace(
                    ['INT AUTO_INCREMENT PRIMARY KEY', 'VARCHAR(100)', 'VARCHAR(150)', 'VARCHAR(20)', 'VARCHAR(255)', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP', 'DECIMAL(10,2)'],
                    ['INTEGER PRIMARY KEY AUTOINCREMENT', 'TEXT', 'TEXT', 'TEXT', 'TEXT', 'DATETIME DEFAULT CURRENT_TIMESTAMP', 'NUMERIC'],
                    $sql
                );
                $this->pdo->exec($sqliteSql);
                return true;
            }

            $stmt = $this->pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return new SQLiteResultAdapter($rows);
        }

        public function prepare(string $sql): SQLiteStmtAdapter {
            return new SQLiteStmtAdapter($this->pdo, $sql);
        }
    }

    $sqlitePath = is_writable(__DIR__) ? __DIR__ . '/travel_website.sqlite' : sys_get_temp_dir() . '/travel_website.sqlite';
    $conn = new SQLiteDbAdapter($sqlitePath);
}

if ($conn && method_exists($conn, 'set_charset')) {
    $conn->set_charset("utf8mb4");
}

// Auto-initialize schema & seed data if tables are missing
$checkTable = $conn->query($useMysql ? "SHOW TABLES LIKE 'destinations'" : "SELECT name FROM sqlite_master WHERE type='table' AND name='destinations'");
if ($checkTable && $checkTable->num_rows === 0) {
    $conn->query("CREATE TABLE IF NOT EXISTS destinations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        country VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        days INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        best_time VARCHAR(100) NOT NULL,
        image VARCHAR(255) NOT NULL
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        destination VARCHAR(100) NOT NULL,
        travel_date DATE NOT NULL,
        persons INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        subject VARCHAR(150) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed initial destinations
    $conn->query("INSERT INTO destinations (name,country,description,days,price,best_time,image) VALUES
    ('Madurai','India','Visit the Meenakshi Amman Temple, local markets and important heritage places in Madurai.',3,6500,'October to March','assets/india.svg'),
    ('Paris','France','Explore the Eiffel Tower area, museums, city streets and famous landmarks of Paris.',5,45000,'April to June','assets/paris.svg'),
    ('Bali','Indonesia','Enjoy beaches, temples, local culture and scenic places around Bali.',5,38000,'April to October','assets/bali.svg'),
    ('Dubai','UAE','Experience modern city attractions, desert activities and shopping areas.',4,42000,'November to March','assets/dubai.svg'),
    ('Singapore','Singapore','Explore Marina Bay, Gardens by the Bay and other city attractions.',4,48000,'February to April','assets/singapore.svg'),
    ('Seoul','South Korea','Explore palaces, markets, city attractions and Korean cultural areas.',6,55000,'April to May','assets/korea.svg')");
}

// Direct photograph URLs. Using normal <img> URLs instead of remote images
// embedded inside SVG prevents the blank-image problem seen in some browsers.
function destination_image_url(string $image): string {
    $map = [
        'assets/india.svg' => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/c/c5/Meenakshi_Amman_Temple%2C_Madurai.jpg/960px-Meenakshi_Amman_Temple%2C_Madurai.jpg',
        'assets/paris.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Eiffel_Tower_Paris_Aug_2026.jpg/960px-Eiffel_Tower_Paris_Aug_2026.jpg',
        'assets/bali.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Rice_Terrace_View%2C_Bali%2C_Indonesia.jpg/960px-Rice_Terrace_View%2C_Bali%2C_Indonesia.jpg',
        'assets/dubai.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Burj_Khalifa_Image.jpg/960px-Burj_Khalifa_Image.jpg',
        'assets/singapore.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Marina_Bay_Sands%2C_2026.jpg/960px-Marina_Bay_Sands%2C_2026.jpg',
        'assets/korea.svg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Gyeongbokgung_Palace_%2855219197845%29.jpg/960px-Gyeongbokgung_Palace_%2855219197845%29.jpg'
    ];
    return $map[$image] ?? $image;
}
