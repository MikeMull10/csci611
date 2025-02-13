<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

// If user is not logged in, redirect to login page
// if (!isset($_SESSION['loggedin'])) {
//     header('Location: login.php');
//     exit();
// }

// Database connection settings
$host = 'localhost';
$dbname = 'blm';
$username = 'root';  // default username for MySQL on WAMP
$password = '';      // default password for MySQL on WAMP

// Create a PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

if (isset($_SESSION['loggedin'])) {
    // Fetch user information based on the session user ID
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['userid']]);
    $user = $stmt->fetch();
}

// Fetch additional data if needed
// Example: Get user activity data or stats for widgets
// $stmt_activity = $pdo->prepare("SELECT * FROM user_activity WHERE user_id = :id");
// $stmt_activity->execute(['id' => $_SESSION['userid']]);
// $user_activity = $stmt_activity->fetchAll();
?>