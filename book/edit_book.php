<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle book update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $bookId = intval($_POST['id']);
    $title = $_POST['title'];
    $year = $_POST['year'];
    $description = !empty($_POST['description']) ? $_POST['description'] : NULL;
    $department = $_POST['department'];

    $sql = "UPDATE books SET title = ?, year = ?, description = ?, department = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $year, $description, $department, $bookId);
    if ($stmt->execute()) {
        // Redirect to books.php after successful update
        header('Location: books.php?updateSuccess=1');
        exit;
    } else {
        $updateError = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Get the book details for editing
$bookId = intval($_GET['id']);
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Edit Book</h1>
        <?php if (isset($updateSuccess)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($updateSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php elseif (isset($updateError)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($updateError, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" id="title" value="<?php echo htmlspecialchars($book['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="text" name="year" class="form-control" id="year" value="<?php echo htmlspecialchars($book['year'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description" rows="3"><?php echo htmlspecialchars($book['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <select name="department" class="form-select" id="department" required>
                    <option value="">Select Department</option>
                    <option value="Computer" <?php if ($book['department'] == 'Computer') echo 'selected'; ?>>Computer</option>
                    <option value="Mechanical" <?php if ($book['department'] == 'Mechanical') echo 'selected'; ?>>Mechanical</option>
                    <option value="Civil" <?php if ($book['department'] == 'Civil') echo 'selected'; ?>>Civil</option>
                    <option value="Electrical" <?php if ($book['department'] == 'Electrical') echo 'selected'; ?>>Electrical</option>
                    <option value="Entc" <?php if ($book['department'] == 'Entc') echo 'selected'; ?>>ENTC</option>
                    <option value="Polymer" <?php if ($book['department'] == 'Polymer') echo 'selected'; ?>>Polymer</option>
                    <option value="IDD" <?php if ($book['department'] == 'IDD') echo 'selected'; ?>>IDD</option>
                    <option value="DDGM" <?php if ($book['department'] == 'DDGM') echo 'selected'; ?>>DDGM</option>
                    <option value="Automobile" <?php if ($book['department'] == 'Automobile') echo 'selected'; ?>>Automobile</option>
                    <option value="Mechatronics" <?php if ($book['department'] == 'Mechatronics') echo 'selected'; ?>>Mechatronics</option>
                    <option value="IT" <?php if ($book['department'] == 'IT') echo 'selected'; ?>>IT</option>
                    <!-- Add other options as needed -->
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
