<?php
// server.php: Processes reservation data and stores it in a MySQL database

// Database configuration (adjust these values to match your XAMPP setup)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "hotel";  // Make sure this database is created in PhpMyAdmin

// Create a new connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the reservations table if it doesn't exist
$tableQuery = "CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($tableQuery)) {
    die("Error creating table: " . $conn->error);
}

// Process form data if method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $name     = $conn->real_escape_string(trim($_POST['name']));
    $email    = $conn->real_escape_string(trim($_POST['email']));
    $roomType = $conn->real_escape_string(trim($_POST['room_type']));
    $checkin  = $conn->real_escape_string(trim($_POST['checkin']));
    $checkout = $conn->real_escape_string(trim($_POST['checkout']));
    
    // Basic validation (you can add more as needed)
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        die("Invalid name format.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }
    if (empty($roomType) || empty($checkin) || empty($checkout)) {
        die("Please fill out all required fields.");
    }
    
    // Insert data into the reservations table
    $sql = "INSERT INTO reservations (name, email, room_type, checkin, checkout)
            VALUES ('$name', '$email', '$roomType', '$checkin', '$checkout')";
    
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted ID and fetch the reservation
        $last_id = $conn->insert_id;
        $result = $conn->query("SELECT * FROM reservations WHERE id = $last_id");
        $reservation = $result->fetch_assoc();
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
                </ul>
                <a href="index.php" class="btn btn-primary">Make Another Reservation</a>
            </div>
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
