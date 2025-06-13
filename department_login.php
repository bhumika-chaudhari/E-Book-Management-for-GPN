<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'librabry_db', 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM dep_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();
        
        // Verify the password directly without hashing
        if ($password === $db_password) {
            $_SESSION['loggedin'] = true;
            header("Location: select_option.html");
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPN Library</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-department_login.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-logo"><a href="#"><i class="fas fa-book"></i> GPN</a></div>
        <button class="navbar-toggle" id="navbar-toggle" aria-label="Toggle Navigation Menu"><i class="fas fa-bars"></i></button>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="notes.html"><i class="fas fa-book-open"></i> Notes</a></li>
            <li class="dropdown">
                <button id="dropdownButton" class="dropbtn"> Login<i class="fa fa-caret-down"></i></button>
                <div id="dropdownMenu" class="dropdown-content">
                    <a href="admin_login.php">Admin</a>
                    <a href="department_login.php">Department</a>
                </div>
            </li>
            <li><a href="About.html"><i class="fas fa-info-circle"></i> About</a></li>
        </ul>
    </div>

    <!-- Login Form -->
    <div class="login-container">
        <img src="./pictures/logo.png" alt="Admin Logo" class="login-image">
        <h2>Department Login</h2>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
 <!-- Display error message if login fails -->
 <?php if (!empty($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
            <button type="submit" name="login">Login</button>
        </form>

       
    </div>

</body>
</html>
