<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maidname = $_POST['username'];
    $mobile = $_POST['mobile'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "maidproject";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
        die();
    }

    // Fetch items from the database table
    $stmt = $conn->prepare("SELECT * FROM maids WHERE full_name = ? AND phone = ?");
    $stmt->bind_param("ss", $maidname, $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows ==1) {
        $row = $result->fetch_assoc();
        $photoFileName = $row['Photo'];
        $imagePath = '../images/' . $photoFileName;
        $maidfullName=$row['full_name'] ;
        $maidno=$row['phone'];
        session_start();
        $_SESSION['maidname']=$maidfullName;
        $_SESSION['maidno']=$maidno;
        $_SESSION['photo']=$imagePath;
        header("Location: maidworks.php");
        exit();
    } else {
        echo "No results found.";
        header("Location: maidloginForm.html");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
