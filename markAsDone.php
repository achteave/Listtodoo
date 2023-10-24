<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $user_id = $_SESSION["user_id"];

    // Update the task's progress in the database to "Completed"
    $sql = "UPDATE tasks SET progress = 'Completed', is_done = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();

    // Redirect back to the tasklist.php page after marking as done
    header("Location: tasklist.php");
} else {
    header("Location: tasklist.php");
}
?>
