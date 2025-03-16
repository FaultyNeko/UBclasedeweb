<?php
// index.php
// 1) Connect to 'world' DB to load countries
$worldConn = new mysqli("localhost", "root", "", "world");
if ($worldConn->connect_error) {
    die("Connection to world DB failed: " . $worldConn->connect_error);
}

// 2) Build <option> tags for countries
$countrySql = "SELECT Name, Code FROM countries ORDER BY Name";
$countryResult = $worldConn->query($countrySql);

$countryOptions = "";
if ($countryResult && $countryResult->num_rows > 0) {
    while ($row = $countryResult->fetch_assoc()) {
        // Suppose countries table has columns 'Name' and 'Code'
        $cName = htmlspecialchars($row['Name']);
        // We'll store the country name in the option's value
        $countryOptions .= "<option value=\"$cName\">$cName</option>";
    }
}
$worldConn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hotel Reservations - With Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS (local copy) -->
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css" />
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
      <button id="sidebarToggle">Menu</button>
    </div>

    <!-- SIDEBAR (with Login Form) -->
    <nav id="sidebar">
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
        <h1 class="mb-4">our scams... i mean rooms, yes rooms >.></h1>
        <div class="row">
          <!-- Room Card: Single Room -->
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="room1.jpg" class="card-img-top" alt="Single Room">
              <div class="card-body">
                <h5 class="card-title">Rooftop Living</h5>
                <p class="card-text">Location: Some Random Building's Rooftop</p>
                <p class="card-text">Price: Half your kidney and 1 lung</p>
                <a href="#" class="btn btn-primary" onclick="displayBookingInfo('Single Room')">Book Now</a>
              </div>
            </div>
          </div>
          <!-- Room Card: Double Room -->
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="room2.jpg" class="card-img-top" alt="Double Room">
              <div class="card-body">
                <h5 class="card-title">Fancy Hobo</h5>
                <p class="card-text">Location: Da Pool</p>
                <p class="card-text">Price: one half eaten hot dog</p>
                <a href="#" class="btn btn-primary" onclick="displayBookingInfo('Double Room')">Book Now</a>
              </div>
            </div>
          </div>
          <!-- Room Card: Suite Room -->
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="room3.jpg" class="card-img-top" alt="Suite Room">
              <div class="card-body">
                <h5 class="card-title">Lé Suite</h5>
                <p class="card-text">Location: Penthouse Suite</p>
                <p class="card-text">Price: Your soul &gt;:D</p>
                <a href="#" class="btn btn-primary" onclick="displayBookingInfo('Suite Room')">Book Now</a>
              </div>
            </div>
          </div>
        </div>
        <!-- (room cards code) -->
        
        <section class="reservation-form" id="reservationFormContainer">
          <h2>Book Your Room</h2>
          <!-- STEP 2: Add a 'city' dropdown to the reservation form -->
          <form id="reservationForm" action="server.php" method="POST">
            <div class="form-group">
              <label for="name">Full Name:</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label for="email">Email Address:</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Your email" required>
            </div>
            <div class="form-group">
              <label for="room-type">Room Type:</label>
              <select class="form-control" id="room-type" name="room_type" required>
                <option value="">Select a room type</option>
                <option value="Single Room">Single Room</option>
                <option value="Double Room">Double Room</option>
                <option value="Suite Room">Suite Room</option>
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
            
            <!-- NEW: City Dropdown (Populated from world DB) -->
            <div class="form-group">
              <label for="city">City:</label>
              <select class="form-control" id="city" name="city" required>
                <option value="">Select a city</option>
                <?php echo $cityOptions; ?>
              </select>
            </div>
            
            <button type="submit" class="btn btn-success">Book Now</button>
          </form>
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
  <script src="js/bootstrap.bundle.min.js"></script>
  <script>
    // Hide reservation form initially
    document.getElementById('reservationFormContainer').style.display = 'none';
    
    function displayBookingInfo(roomType) {
      document.getElementById('reservationFormContainer').style.display = 'block';
      document.getElementById('room-type').value = roomType;
      document.getElementById('reservationFormContainer').scrollIntoView({ behavior: 'smooth' });
    }
    
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Login form submitted! (No backend processing)');
    });
    
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.body.classList.toggle('active-sidebar');
    });
  </script>
</body>
</html>
