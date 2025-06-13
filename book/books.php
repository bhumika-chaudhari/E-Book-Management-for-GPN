<?php
// Add this block at the top of your books.php file, before any HTML output
if (isset($_GET['updateSuccess']) && $_GET['updateSuccess'] == 1) {
    echo '<div class="alert alert-success">The book has been updated successfully.</div>';
}
?>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

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

// Handle logout
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    header('Location: login.php');
    exit;
}

// Handle new user creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createUser'])) {
    $newUsername = $_POST['email'];
    $newPassword = $_POST['password']; // Hash the password for security
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newUsername, $hashedPassword);

    if ($stmt->execute()) {
        $userCreateSuccess = "New user has been created successfully.";
    } else {
        $userCreateError = "Error: " . $stmt->error;
    }
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $description = !empty($_POST['description']) ? $_POST['description'] : NULL;
    $department = $_POST['department'];
    $target_dir = "books data/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $pdfPath = $target_file;

        $sql = "INSERT INTO books (title, description, pdf_path, department, year) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $title, $description, $pdfPath, $department, $year);

        if ($stmt->execute()) {
            $uploadSuccess = "The file has been uploaded successfully.";
        } else {
            $uploadError = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $uploadError = "Sorry, there was an error uploading your file.";
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
// Handle book deletion
if (isset($_GET['delete'])) {
    $bookId = intval($_GET['delete']);
    
    // First, retrieve the file path from the database for the book to be deleted
    $sql = "SELECT pdf_path FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Check if the book exists in the database
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pdfPath = $row['pdf_path']; // Get the file path from the database
            
            // Remove the file from the server
            $filePath = 'books data/' . basename($pdfPath); // Construct the full file path
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            } else {
                // Handle case where the file does not exist
                echo 'File not found.';
            }

            // Now, delete the book entry from the database
            $deleteSql = "DELETE FROM books WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $bookId);
            if ($deleteStmt->execute()) {
                $deleteSuccess = "The book has been deleted successfully.";
            } else {
                $deleteError = "Error: " . $deleteStmt->error;
            }
            $deleteStmt->close();
        } else {
            $deleteError = "Error: Book not found.";
        }
    } else {
        $deleteError = "Error: " . $stmt->error;
    }
    $stmt->close();
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


// Retrieve books
$sql = "SELECT id, year, title, description, pdf_path, department FROM books";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Library</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../departments/department-style.css">
    <script>
        document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to delete this book?')) {
                    event.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
   <!-- Custom Navbar -->
   <div class="navbar">
        <div class="navbar-logo">
            <a href="#"><i class="fas fa-book"></i> GPN</a></div>
        <button class="navbar-toggle" id="navbar-toggle"><i class="fas fa-bars"></i></button>
        <ul>
            <li><a href="../home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="../notes.html"><i class="fas fa-book-open"></i> Notes</a></li>
            <li class="dropdown">
                <button id="dropdownButton" class="dropbtn">Login <i class="fa fa-caret-down"></i></button>
                <div id="dropdownMenu" class="dropdown-content">
                    <a href="../admin_login.php">Admin</a>
                    <a href="../department_login.php">Department </a>
                </div>
            </li>
            <li><a href="../About.html"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="../admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

        </ul>
    </div>

    <header>
        <h1>Books Library</h1>
        <div class="upload-container">
            <button id="openUploadFormBtn" class="upload_btn"></button>
        </div>
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search Books...">
            <button id="searchClear" class="search-clear">&times;</button>
        </div>
    </header>

    <main>
        <div class="book-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $pdfPath = 'books data/' . basename($row['pdf_path']);
                    $bookId = $row['id']; // Ensure your books table has an 'id' column

                    echo '<div class="book-card">';
                    echo '<h2>Year ' . htmlspecialchars($row['year']) . '</h2>';
                    echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<h2>Department: ' . htmlspecialchars($row['department']) . '</h2>';
                    echo '<p>' . htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '<div class="button-group">';
                    echo '<a href="' . htmlspecialchars($pdfPath) . '" class="download-btn" download>Download</a>';
                    echo '<a href="edit_book.php?id=' . $bookId . '" class="btn btn-warning">Edit</a>';
                    echo '<a href="?delete=' . $bookId . '" class="btn btn-danger">Delete</a>';
                    echo '</div>';
                    
                    echo '</div>';
                }
            } else {
                echo '<p>No books are currently available. Please visit again!</p>';
            }
            ?>
        </div>

        <div id="popup" class="popup" style="display:none;">
            <div class="popup-content">
                <span class="close-btn" id="closePopupBtn">&times;</span>
                <h2>Upload Your Notes</h2>
                <?php if (isset($uploadSuccess)): ?>
                    <div class="alert alert-success"><?php echo $uploadSuccess; ?></div>
                <?php elseif (isset($uploadError)): ?>
                    <div class="alert alert-danger"><?php echo $uploadError; ?></div>
                <?php endif; ?>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="text" name="year" class="form-control" id="year" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fileToUpload" class="form-label">Select PDF to upload:</label>
                        <input type="file" name="fileToUpload" class="form-control" id="fileToUpload" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select name="department" class="form-select" id="department" required>
                            <option value="">Select Department</option>
                            <option value="Computer">Computer</option>
                            <option value="Mechanical">Mechanical</option>
                            <option value="Civil">Civil</option>
                            <option value="Electrical">Electrical</option>
                            <option value="Entc">ENTC</option>
                            <option value="Polymer">Polymer</option>
                            <option value="IDD">IDD</option>
                            <option value="DDGM">DDGM</option>
                            <option value="Automobile">Automobile</option>
                            <option value="Mechatronics">Mechatronics</option>
                            <option value="IT">IT</option>
                        </select>
                    </div>
                    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const bookCards = document.querySelectorAll('.book-card');
            
            bookCards.forEach(card => {
                const title = card.querySelector('h2').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(query) || description.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
            
            document.getElementById('searchClear').style.display = query.length > 0 ? 'block' : 'none';
        });

        document.getElementById('searchClear').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchInput').dispatchEvent(new Event('input'));
            this.style.display = 'none';
        });

        // Upload Form popup
        const uploadForm = document.getElementById("popup");
        const openUploadFormButton = document.getElementById("openUploadFormBtn");
        const closeUploadFormButton = document.getElementById("closePopupBtn");

        openUploadFormButton.addEventListener("click", function() {
            uploadForm.style.display = "block";
        });

        closeUploadFormButton.addEventListener("click", function() {
            uploadForm.style.display = "none";
        });

        window.addEventListener("click", function(event) {
            if (event.target === uploadForm) {
                uploadForm.style.display = "none";
            }
        });
    </script>
</body>
</html>
