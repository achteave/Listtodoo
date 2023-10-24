<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Include the db.php file to establish a database connection

$user_id = $_SESSION["user_id"];
$sql = "SELECT id, task_name, description, progress, is_done = 1 FROM tasks WHERE user_id = ? ORDER BY is_done ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_task'])) {
        $updatedTasks = $_POST['update_task'];
        $updatedProgress = $_POST['progress'];
        
        // Loop through the tasks to update their progress
        for ($i = 0; $i < count($updatedTasks); $i++) {
            $taskId = $updatedTasks[$i];
            $progress = $updatedProgress[$i];
            
            // Update the progress in the database
            $sql = "UPDATE tasks SET progress = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $progress, $taskId);
            $stmt->execute();
        }
        
        // Redirect back to the tasklist.php page after updating progress
        header("Location: tasklist.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Task List - Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleSt.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Task Tracker</a>
            <!-- Add a logout button -->
            <a href="logout.php" class="btn btn-primary">Logout</a>
        </div>
    </nav>
    <div class="container">
        <header>
            <h1>Task List</h1>
        </header>

        <section>
        <a href="add_task.php"  class="btn btn-primary">Add Task</a>

            <form action="tasklist.php" method="post">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {                        
                        echo "<tr>";
                        $taskNameClass = ($row['progress'] === 'Completed') ? 'completed-task' : ''; // Check if task is completed

                        echo "<td class='$taskNameClass'>" . $row['task_name'] . "</td>";     

                        echo "<td>";
                        echo "<input type='hidden' name='update_task[]' value='" . $row['id'] . "'>";
                        echo "<select class='form-select' name='progress[]' onchange='this.form.submit()'>";
                        
                        $progressOptions = ['Not Started', 'In Progress', 'Completed'];
                        foreach ($progressOptions as $option) {
                            $selected = ($row['progress'] === $option) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        
                        echo "</select>";
                        echo "</td>";
                        echo "<td>";

                        echo "<div>";
                        echo "<a href='view_task.php?task_id=" . $row['id'] . "' class='btn btn-primary' style='margin-right: 10px;'>View Details</a>";
                       
                        echo "<a href='markAsDone.php?task_id=" . $row['id'] . "' class='btn btn-primary mark-as-done"; // Start the button element
                        if ($row['progress'] === 'Completed') {
                            echo " completed-button"; // Add the completed-button class if the task is completed
                        }
                    
                        echo "'>Mark As Done</a>"; // Close the button element

                        echo "</div>";         
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </form>
        </section>
        <footer>
            <p>&copy; 2023 Task Tracker</p>
        </footer>
    </div>
</body>
</html>
