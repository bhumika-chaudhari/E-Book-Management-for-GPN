<?php
// Database connection
$host = 'localhost';
$db = "librabry_db";
$port = 3307; // Update with your database name
$user = 'root'; // Update with your DB username
$password = ''; // Update with your DB password

$conn = new mysqli($host, $user, $password, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// List of department-specific tables
$departmentTables = [
    'notes_auto' => 'Automobile Engineering',
    'notes_civil' => 'Civil Engineering',
    'notes_computer' => 'Computer Technology',
    'notes_ddgm' => 'Dress Designing Engineering',
    'notes_electrical' => 'Electrical Engineering',
    'notes_entc' => 'Electronics and Telecommunication',
    'notes_idd' => 'Interior Designing Engineering',
    'notes_if' => 'Information Technology',
    'notes_mechanical' => 'Mechanical Engineering',
    'notes_mect' => 'Mechatronics Engineering',
    'notes_poly' => 'Polymer Engineering'
];

// Initialize totals
$totalNotes = 0;
$notesPerDepartment = [];

// Query each department table
foreach ($departmentTables as $table => $departmentName) {
    $query = "SELECT COUNT(*) AS total_notes FROM $table";
    $result = $conn->query($query);
    
    if ($result) {
        $count = $result->fetch_assoc()['total_notes'];
        $totalNotes += $count; // Add to the total count
        
        // Add to department-wise notes array
        $notesPerDepartment[] = [
            'department_name' => $departmentName,
            'total_notes' => $count
        ];
    } else {
        // Handle SQL errors
        echo "Error querying $table: " . $conn->error;
    }
}

// Prepare the data to return as JSON
$response = [
    'totalNotes' => $totalNotes,
    'notesPerDepartment' => $notesPerDepartment
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
