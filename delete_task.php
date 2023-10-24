<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Include the db.php file to establish a database connection

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $user_id = $_SESSION["user_id"];

    // Delete the task from the database
    $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        // Task deleted successfully
        $_SESSION['success_message'] = "Task deleted successfully.";
    } else {
        // Error deleting the task
        $_SESSION['error_message'] = "Error deleting the task.";
    }

    $stmt->close();
}

// Redirect back to the tasklist page after deleting the task
header("Location: tasklist.php");
exit;
