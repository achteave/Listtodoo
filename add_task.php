<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php'); // Include the db.php file to establish a database connection

    $user_id = $_SESSION["user_id"];
    $task_name = $_POST["task_name"];
    $description = $_POST["description"];

    // Insert the new task into the database
    $sql = "INSERT INTO tasks (user_id, task_name, description, progress, is_done) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // You can set default values for progress and is_done as needed
    $progress = "Not Started";
    $is_done = 0;

    $stmt->bind_param("isssi", $user_id, $task_name, $description, $progress, $is_done);
    $stmt->execute();
    
    // After successful insertion, redirect back to the tasklist.php page
    header("Location: tasklist.php");
}
?>

<html>
<div class="big-wrapper light">
        <img src="./img/shape.png" alt="" class="shape" />
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleT.css">

    </head>
    <body>
            <form action="add_task.php" method="post">
                <div class="mb-3">
                    <label for="task_name" class="form-label">Task Name:</label>
                    <input type="text" class="form-control" name="task_name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
            <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <script src="./app.js"></script>
    </body>
</html>