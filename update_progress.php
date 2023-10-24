<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_id = $_POST["task_id"];
    $progress = $_POST["progress"];
include('db.php'); // Include the db.php file to establish a database connection

$update_query = "UPDATE tasks SET progress = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sii", $progress, $task_id, $user_id);
    
    if ($stmt->execute()) {
        // Task progress updated successfully
    } else {
        // Error updating task progress
    }

    $stmt->close();
    $conn->close();
}
// Redirect back to the task list page
header("Location: tasklist.php");
exit;
?>

