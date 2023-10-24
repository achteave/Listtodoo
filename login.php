<?php
include('db.php'); // Include the db.php file to establish a database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Prepare and execute the SELECT statement
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION["user_id"] = $id;
            header("Location: tasklist.php"); // Redirect to the task list page
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleL.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Login to Task Tracker</h1>
        </header>

        <section>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <p>
                Don't have an account? <a href="register.php">Register here</a>.
            </p>
        </section>

        <footer>
            <p>&copy; 2023 Task Tracker</p>
        </footer>
    </div>
</body>
</html>
