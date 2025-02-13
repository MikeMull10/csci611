<?php
require 'includes/db_connect.php';
require 'includes/admin_header.php';

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
} else if ($user['role'] != 'client') {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['userid'];

// Handle permit purchase
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['permit_id'])) {
    $permit_id = intval($_POST['permit_id']);
    $assigned_date = date('Y-m-d'); // Today's date
    $expiration_date = date('Y-m-d', strtotime('+1 year')); // Expiration set to 1 year later

    // Insert into the permits table
    $stmt = $pdo->prepare("INSERT INTO permits (user_id, permit_id, assigned_date, expiration_date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $permit_id, $assigned_date, $expiration_date])) {
        echo "<p class='success'>Permit purchased successfully!</p>";
    } else {
        echo "<p class='error'>Failed to purchase permit.</p>";
    }
}

// Fetch available permits
$stmt_permits = $pdo->query("SELECT * FROM available_permits");
$permits = $stmt_permits->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy a Permit</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/permit.css">
</head>
<body>
    <div class="main-content">
        <h1>Buy a Permit</h1>
        <form method="post">
            <label for="permit_id">Select a Permit:</label>
            <select name="permit_id" required>
                <?php foreach ($permits as $permit): ?>
                    <option value="<?= $permit['id']; ?>">
                        <?= htmlspecialchars($permit['permit_name']) . " - $" . number_format($permit['price'], 2); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="buy-button">Buy Permit</button>
        </form>
    </div>
</body>
</html>
