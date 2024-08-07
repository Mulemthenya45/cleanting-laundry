<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laundry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $secondname = $_POST["secondname"];
    $enteredPassword = $_POST["password"];

    // Query to retrieve hashed password from the database
    $sql = "SELECT password FROM admincredentials WHERE firstname = '$firstname' AND secondname = '$secondname'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPasswordFromDB = $row["password"];

        // Verify the entered password with the hashed password from the database
        if (password_verify($enteredPassword, $hashedPasswordFromDB)) {
            // Admin credentials are correct, set session variables and redirect
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_firstname'] = $firstname;
            $_SESSION['admin_secondname'] = $secondname;
            header("Location: admindash.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "Invalid credentials. Please try again.";
        }
    } else {
        echo "Invalid credentials. Please try again.";
    }
}

$conn->close();
?>
