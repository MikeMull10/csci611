<?php
require 'includes/db_connect.php'; // Include database connection
require 'includes/admin_header.php';

// Check if there's an ID in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID from the URL

    // Fetch specific material based on ID
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $material = $stmt->fetch();

    // If material exists, display it
    if ($material) {
        $stmt_update = $pdo->prepare("SELECT progress FROM employee_courses WHERE course_id = :id AND employee_id = :user_id");
        $stmt_update->execute(['id' => $material['id'], 'user_id' => $user['id']]);

        $current_status = $stmt_update->fetch(PDO::FETCH_ASSOC)['progress'];

        if ($current_status == 'Not Started') {
            // Check if the video is "Not Started" and update the status (you might need to update the database as well)
            $status = "In Progress"; // For this example, we directly set the status as "Started"

            // Optionally update the status in the database (this requires adding a "status" field in your courses table or elsewhere)
            $stmt_update = $pdo->prepare("UPDATE employee_courses SET progress = :status WHERE course_id = :id AND employee_id = :user_id");
            $stmt_update->execute(['status' => $status, 'id' => $id, 'user_id' => $user['id']]);
        }

        echo "<div class='main-content training-page'>";
        echo "<h1>Training Material: " . htmlspecialchars($material['course_name']) . "</h1>";
        echo "<div class='training-material-detail'>";

        // Display the video embedded in the page
        if ($material['training_video_link']) {
            $video_url = htmlspecialchars($material['training_video_link']);
            
            // Check if it's a YouTube or Vimeo link to embed it accordingly
            if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                $video_id = parse_url($video_url, PHP_URL_QUERY);
                parse_str($video_id, $query_params);
                $video_id = isset($query_params['v']) ? $query_params['v'] : '';
                if ($video_id) {
                    // Embed YouTube video
                    echo "<div class='video-embed'>
                            <iframe width='560' height='315' src='https://www.youtube.com/embed/{$video_id}' frameborder='0' allowfullscreen></iframe>
                        </div>";
                }
            } elseif (strpos($video_url, 'vimeo.com') !== false) {
                $video_id = substr(parse_url($video_url, PHP_URL_PATH), 1);
                // Embed Vimeo video
                echo "<div class='video-embed'>
                        <iframe src='https://player.vimeo.com/video/{$video_id}' width='560' height='315' frameborder='0' allowfullscreen></iframe>
                    </div>";
            } else {
                // Generic embed (could be used for HTML5 video or others)
                echo "<div class='video-embed'>
                        <video width='560' height='315' controls>
                            <source src='{$video_url}' type='video/mp4'>
                            Your browser does not support the video tag.
                        </video>
                    </div>";
            }
        } else {
            echo "<p>No video assigned for this material.</p>";
        }

        echo '<form method="post" action="update_progress.php">
            <input type="hidden" name="course_id" value="' . $id . '">
            <input type="hidden" name="user_id" value="' . $user['id'] . '">
            <button type="submit" name="finish_course" class="finish-button">Mark as Finished</button>
        </form>';

        echo "</div>"; // End of training-material-detail
        echo "</div>"; // End of main-content training-page
    } else {
        echo "<p>Material not found.</p>";
    }
} else {
    // If no ID is passed, show the list of materials
    echo "<div class='main-content training-page'>";
    echo "<h1>Assigned Training Materials</h1>";

    // Fetch all materials from the database
    $stmt = $pdo->query("SELECT * FROM courses");
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($materials) {
        // Start table
        echo "<table class='training-materials-table'>";
        echo "<tr><th>Course Name</th><th>Status</th><th>Action</th></tr>"; // Table headers

        foreach ($materials as $material) {
            // Assuming you want to display the status in a static format like 'Not Started', 'In Progress', or 'Completed'
            // You can change this logic based on your requirements
            $stmt_update = $pdo->prepare("SELECT progress FROM employee_courses WHERE course_id = :id AND employee_id = :user_id");
            $stmt_update->execute(['id' => $material['id'], 'user_id' => $user['id']]);

            // Fetch the result
            $status = $stmt_update->fetch(PDO::FETCH_ASSOC)['progress'];

            echo "<tr>";
            echo "<td><a href='?id=" . $material['id'] . "'>" . htmlspecialchars($material['course_name']) . "</a></td>";
            echo "<td>" . htmlspecialchars($status) . "</td>";
            echo "<td><a href='?id=" . $material['id'] . "'>View Material</a></td>";
            echo "</tr>";
        }

        echo "</table>"; // End table
    } else {
        echo "<p>No materials found.</p>";
    }
    echo "</div>"; // End of main-content training-page
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Materials</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/training.css">
</head>
<body>
    
</body>
</html>