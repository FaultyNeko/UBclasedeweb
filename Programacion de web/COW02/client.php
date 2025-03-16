<?php
// client.php - Processes the reservation form submission

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim form data
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $roomType = trim($_POST['room_type']);
    $checkin  = trim($_POST['checkin']);
    $checkout = trim($_POST['checkout']);

    // Basic validation
    $isNameValid = preg_match("/^[a-zA-Z\s]+$/", $name);
    $isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
    $areDatesValid = !empty($checkin) && !empty($checkout);

    if ($isNameValid && $isEmailValid && !empty($roomType) && $areDatesValid) {
        // Data is valid; in a real scenario, you would insert the data into a database.
        // For demonstration, we display a confirmation page.
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
                <p>Thank you, <?php echo htmlspecialchars($name); ?>, for your reservation.</p>
                <ul>
                    <li>Email: <?php echo htmlspecialchars($email); ?></li>
                    <li>Room Type: <?php echo htmlspecialchars($roomType); ?></li>
                    <li>Check-In Date: <?php echo htmlspecialchars($checkin); ?></li>
                    <li>Check-Out Date: <?php echo htmlspecialchars($checkout); ?></li>
                </ul>
                <a href="index.php" class="btn btn-primary">Make Another Reservation</a>
            </div>
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "There was an error with your reservation. Please ensure all fields are correctly filled.";
    }
} else {
    echo "Invalid request method.";
}
?>
