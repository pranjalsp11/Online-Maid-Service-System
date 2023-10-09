<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $fullName = $_POST["full-name"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $experience = $_POST["experience"];
    $skills = $_POST["skills"];
    $availability = $_POST["availability"];
    $languagePreference = $_POST["language-preference"];
    $salaryExpectation = $_POST["salary-expectation"];
    $backgroundCheck = isset($_POST["background-check"]) ? 1 : 0;
    $additionalPreferences = $_POST["additional-preferences"];
    $fileName=$_FILES["photo"]["name"];

    // Database connection settings
    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "maidproject";
    $dbName2 = "maidjobs";

    // Create a database connection
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to insert data into the database
    $sql = "INSERT INTO maids (full_name, phone, address, Photo, experience, skills, availability, language_preference, salary_expectation, background_check, additional_preferences)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssis",
        $fullName,
        $phone,
        $address,
        $fileName,
        $experience,
        $skills,
        $availability,
        $languagePreference,
        $salaryExpectation,
        $backgroundCheck,
        $additionalPreferences
    );
    if ($stmt->execute()) {
        // Close the statement here, outside the if-else block
        $stmt->close();

        // Create a new database connection for the second database
        $conn2 = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName2);

        // Check the second database connection
        if ($conn2->connect_error) {
            die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "
        CREATE TABLE `" . $conn2->real_escape_string($fullName) . "` (
            Name VARCHAR(100) NOT NULL,
            MobileNo VARCHAR(15) NOT NULL,
            Address VARCHAR(200) NOT NULL,
            ServiceType VARCHAR(50) NOT NULL
        )
    ";

    if ($conn2->query($sql2)) {
        // Move the uploaded file
        move_uploaded_file($_FILES["photo"]["tmp_name"], $fileName);
        header("Location: ../index.html");
        exit();
    } else {
        // If there's an error creating the table, handle it accordingly
        header("Location: maid.html");
        exit();

    }
    

    $conn2->close();
} else {
    header("Location: maid.html");
    exit();
}

// Close the first database connection
$conn->close();
}
?>
