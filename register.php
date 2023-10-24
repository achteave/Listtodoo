<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username is already registered
    $check_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose a different one.";
    } else {
        // Insert the new user into the database
        $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $username, $hashed_password);

        
        if ($stmt->execute()) {
            echo "Registration successful. You can now <a href='login.php'>log in</a>.";
        } else {
            echo "Registration failed. Please try again.";
        }
    }
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleR.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>
</body>
</html>
