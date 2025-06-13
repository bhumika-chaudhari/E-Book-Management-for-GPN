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
    <link rel="stylesheet" href="../departments/department-style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: rgb(250, 235, 212);
">
   <!-- Custom Navbar -->
   <div class="navbar">
        <div class="navbar-logo" style="padding: 10px; padding-left:20px;">
            <a href="#"><i class="fas fa-book"></i> GPN</a></div>
        <ul>
            <li><a href="../home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="../notes.html"><i class="fas fa-book-open"></i> Notes</a></li>
            <li><a href="../About.html"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="../department_login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <header>
        <h1>Books Library</h1>
    
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
        echo '<div class="book-card">';
        echo '<h2 class="book-year">Year ' . htmlspecialchars($row['year']) . '</h2>';
        echo '<h2 class="book-title">' . htmlspecialchars($row['title']) . '</h2>';
        echo '<h2 class="book-department">Department: ' . htmlspecialchars($row['department']) . '</h2>';
        echo '<p class="book-description">' . htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<div class="button-group">';
        echo '<a href="' . htmlspecialchars($pdfPath) . '" class="download-btn" download>Download</a>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No books are currently available. Please visit again!</p>';
}
?>

        </div>
    </main>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase(); // Convert the query to lowercase for case-insensitive search
    const bookCards = document.querySelectorAll('.book-card'); // Select all book cards

    bookCards.forEach(card => {
        const year = card.querySelector('.book-year').textContent.toLowerCase();

        if (year.includes(query)) {
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

        </script>
</body>
</html>
