<?php
require 'includes/db_connect.php'; // Connect to database
include 'includes/admin_header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Get employee ID
    $id = intval($_POST['id']); 

    // Fetch employee details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Employee not found.");
    }

    // Fetch assigned courses
    $stmt_courses = $pdo->prepare("
        SELECT ec.id, c.course_name, c.training_video_link, ec.progress 
        FROM employee_courses ec
        JOIN courses c ON ec.course_id = c.id
        WHERE ec.employee_id = :id;
    ");
    $stmt_courses->execute(['id' => $id]);
    $courses = $stmt_courses->fetchAll();
} else {
    die("Request unsuccessful.");
}

// Handle Course Assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_course'])) {
    // Sanitize and fetch POST data
    $course_id = intval($_POST['course_id']);
    $video_link = $_POST['video_link'] ?? null;

    // Insert into employee_courses table
    $stmt_assign = $pdo->prepare("
        INSERT INTO employee_courses (employee_id, course_id, progress)
        VALUES (:employee_id, :course_id, 'Not Started')
    ");
    $stmt_assign->execute([
        'employee_id' => $id,
        'course_id' => $course_id,
    ]);

    // Redirect to the same page with GET request after POST (PRG Pattern)
    header("Location: view_employees.php"); // Use the employee ID in the URL
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employee Training</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/employee-training.css">
</head>
<body>
    <div class="main-content">
        <!-- Header Section -->
        <h1>Training for <?php echo htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['lastname']); ?></h1>

        <!-- Assigned Courses Table -->
        <section class="widget">
            <h2>Assigned Courses</h2>
            <table>
                <tr>
                    <th>Course Name</th>
                    <th>Training Video</th>
                    <th>Progress</th>
                </tr>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                    <td>
                        <?php if ($course['training_video_link']): ?>
                            <a href="<?php echo htmlspecialchars($course['training_video_link']); ?>" target="_blank">Watch Video</a>
                        <?php else: ?>
                            No Video Assigned
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($course['progress']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <!-- Assign New Course Section -->
        <section class="widget">
            <h2>Assign a New Course</h2>
            <form class="form-container" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <!-- Course Dropdown -->
                <label for="course_id">Select Course:</label>
                <select name="course_id" required>
                    <?php
                    $stmt_all_courses = $pdo->query("SELECT * FROM courses");
                    while ($course = $stmt_all_courses->fetch()) {
                        echo "<option value='{$course['id']}'>{$course['course_name']}</option>";
                    }
                    ?>
                </select>

                <!-- Submit Button -->
                <button type="submit" name="assign_course">Assign Course</button>
            </form>
        </section>
    </div>
</body>
</html>
