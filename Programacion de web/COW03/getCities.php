<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "world");
if ($conn->connect_error) {
    die(json_encode([]));
}

$countryCode = isset($_GET['countryCode']) ? $_GET['countryCode'] : '';
$countryCode = $conn->real_escape_string($countryCode);

$sql = "SELECT Name FROM cities WHERE country_code = '$countryCode' ORDER BY Name";
$result = $conn->query($sql);

$cities = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['Name'];
    }
}
echo json_encode($cities);

$conn->close();
