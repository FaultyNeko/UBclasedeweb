<?php
// getNames.php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "names"; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Get the 'q' parameter
$query = isset($_GET['q']) ? $_GET['q'] : '';
$query = $conn->real_escape_string($query);

// Since the only column is called `COL 1`
$sql = "SELECT `COL 1` AS fullname
        FROM mock_data
        WHERE `COL 1` LIKE '$query%'
        LIMIT 10";

$result = $conn->query($sql);

$names = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        //the column as "fullname"
        $names[] = $row['fullname'];
    }
}

$conn->close();

echo json_encode($names);
?>
