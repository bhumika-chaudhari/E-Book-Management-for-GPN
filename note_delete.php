<?php
session_start();

// Check if the user is logged in and department is selected
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['department'])) {
    header("Location: department_login.php");
    exit();
}

$department = $_SESSION['department'];

// Available departments
$department_tables = [
    "Automobile" => "notes_auto",
    "Civil" => "notes_civil",
    "Computer" => "notes_computer",
    "Dress Designing" => "notes_ddgm",
    "Electrical" => "notes_electrical",
    "Electronics and Telecommunication" => "notes_entc",
    "Interior Designing" => "notes_idd",
    "Information Technology" => "notes_if",
    "Mechanical" => "notes_mechanical",
    "Mechatronics" => "notes_mect",
    "Polymer" => "notes_poly"
    ];
$table_name = $department_tables[$department];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "librabry_db";
$port = 3307;

// Create MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare DELETE statement
    $delete_sql = "DELETE FROM $table_name WHERE id=?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to the department management page after deletion
        header("Location: department_select.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No ID provided for deletion.";
}

// Close the MySQL connection
$conn->close();
?>