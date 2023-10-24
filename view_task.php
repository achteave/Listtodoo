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

    // Get the task details from the database
    $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    // If the task does not exist, redirect back to the tasklist page
    if ($task == null) {
        header("Location: tasklist.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Details - Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleV.css">
</head>
<div class="container">
    <header>
        <h1>Task Details</h1>
    </header>

    <section>
        <h2><?php echo $task['task_name']; ?></h2>
        <p><strong>Progress:</strong> <?php echo $task['progress']; ?></p>
        <p><strong>Description:</strong> <?php echo $task['description']; ?></p>

        <a href="edit_task.php?task_id=<?php echo $task['id']; ?>" class="btn btn-primary">Edit Task</a>
        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $task['id']; ?>)">Delete Task</button>
    </section>

    <footer>
        <p>&copy; 2023 Task Tracker</p>
    </footer>
</div>

<script>
    function confirmDelete(taskId) {
        var confirmed = confirm("Are you sure you want to delete this task?");
        if (confirmed) {
            window.location.href = "delete_task.php?task_id=" + taskId;
        }
    }
</script>
</html>
