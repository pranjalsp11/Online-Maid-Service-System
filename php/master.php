
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["name"])) {
    // Database connection settings
    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "maidproject";
    $dbName2 = "maidjobs";
    session_start();
    $username=$_SESSION['username'];
    $username=$_SESSION['cusName'];
         $mobileNo=$_SESSION['cusMobile'];
         $address=$_SESSION['cusAddress'];
          $serviceType=$_SESSION['cusServiceType'];
    $maidName = urldecode($_GET["name"]);

    // Create a database connection for maidproject database
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve maid data from the database based on the maid's name
    $sql = "SELECT * FROM maids WHERE full_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $maidName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows >= 1) {
        // Get the maid's information
        $maidInfo = $result->fetch_assoc();
        $name = $maidInfo['full_name'];
         // Replace this with the actual service type or any other relevant method to get the service type.
        // Create a database connection for maidjobs database
        $conn2 = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName2);
        if ($conn2->connect_error) {
            die("Connection failed: " . $conn2->connect_error);
        }

        // Prepare and execute the SQL query to insert data into the database
        $sql2 = "INSERT INTO `$name` (Name, MobileNo, Address, ServiceType) VALUES (?, ?, ?, ?)";
        $stmt2 = $conn2->prepare($sql2);
        $stmt2->bind_param("ssss", $username, $mobileNo, $address, $serviceType);

        if ($stmt2->execute()) {
            header("Location: ../index.html");
            exit();
            
        } else {
            header("Location: master.php");
            exit();
            
        }

        // Close the statement and connection for maidjobs database
        $stmt2->close();
        $conn2->close();
    } else {
        echo "Maid not found.";
    }

    // Close the statement and connection for maidproject database
    $stmt->close();
    $conn->close();
} else {
    // Redirect to view_maids.php if the maid name is not provided
    header("Location: view_maids.php");
    exit();
}
?>
