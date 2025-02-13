<?php
    require 'includes/db_connect.php'; // Connect to database
    include 'includes/admin_header.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        // Sanitize and fetch the user data
        $id = intval($_POST['id']); // Using the passed ID

        // Fetch user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if ($user) {
            if (isset($_POST['username'])) {
                // Prepare the base SQL statement without the password part
                $sql = "UPDATE users SET username = :username, firstname = :firstname, lastname = :lastname, email = :email, role = :role";

                // Check if the password is not blank
                if (!empty($_POST['password'])) {
                    // Add password to the SQL statement and hash it
                    $sql .= ", password = :password";
                }

                $sql .= " WHERE id = :id";

                // Prepare and execute the statement
                $stmt = $pdo->prepare($sql);

                // Create the parameters array
                $params = [
                    'id' => $id,
                    'username' => $_POST['username'],
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'role' => $_POST['role']
                ];

                // If the password is not empty, add the hashed password to the parameters
                if (!empty($_POST['password'])) {
                    $params['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                }

                $stmt->execute($params);
                echo "<div class='edit-form-wrapper success-message'>User updated successfully!</div>";
            } else {
                // Display the edit form within the scoped wrapper
                echo '<div class="edit-form-wrapper">
                        <h2>Edit User</h2>
                        <form action="edit_employee.php" method="post">
                            <input type="hidden" name="id" value="' . $user['id'] . '">

                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" value="' . $user['username'] . '" required><br>

                            <label for="username">Password:</label>
                            <input type="text" id="password" name="password" value=""><br>

                            <label for="firstname">First Name:</label>
                            <input type="text" id="firstname" name="firstname" value="' . $user['firstname'] . '" required><br>

                            <label for="lastname">Last Name:</label>
                            <input type="text" id="lastname" name="lastname" value="' . $user['lastname'] . '" required><br>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="' . $user['email'] . '" required><br>

                            <label for="role">Role:</label>
                            <select name="role">
                                <option value="user"' . ($user['role'] == 'user' ? ' selected' : '') . '>User</option>
                                <option value="admin"' . ($user['role'] == 'admin' ? ' selected' : '') . '>Admin</option>
                            </select><br>

                            <button type="submit">Update</button>
                        </form>
                      </div>';
            }
        } else {
            echo "<div class='edit-form-wrapper error-message'>User not found.</div>";
        }
    } else {
        header('Location: dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/edit-employee.css">
</head>
</html>