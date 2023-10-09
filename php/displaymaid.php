<!-- view_maids.php -->
<!DOCTYPE html>
<html>
<head>
  <title>Maid Descriptions</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    
    img {
      max-width: 200px;
      height: auto;
      display: block;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Maid Descriptions</h2>
    <div class="row">
      <?php
      // ... (the PHP code to fetch data from the database remains the same) ...
      $dbHost = "localhost";
      $dbUsername = "root";
      $dbPassword = "";
      $dbName = "maidproject";

      // Create a database connection
      $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve maid data from the database
    $sql = "SELECT * FROM maids";
    $result = $conn->query($sql);
   

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
       
          $photoFileName = $row['Photo'];
          $imagePath = '../images/' . $photoFileName;
          $fullName = $row['full_name'];
          // Display the maid details with their photos
          echo '<div class="col-md-4 mb-4">';
          echo '  <div class="card">';
          echo '    <div class="card-body d-flex justify-content-center align-items-center">';
          echo '    <img src="' . $imagePath . '" alt="Maid Photo" class="card-img-top">';
          echo '    </div>';
          echo '    <div class="card-body">';
          echo '      <h5 class="card-title">' . $row['full_name'] . '</h5>';
          echo '      <p class="card-text">Phone: ' . $row['phone'] . '</p>';
          echo '      <p class="card-text">Address: ' . $row['address'] . '</p>';
          // Add more maid details here
  
          // Add the "Book Maid" button with a link to the booking form
          // The link will include the maid's ID as a parameter to identify the maid being booked
          echo '      <a href="master.php?name=' . urlencode($fullName) . '" class="btn btn-primary">Book Maid</a>';
  
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
      }
      } else {
          echo "No maids registered.";
      }

      // ... (the rest of the PHP code remains the same) ...
      ?>
    </div>
  </div>
</body>
</html>
