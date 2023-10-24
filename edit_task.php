<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Include the db.php file to establish a database connection

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    
    // Retrieve task details from the database
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT id, task_name, description, progress, is_done FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    
    // Check if the task exists and belongs to the user
    if (!$task) {
        // Task not found or does not belong to the user, handle accordingly
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission to update the task details
    $newTaskName = $_POST['task_name'];
    $newDescription = $_POST['description'];
    $newProgress = $_POST['progress'];
    
    // Update the task in the database
    $sql = "UPDATE tasks SET task_name = ?, description = ?, progress = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $newTaskName, $newDescription, $newProgress, $task_id, $user_id);
    $stmt->execute();
    
    // Redirect back to the tasklist after editing
    header("Location: tasklist.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task - Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleE.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Task</h1>
        </header>

        <section>
            <!-- Task editing form -->
            <form action="edit_task.php?task_id=<?php echo $task_id; ?>" method="post">
                <div class="mb-3">
                    <label for="task_name" class="form-label">Task Name:</label>
                    <input type="text" class="form-control" name="task_name" value="<?php echo $task['task_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" name="description"><?php echo $task['description']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="progress" class="form-label">Progress:</label>
                    <select class="form-select" name="progress">
                        <option value="Not Started" <?php if ($task['progress'] === 'Not Started') echo 'selected'; ?>>Not Started</option>
                        <option value="In Progress" <?php if ($task['progress'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if ($task['progress'] === 'Completed') echo 'selected'; ?>>Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </section>

        <footer>
            <p>&copy; 2023 Task Tracker</p>
        </footer>
    </div>
</body>
</html>
