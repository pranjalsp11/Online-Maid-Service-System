<?php
// Start the session to retrieve profile information
session_start();

// Verify that session variables are set
if (isset($_SESSION['maidname'], $_SESSION['maidno'], $_SESSION['photo'])) {
    $name = $_SESSION['maidname'];
    $maidno = $_SESSION['maidno'];
    $photo = $_SESSION['photo'];

    // Replace this part with the code to fetch cart items from the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "maidjobs";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch cart items from the database using prepared statements
    $stmt = $conn->prepare("SELECT Name, MobileNo, Address, ServiceType FROM $name");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cart_items = array();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = array(
            'item_name' => $row['Name'],
            'mobileNo' =>  $row['MobileNo'],
            'address' => $row['Address'],
            'serviceType' => $row['ServiceType']
        );
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Session variables not set!";
}

  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .profile {
            text-align: center;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        /* Additional CSS for the cart table */
        .cart {
            margin-top: 20px;
        }
        .cart h2 {
            margin-bottom: 15px;
        }
        .cart table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart th,
        .cart td {
            padding: 10px;
            text-align: center;
        }
        .cart th {
            background-color: #f2f2f2;
        }
        .cart tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="profile">
            <img src="<?php echo $photo ?>" alt="Profile Picture">
            <h2>Name: <?php echo $name ?></h2>
            <p>Number: <?php echo $maidno ?></p>
            <!-- You can add more profile information here -->
        </div>

        <!-- Cart Table -->
        <div class="cart">
            <h2>Works</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                        <th>Service Type</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cart_items as $item) { ?>
    <tr>
        <td><?php echo $item['item_name']; ?></td>
        <td><?php echo $item['mobileNo']; ?></td>
        <td><?php echo $item['address']; ?></td>
        <td><?php echo $item['serviceType']; ?></td>
        <td><form action="completejob.php" method="post">
                    <input type="hidden" name="item_name" value="<?php echo $item['item_name']; ?>">
                    <button type="submit" name="remove_item" class="btn btn-danger">Remove</button>
                </form></td>
    </tr>
<?php } ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
</body>
</html>

