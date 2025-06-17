<?php
$servername = "localhost";
$username   = "root";
$password   = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection to MySQL failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$dbSql = "CREATE DATABASE IF NOT EXISTS simpsons";
if (!$conn->query($dbSql)) {
    die("Error creating 'simpsons' database: " . $conn->error);
}

$conn->select_db("simpsons");

// Create the reservation table if it doesn't exist
$tableQuery = "CREATE TABLE IF NOT EXISTS reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    country_code VARCHAR(10), 
    city VARCHAR(100),         
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($tableQuery)) {
    die("Error creating 'reservation' table: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $conn->real_escape_string(trim($_POST['name']));
    $email       = $conn->real_escape_string(trim($_POST['email']));
    $roomType    = $conn->real_escape_string(trim($_POST['room_type']));
    $checkin     = $conn->real_escape_string(trim($_POST['checkin']));
    $checkout    = $conn->real_escape_string(trim($_POST['checkout']));
    $countryCode = isset($_POST['country']) ? $conn->real_escape_string(trim($_POST['country'])) : '';
    $city        = isset($_POST['city'])    ? $conn->real_escape_string(trim($_POST['city']))    : '';

    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        die("Invalid name format.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }
    if (empty($roomType) || empty($checkin) || empty($checkout)) {
        die("Please fill out all required fields (room type, checkin, checkout).");
    }

    $sql = "INSERT INTO reservation (name, email, room_type, checkin, checkout, country_code, city)
            VALUES ('$name', '$email', '$roomType', '$checkin', '$checkout', '$countryCode', '$city')";
    
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $result  = $conn->query("SELECT * FROM reservation WHERE id = $last_id");
        $reservation = $result->fetch_assoc();

        // Check if the request is made via Ajax
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => "success",
                "reservation" => $reservation
            ));
        } else {
            // Normal non-Ajax request: display full HTML confirmation page
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Reservation Confirmation</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="css/bootstrap.min.css">
                <link rel="stylesheet" href="css/styles.css">
            </head>
            <body>
                <div class="container mt-5">
                    <h1>Reservation Confirmed</h1>
                    <p>Thank you, <?php echo htmlspecialchars($reservation['name']); ?>, for your reservation.</p>
                    <ul>
                        <li>Email: <?php echo htmlspecialchars($reservation['email']); ?></li>
                        <li>Room Type: <?php echo htmlspecialchars($reservation['room_type']); ?></li>
                        <li>Check-In Date: <?php echo htmlspecialchars($reservation['checkin']); ?></li>
                        <li>Check-Out Date: <?php echo htmlspecialchars($reservation['checkout']); ?></li>
                        <?php if (!empty($reservation['country_code'])) : ?>
                            <li>Country Code: <?php echo htmlspecialchars($reservation['country_code']); ?></li>
                        <?php endif; ?>
                        <?php if (!empty($reservation['city'])) : ?>
                            <li>City: <?php echo htmlspecialchars($reservation['city']); ?></li>
                        <?php endif; ?>
                    </ul>
                    <a href="index.php" class="btn btn-primary">Make Another Reservation</a>
                </div>
                <script src="js/jquery.min.js"></script>
                <script src="js/bootstrap.bundle.min.js"></script>
            </body>
            </html>
            <?php
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
