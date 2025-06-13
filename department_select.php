<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: department_login.php");
    exit();
}

// Available departments
$department_tables = [
   "Automobile" => "notes_auto",
"Civil" => "notes_civil",
"Computer" => "notes_computer",
"Dress Design" => "notes_ddgm",
"Electrical" => "notes_electrical",
"Entc" => "notes_entc",
"Interior Designing" => "notes_idd",
"Information Technology" => "notes_if",
"Mechanical" => "notes_mechanical",
"Mechatronics" => "notes_mect",
"Polymer" => "notes_poly"

];

// Handle department selection
$department = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['department'])) {
    $department = trim($_POST['department']);
    $_SESSION['department'] = $department;
} elseif (isset($_SESSION['department'])) {
    $department = $_SESSION['department'];
}

// Check if department is selected and valid
if ($department && array_key_exists($department, $department_tables)) {
    $table_name = $department_tables[$department];
} else {
  
    $department = null; // Reset department if invalid
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "librabry_db";
$port = 3307;
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$notes = []; // Empty array for notes
$search_query = ""; // Empty search query

// Check if department is selected and valid
if ($department && array_key_exists($department, $department_tables)) {
    $table_name = $department_tables[$department];

    // If search query is submitted, filter results based on the query
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
        $search_query = trim($_POST['search_query']);
        
        // Prepare the SQL query to search by title or subject
        $sql = "SELECT * FROM $table_name WHERE title LIKE ? OR subject LIKE ?";
        $stmt = $conn->prepare($sql);
        
        // Bind the search query to the SQL statement, adding wildcards for partial matches
        $search_term = "%" . $search_query . "%";
        $stmt->bind_param("ss", $search_term, $search_term);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch all matching results
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }
        }
        
        $stmt->close();
    } else {
        // If no search query, fetch all notes for the selected department
        $sql = "SELECT * FROM $table_name"; // Simple query without filtering
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }
        }
    }
}


