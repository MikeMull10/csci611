<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection settings
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

// Create a PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed."); // Generic error message
}

if (isset($_SESSION['loggedin'])) {
    // Fetch user information based on the session user ID
    $userId = (int) $_SESSION['userid']; // Ensure it's an integer
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();
}

// Fetch additional data if needed
// Example: Get user activity data or stats for widgets
// $stmt_activity = $pdo->prepare("SELECT * FROM user_activity WHERE user_id = :id");
// $stmt_activity->execute(['id' => $_SESSION['userid']]);
// $user_activity = $stmt_activity->fetchAll();
?>