<?php
session_start();

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Prepare SQL query to check the credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $input_username]);
    $user = $stmt->fetch();

    // Check if the user exists and verify the password
    if ($user && password_verify($input_password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username']; // Optionally store username
        $_SESSION['userid'] = $user['id']; // Optionally store user ID
        header("Location: dashboard.php");  //dashboard.php later
        exit();
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Login Page</h2>

        <?php
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <form method="POST" action="login.php">
            <label for="username">Username: </label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password: </label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <a href="forgot.php">Forgot password?</a>
    </div>
</body>
</html>
