<?php
session_start();

// Check if the user is logged in and department is selected
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['department'])) {
    header("Location: department_login.php");
    exit();
}

$department = $_SESSION['department'];

// Map department to corresponding tables
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $year = $_POST['year'];
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $resource_type = $_POST['resource_type'];

    // Prepare UPDATE statement
    $update_sql = "UPDATE $table_name SET year=?, title=?, subject=?, description=?, resource_type=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssi", $year, $title, $subject, $description, $resource_type, $id);

    if ($stmt->execute()) {
        // Redirect to the department management page after update
        header("Location: department_select.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the existing data
    $select_sql = "SELECT * FROM $table_name WHERE id=?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $note = $result->fetch_assoc();
    } else {
        echo "Note not found.";
        exit();
    }

    $stmt->close();
} else {
    echo "No ID provided.";
    exit();
}

// Close the MySQL connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Note</title>
    <!-- Link to the external CSS file -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
 /* CSS for the update form */
body {
    background-color: #f4f7f6;
    font-family: 'Lato', sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    position: relative; /* For positioning the close button */
    margin-top: 15px;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 50%;
}
.container:hover{
    box-shadow: 5px 5px 15px 10px rgba(0, 0, 0, 0.2); /* Keep the shadow for depth */
}

h2 {
    color: #333;
    text-align: center;
    font-weight: 700;
    margin-bottom: 15px;
}

/* Styling for the close button */
.close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    font-weight: bold;
    color: #888;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: #ff0000; /* Change color on hover */
}

.form-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: #555;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
}

textarea.form-control {
    resize: none;
}

button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 15%;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    h2 {
        font-size: 1.5rem;
    }

    button[type="submit"] {
        font-size: 1rem;
    }
}


    </style>
</head>

<body>
<div class="container">
    <h2>Update Note</h2>
    <form method="post" action="note_update.php">
        <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="text" class="form-control" id="year" name="year" value="<?php echo $note['year']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $note['title']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $note['subject']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $note['description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="resource_type" class="form-label">Resource Type</label>
            <input type="text" class="form-control" id="resource_type" name="resource_type" value="<?php echo $note['resource_type']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
