<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to handle login
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
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

// Fetch years for dropdown
$sqlYears = "SELECT DISTINCT year FROM notes_ddgm WHERE department = 'Dress Design'";
$yearsResult = $conn->query($sqlYears);

// Fetch subjects for the selected year
$selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
$selectedSubject = isset($_GET['subject']) ? $_GET['subject'] : '';

$sqlSubjects = "SELECT DISTINCT subject FROM notes_ddgm WHERE department = 'Dress Design' AND year = ?";
$stmtSubjects = $conn->prepare($sqlSubjects);
$stmtSubjects->bind_param("s", $selectedYear);
$stmtSubjects->execute();
$subjectsResult = $stmtSubjects->get_result();

// Fetch books for the selected year and subject
$sqlBooks = "SELECT year, title, description, pdf_path FROM notes_ddgm WHERE department = 'Dress Design'";
if ($selectedYear) {
    $sqlBooks .= " AND year = ?";
}
if ($selectedSubject) {
    $sqlBooks .= " AND subject = ?";
}

$stmtBooks = $conn->prepare($sqlBooks);
if ($selectedYear && $selectedSubject) {
    $stmtBooks->bind_param("ss", $selectedYear, $selectedSubject);
} elseif ($selectedYear) {
    $stmtBooks->bind_param("s", $selectedYear);
}
$stmtBooks->execute();
$booksResult = $stmtBooks->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes Library For Dress Design Department</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="department-style.css">
   
</head>
<body style=' background-color: #eaddc3;'>
     <!-- Custom Navbar -->
     <div class="navbar">
        <div class="navbar-logo">
            <a href="#"><i class="fas fa-book"></i> GPN</a></div>
        <button class="navbar-toggle" id="navbar-toggle"><i class="fas fa-bars"></i></button>
        <ul>
        <li><a href="../notes.html"><i class="fas fa-arrow-left"></i> Back</a></li>

            <li><a href="../home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="../notes.html"><i class="fas fa-book-open"></i> Notes</a></li>
            <li class="dropdown">
              <a>  <button id="dropdownButton" class="dropbtn">Login
                  <i class="fa fa-caret-down"></i></a>
                </button>
                <div id="dropdownMenu" class="dropdown-content">
                    <a href="../admin_login.php">Admin</a>
                    <a href="../department_login.php">Department </a>
                </div>
            </li>
            
            <li><a href="../About.html"><i class="fas fa-info-circle"></i> About</a></li>

          
        </ul>
    </div>
    
    <header>
        <h1>Notes Library For Dress Design Department</h1>
        <div class="search-container">
            <select id="yearSelect" class="form-select">
                <option value="">Select Year</option>
                <?php while ($year = $yearsResult->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($year['year']); ?>" <?php echo ($selectedYear === $year['year']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($year['year']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <select id="subjectSelect" class="form-select" style="<?php echo $selectedYear ? 'display: inline-block;' : 'display: none;'; ?>">
                <option value="">Select Subject</option>
                <?php while ($subject = $subjectsResult->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($subject['subject']); ?>" <?php echo ($selectedSubject === $subject['subject']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($subject['subject']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </header>

    <main>
        <div class="book-container">
            <?php
            if ($booksResult->num_rows > 0) {
                while ($row = $booksResult->fetch_assoc()) {
                    $pdfPath = 'pdfs/' . basename($row['pdf_path']);

                    echo '<div class="book-card">';
                    echo '<h2>Year ' . htmlspecialchars($row['year']) . '</h2>';
                    echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<p>' . ($row['description'] !== NULL ? htmlspecialchars($row['description']) : '') . '</p>';
                    echo '<a href="' . htmlspecialchars($pdfPath) . '" class="download-btn" download>Download</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No books found.</p>';
            }
            ?>
        </div>
    </main>

    <script>
        document.getElementById('yearSelect').addEventListener('change', function() {
            var selectedYear = this.value;
            var subjectSelect = document.getElementById('subjectSelect');
            subjectSelect.style.display = selectedYear ? 'inline-block' : 'none';

            if (selectedYear) {
                window.location.href = '?year=' + selectedYear;
            }
        });

        document.getElementById('subjectSelect').addEventListener('change', function() {
            var selectedSubject = this.value;
            var selectedYear = document.getElementById('yearSelect').value;

            if (selectedYear && selectedSubject) {
                window.location.href = '?year=' + selectedYear + '&subject=' + selectedSubject;
            }
        });
        document.getElementById('dropdownButton').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent default action
    var dropdownMenu = document.getElementById('dropdownMenu');
    
    // Toggle the 'show' class
    dropdownMenu.classList.toggle('show');
});

// Close the dropdown if clicked outside
window.addEventListener('click', function(event) {
    var dropdownMenu = document.getElementById('dropdownMenu');
    var dropdownButton = document.getElementById('dropdownButton');
    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        if (dropdownMenu.classList.contains('show')) {
            dropdownMenu.classList.remove('show');
        }
    }
});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
