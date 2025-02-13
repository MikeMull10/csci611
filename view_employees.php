<?php include("includes/db_connect.php"); ?>

<?php
if ($user) {
    if ($user['role'] != "admin") {
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include("includes/admin_header.php"); ?>

    <?php
        $sql = "SELECT * FROM users WHERE role != 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<link rel='stylesheet' type='text/css' href='css/employee-table.css'>";

        if ($users) {
            echo "<div class=\"user-table\">";
            echo "<h2>Employees</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Job Title</th><th>Supervisor(s)</th><th>Training</th><th>Edit</tb></tr>";

            foreach ($users as $user) {
                $sql2 = "SELECT * FROM supervisors WHERE employee_id = :employee_id"; // Use parameterized query to avoid SQL injection
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute(['employee_id' => $user['id']]); // Bind the actual value of $user['id']
                $supers = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                // Step 1: Get all supervisor IDs
                $supervisor_ids = array_column($supers, 'supervisor_id');

                // Step 2: Get the names of the supervisors
                if (!empty($supervisor_ids)) {
                    // Join the supervisor IDs into a string for the SQL IN clause
                    $placeholders = implode(',', array_fill(0, count($supervisor_ids), '?'));

                    // Prepare a query to get the names of the supervisors
                    $sql3 = "SELECT id, firstname, lastname FROM users WHERE id IN ($placeholders)";
                    $stmt3 = $pdo->prepare($sql3);
                    $stmt3->execute($supervisor_ids); // Bind the supervisor IDs to the query

                    // Fetch the supervisors' names
                    $supervisors = $stmt3->fetchAll(PDO::FETCH_ASSOC);

                    // Step 3: Format the names as a list with line breaks
                    $supervisor_names = array_map(function($supervisor) {
                        return $supervisor['firstname'] . ' ' . $supervisor['lastname'];
                    }, $supervisors);

                    // Join the names with <br> for HTML line breaks
                    $super = implode("<br>", $supervisor_names);
                } else {
                    $super = '';
                }
                echo "<tr>
                        <td>{$user['id']}</td>
                        <td>{$user['username']}</td>
                        <td>{$user['firstname']}</td>
                        <td>{$user['lastname']}</td>
                        <td>{$user['email']}</td>
                        <td>{$user['job_title']}</td>
                        <td>{$super}</td>
                        <td>
                            <form action=\"view_training.php\" method=\"post\" style=\"display:inline;\">
                                <input type=\"hidden\" name=\"id\" value=\"" . $user['id'] . "\">
                                <button type=\"submit\" class=\"edit-button\">View Training</button>
                            </form>
                        </td>
                        <td>
                            <form action=\"edit_employee.php\" method=\"post\" style=\"display:inline;\">
                                <input type=\"hidden\" name=\"id\" value=\"" . $user['id'] . "\">
                                <button type=\"submit\" class=\"edit-button\">Edit</button>
                            </form>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No non-admin users found.";
        }
        echo "</div>";
    ?>
</body>
</html>