<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection settings
    $host = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "maidproject"; // Replace with your actual database name

    // Create a database connection using PDO (PHP Data Objects)
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the form data
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $address = $_POST["address"];
        $mobile = $_POST["mobileNumber"]; // Use "mobile" instead of "mobileNumber"

        // Query the database to check if the email already exists
        $stmt = $conn->prepare("SELECT * FROM user WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if a user with the entered email already exists
        if ($stmt->rowCount() > 0) {
                header("Location: register1.html");
                exit();
        } else {
            // Email is unique, proceed with data insertion
            // SQL query to insert data into the "user" table using a prepared statement
            $stmt = $conn->prepare("INSERT INTO user (Name, Email, Password, Address, Mobile) 
                                VALUES (:name, :email, :password, :address, :mobile)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':mobile', $mobile); // Use "mobile" instead of "mobileNumber"
            if ($stmt->execute()) {
                session_start();
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['address'] = $address;
            $_SESSION['mobileno'] = $mobile;
                header("Location: login.html");
                exit();
            } else {
                header("Location: register1.html");
                exit();
            }
        }

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
