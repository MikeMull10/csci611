<?php
require 'includes/db_connect.php'; // Include database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finish_course'])) {
    $course_id = intval($_POST['course_id']);
    $user_id = intval($_POST['user_id']);

    // Prepare the update statement
    $stmt_update = $pdo->prepare("UPDATE employee_courses SET progress = :progress WHERE course_id = :course_id AND employee_id = :user_id");
    $stmt_update->execute([
        'progress' => 'Completed',
        'course_id' => $course_id,
        'user_id' => $user_id
    ]);

    // Redirect back to the training page (or wherever you want after updating)
    header("Location: training.php"); // Or wherever you want to redirect
    exit();
}
?>
