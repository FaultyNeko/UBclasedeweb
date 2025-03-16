<?php
// getCities.php
header('Content-Type: application/json');

// Connect to world DB
$conn = new mysqli("localhost", "root", "", "world");
if ($conn->connect_error) {
    die(json_encode([])); // return empty array if error
}

// Grab the country code from the query string
$countryCode = isset($_GET['countryCode']) ? $_GET['countryCode'] : '';
$countryCode = $conn->real_escape_string($countryCode);

// Query the 'cities' table to find rows that match this country code
$sql = "SELECT Name FROM cities WHERE country_code = '$countryCode' ORDER BY Name LIMIT 50";
$result = $conn->query($sql);

$cities = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['Name'];
    }
}

// Output as JSON
echo json_encode($cities);

$conn->close();
