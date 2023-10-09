<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is a login request
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Replace the database connection details with your own
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $database = "maidproject";

        // Create a connection
        $conn = new mysqli($servername, $username_db, $password_db, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT password FROM user WHERE Name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $row= $stmt->fetch();
        // Verify the password
        if (hash_equals($hashedPassword, crypt($password, $hashedPassword))) {
            // Password is correct, create a session and redirect to a secure page
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
        // Check if "Remember Me" checkbox is checked
         if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            // Set cookies for username and hashed password (You can customize the cookie name and expiry time)
            $expiry = time() + (30 * 24 * 60 * 60); // Set cookie expiry to 30 days from now
            setcookie('remember_username', $username, $expiry, '/');
            setcookie('remember_password', $hashedPassword, $expiry, '/');
        } else {
            // Unset cookies if "Remember Me" is not checked (optional)
            setcookie('remember_username', '', time() - 3600, '/');
            setcookie('remember_password', '', time() - 3600, '/');
        }
            header("Location: ../index.html");
            exit();
        } else {
            // Password is incorrect, show an error message
            echo "Invalid username or password.";
            header("Location: login.html");
            exit();
        }

        // Close the connection
        $stmt->close();
        $conn->close();
        header("Location: register1.html");
        exit();
    
}
?>