// Handle form submission for file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Get form data
    $year = $_POST['year'];
    $title = $_POST['title'];
    $sub = $_POST['subject'];
    $description = $_POST['description'];
    $type = $_POST['resource_type'];

    // Handle file upload
    $file_tmp = $_FILES['pdf_path']['tmp_name'];
    $file_name = basename($_FILES['pdf_path']['name']);
    $destination = 'departments/pdfs/' . $file_name;


    // Move the file to the destination
    if (move_uploaded_file($file_tmp, $destination)) {
        // File uploaded successfully, now insert into the database
        $stmt = $conn->prepare("INSERT INTO $table_name (year, title, subject, description, pdf_path, department, resource_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $year, $title, $sub, $description, $destination, $department, $type);

        if ($stmt->execute()) {
        } else {
            die("Database error: " . $stmt->error);
        }

        $stmt->close();
    } else {
        die("Error: File upload failed.");
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage <?php echo htmlspecialchars($department ? $department : 'Select Department'); ?> Notes</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-department_select.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 60%; /* Could be more or less, depending on screen size */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            position: relative; /* For positioning the close button */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute; /* Position it absolutely within the modal-content */
            top: 10px;
            right: 15px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
.form-label {
    font-weight: bold;
    color: #333;
}

.form-control {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    margin-bottom: 15px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}
#upload_form input[type="text"] {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 10px;
}
    </style>
</head>
<body>
<!-- Custom Navbar -->
<div class="navbar">
    <div class="navbar-logo"><a href="#"><i class="fas fa-book"></i> GPN</a></div>
    <button class="navbar-toggle" id="navbar-toggle" aria-label="Toggle Navigation Menu"><i class="fas fa-bars"></i></button>
    <ul>
        <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="notes.html"><i class="fas fa-book-open"></i> Notes</a></li>
        <li class="dropdown">
            <button id="dropdownButton" class="dropbtn">Login<i class="fa fa-caret-down"></i></button>
            <div id="dropdownMenu" class="dropdown-content">
                <a href="admin_login.php">Admin</a>
                <a href="department_login.php">Department</a>
            </div>
        </li>
        <li><a href="About.html"><i class="fas fa-info-circle"></i> About</a></li>
        <li><a href="department_login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="container2">
    <h2>Manage Notes for <?php echo htmlspecialchars($department ? $department : 'Select Department'); ?></h2>

    <!-- Department Selection -->
    <form method="post" action="department_select.php">
        <label for="department">Select Department:</label>
        <select name="department" id="department" required>
            <option value="">--Select--</option>
            <?php foreach ($department_tables as $key => $table): ?>
                <option value="<?php echo htmlspecialchars($key); ?>" <?php if ($key == $department) echo "selected"; ?>>
                    <?php echo htmlspecialchars($key); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Submit" id="search_btn">
    </form>

    <!-- Upload button -->
    <button class="btn btn-primary" id="uploadBtn">Upload</button>

    <!-- Search Bar -->
<?php if ($department): ?>
<div class="search-bar">
    <form method="post" action="department_select.php">
        <input type="text" name="search_query" value="<?php echo isset($search_query) ? htmlspecialchars($search_query) : ''; ?>" placeholder="Search by Title or Subject">
        <input type="submit" value="Search">
    </form>
</div>
<?php endif; ?>


    <!-- Notes Table -->
    <?php if ($department && count($notes) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Year</th>
                <th>Title</th>
                <th>Subject</th>
                <th>Description</th>
                <th>Department</th>
                <th>Resource Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notes as $note): ?>
            <tr>
                <td><?php echo htmlspecialchars($note['id']); ?></td>
                <td><?php echo htmlspecialchars($note['year']); ?></td>
                <td><?php echo htmlspecialchars($note['title']); ?></td>
                <td><?php echo htmlspecialchars($note['subject']); ?></td>
                <td><?php echo htmlspecialchars($note['description']); ?></td>
                <td><?php echo htmlspecialchars($note['department']); ?></td>
                <td><?php echo htmlspecialchars($note['resource_type']); ?></td>
                <td>
                    <a href="note_update.php?id=<?php echo urlencode($note['id']); ?>" class="btn btn-primary btn-sm">Update</a>
                    <a href="note_delete.php?id=<?php echo urlencode($note['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No notes available.</p>
    <?php endif; ?>
</div>

<!-- Upload Form Modal -->
<div id="uploadModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Upload Notes</h2>
        <form method="post" enctype="multipart/form-data" id="upload_form">
            <div class="mb-3">
                <label for="year" class="form-label">Select Year</label>
                <select class="form-control" id="year" name="year">
                    <option value="">-- Select Year --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter the Title" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter the Subject" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter the Description" required>
            </div>
            <div class="mb-3">
                <label for="pdf_path" class="form-label">Upload PDF</label>
                <input type="file" class="form-control" id="pdf_path" name="pdf_path" required>
            </div>
            <div class="mb-3">
                <label for="resource_type" class="form-label">Select Resource Type</label>
                <select class="form-control" id="resource_type" name="resource_type">
                    <option value="">-- Select Resource Type --</option>
                    <option value="Notes">Notes</option>
                    <option value="Manual">Manual</option>
                    <option value="Syllabus">Syllabus</option>
                    <option value="Paper">Paper</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
</div>
<!-- Upload Form Modal End -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var modal = document.getElementById("uploadModal");// Get modal element
  
    var btn = document.getElementById("uploadBtn"); // Get button that opens the modal

    var span = document.getElementsByClassName("close")[0];// Get the <span> element that closes the modal

    btn.onclick = function() {
        modal.style.display = "block";
    } // When the user clicks the button, open the modal 
    
    span.onclick = function() {
        modal.style.display = "none";
    }// When the user clicks on <span> (x), close the modal
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }// When the user clicks anywhere outside of the modal, close it
</script>
</body>
</html>

