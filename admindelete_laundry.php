<?php

// Your database connection code goes here
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

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve laundryid from POST data
    $laundryid = $_POST['laundryid'];

    // Prepare and execute SQL to delete the laundry row
    $sql = "DELETE FROM laundrydata WHERE laundryid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $laundryid);

    if ($stmt->execute()) {
        // Redirect to the page where the user views laundry data
        $_SESSION['success_message'] = "Laundry data deleted successfully.";
        header("Location: laundryorders.php");
        exit();
    } else {
        // Redirect to an error page or handle the error accordingly
        $_SESSION['error_message'] = "Error deleting laundry data: " . $conn->error;
        header("Location: laundryorders.php");
        exit();
    }
} else {
    // Redirect to an error page or handle the error accordingly
    $_SESSION['error_message'] = "Error: Invalid request method.";
    header("Location: view_laundry.php");
    exit();
}
?>
