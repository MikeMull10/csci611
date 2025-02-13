<?php include("includes/db_connect.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include("includes/admin_header.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Welcome to Your Dashboard</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($user['firstname']); ?></span>
            </div>
        </div>

        <!-- Dashboard Widgets -->
        <div class="widget">
            <h3>Profile Information</h3>
            <p>Name: <?php echo htmlspecialchars($user['firstname']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Role: <?php echo htmlspecialchars($user['role'])?></p>
        </div>
        
        <!-- Additional widgets can be added here, such as activity data -->
        <div class="widget">
            <h3>Recent Activity</h3>
            <p>Display user activity data here (e.g., last login time, recent actions, etc.)</p>
        </div>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
