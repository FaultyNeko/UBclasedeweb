<?php
// Check if a room type is passed via the query string (fallback for no-JS mode)
$selectedRoom = "";
if (isset($_GET['room'])) {
    $selectedRoom = $_GET['room'];
}

$worldConn = new mysqli("localhost", "root", "", "world");
if ($worldConn->connect_error) {
    die("Connection failed: " . $worldConn->connect_error);
}

$countrySql = "SELECT Code, Name FROM countries ORDER BY Name";
$countryResult = $worldConn->query($countrySql);

$countryOptions = "";
if ($countryResult && $countryResult->num_rows > 0) {
    while ($row = $countryResult->fetch_assoc()) {
        $cCode = htmlspecialchars($row['Code']);
        $cName = htmlspecialchars($row['Name']);
        $countryOptions .= "<option value=\"$cCode\">$cName</option>";
    }
}
$worldConn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hotel Reservations - With Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap and custom styles -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/styles.css">
  
  <!-- Load fallback CSS if JavaScript is disabled -->
  <noscript>
    <link rel="stylesheet" href="css/noscript.css">
  </noscript>
  
  <!-- Load Prototype and Scriptaculous first -->
  <script src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js?load=effects"></script>
  
  <!-- Your custom JS that uses Prototype -->
  <script src="js/main.js"></script>
</head>
<body>
  <div id="page-container">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
      <a class="navbar-brand" href="#">Jimbobs Fake Mustache &amp; Hotel Emporium</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent"
              aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#">Rooms</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="GET" action="https://www.google.com/search">
          <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search Google" aria-label="Google Search">
          <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Google</button>
        </form>
        <form class="form-inline my-2 my-lg-0 ml-2" method="GET" action="https://www.wikipedia.org/wiki/Special:Search">
          <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search Wikipedia" aria-label="Wikipedia Search">
          <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Wiki</button>
        </form>
      </div>
    </nav>
    
    <!-- SIDEBAR TOGGLE BUTTON -->
    <div id="sidebarToggleContainer">
      <!-- In no-JS mode, this link uses the fragment identifier to toggle the sidebar -->
      <a href="#sidebar" id="sidebarToggle" class="btn btn-primary">Menu</a>
    </div>
    
    <!-- SIDEBAR -->
    <nav id="sidebar">
      <!-- Fallback close link for no-JS mode -->
      <a href="#" id="closeSidebar" class="btn btn-secondary">Close</a>
      
      <div class="login-container">
        <h3>Login</h3>
        <form id="loginForm">
          <div class="form-group">
            <label for="login-username">Username:</label>
            <input type="text" id="login-username" name="username" placeholder="Username" required>
          </div>
          <div class="form-group">
            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="password" placeholder="Password" required>
          </div>
          <button type="submit">Login</button>
        </form>
      </div>
      <h3 class="mt-4">Sidebar Menu</h3>
      <ul>
        <li><a href="https://www.google.com" target="_blank">Google</a></li>
        <li><a href="https://www.wikipedia.org" target="_blank">Wikipedia</a></li>
      </ul>
    </nav>
    
    <!-- MAIN CONTENT -->
    <div id="content-wrap">
      <div class="container mt-5">
        <h1 class="mb-4">Our Rooms</h1>
        <div class="row">
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="room1.jpg" class="card-img-top" alt="Single Room">
              <div class="card-body">
                <h5 class="card-title">Rooftop Living</h5>
                <p class="card-text">Location: Some Random Building's Rooftop</p>
                <p class="card-text">Price: Half your kidney and 1 lung</p>
                <!-- Updated Book Now link with fallback query parameter -->
                <a href="index.php?room=Single+Room#reservationFormContainer" 
                   class="btn btn-primary" 
                   onclick="displayBookingInfo('Single Room'); return false;">
                  Book Now
                </a>
              </div>
            </div>
          </div>
          <!-- Repeat for other room cards with corresponding room type -->
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="room2.jpg" class="card-img-top" alt="Double Room">
              <div class="card-body">
                <h5 class="card-title">Fancy Hobo</h5>
                <p class="card-text">Location: Da Pool</p>
                <p class="card-text">Price: one half eaten hot dog</p>
                <a href="index.php?room=Double+Room#reservationFormContainer" 
                   class="btn btn-primary" 
                   onclick="displayBookingInfo('Double Room'); return false;">
                  Book Now
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="room3.jpg" class="card-img-top" alt="Suite Room">
              <div class="card-body">
                <h5 class="card-title">Lé Suite</h5>
                <p class="card-text">Location: Penthouse Suite</p>
                <p class="card-text">Price: Your soul &gt;:D</p>
                <a href="index.php?room=Suite+Room#reservationFormContainer" 
                   class="btn btn-primary" 
                   onclick="displayBookingInfo('Suite Room'); return false;">
                  Book Now
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Reservation Form; pre-display if a room is selected via fallback -->
        <section class="reservation-form" id="reservationFormContainer" style="<?php echo ($selectedRoom !== '') ? 'display:block;' : 'display:none;'; ?>">
          <h2>Book Your Room</h2>
          <form id="reservationForm" action="server.php" method="POST">
            <div class="form-group" style="position: relative;">
              <label for="name">Full Name:</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
              <div id="nameAutocomplete" class="dropdown-menu" style="display: none;"></div>
            </div>
            <div class="form-group">
              <label for="email">Email Address:</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Your email" required>
            </div>
            <div class="form-group">
              <label for="room-type">Room Type:</label>
              <select class="form-control" id="room-type" name="room_type" required>
                <option value="">Select a room type</option>
                <option value="Single Room" <?php if ($selectedRoom === 'Single Room') echo 'selected'; ?>>Single Room</option>
                <option value="Double Room" <?php if ($selectedRoom === 'Double Room') echo 'selected'; ?>>Double Room</option>
                <option value="Suite Room" <?php if ($selectedRoom === 'Suite Room') echo 'selected'; ?>>Suite Room</option>
              </select>
            </div>
            <div class="form-group">
              <label for="checkin">Check-In Date:</label>
              <input type="date" class="form-control" id="checkin" name="checkin" required>
            </div>
            <div class="form-group">
              <label for="checkout">Check-Out Date:</label>
              <input type="date" class="form-control" id="checkout" name="checkout" required>
            </div>
            <div class="form-group">
              <label for="country">Country:</label>
              <select class="form-control" id="country" name="country" required>
                <option value="">Select a country</option>
                <?php echo $countryOptions; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="city">City:</label>
              <select class="form-control" id="city" name="city" required>
                <option value="">-- Select a country first --</option>
              </select>
              <div id="loadingBarContainer" style="display:none; margin-bottom:10px;">
                <div id="loadingBar" style="background: green; width: 0%; height: 20px;"></div>
                <div id="loadingTip" style="margin-top: 5px; font-size: 12px; color: #555;"></div>
              </div>
            </div>
            <button type="submit" class="btn btn-success">Book Now</button>
          </form>
        </section>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; Jimbobs Fake Mustache &amp; Hotel Emporium. No refunds.</p>
  </footer>

  <script src="js/jquery.min.js"></script>
  <script>
    // Release the $ so that Prototype’s $ remains intact
    jQuery.noConflict();
  </script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <!-- Reservation Confirmation Modal -->
  <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reservationModalLabel">Reservation Confirmed</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Hotel Image at the top -->
          <div id="modalHotelImage" style="text-align: center; margin-bottom: 20px;">
            <img src="" alt="Hotel Image" style="max-width: 100%; height: auto;">
          </div>
          <!-- Reservation details go here -->
          <div id="modalReservationDetails"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
