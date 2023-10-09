<?php
session_start();
if (!empty($_SESSION['maidname'])) {
    $name = $_SESSION['maidname'];
    
    if (isset($_POST['remove_item']) && !empty($_POST['item_name'])) {
        // Replace the following with your database connection code
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "maidjobs";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        try {
            // Prepare and execute the SQL statement to remove the item from the database
            $item_name = $_POST['item_name'];
            $stmt = $conn->prepare("DELETE FROM $name WHERE Name = ?");
            $stmt->bind_param('s', $item_name);
            $stmt->execute();
            header("Location: ../index.html");
            exit();
            
        } catch (Exception $e) {
            $response = array("status" => "error", "message" => "Error: " . $e->getMessage());
            echo json_encode($response);
        }

        $stmt->close();
        $conn->close();
    } else {
        $response = array("status" => "error", "message" => "Missing or invalid item name.");
        echo json_encode($response);
    }
} else {
    $response = array("status" => "error", "message" => "Session data not found.");
    echo json_encode($response);
}
?>
