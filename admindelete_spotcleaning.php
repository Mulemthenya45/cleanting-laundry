<?php
session_start();

// Check if the user is logged in


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
    $spotcleaningid = $_POST['spotcleaningid'];

    // Prepare and execute SQL to delete the laundry row
    $sql = "DELETE FROM spot_cleaning_data WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $spotcleaningid);

    if ($stmt->execute()) {
        // Redirect to the page where the user views laundry data
        $_SESSION['success_message'] = "spot data deleted successfully.";
        header("Location: laundryorders.php");
        exit();
    } else {
        // Redirect to an error page or handle the error accordingly
        $_SESSION['error_message'] = "Error deleting spot data: " . $conn->error;
        header("Location: view_laundry.php");
        exit();
    }
} else {
    // Redirect to an error page or handle the error accordingly
    $_SESSION['error_message'] = "Error: Invalid request method.";
    header("Location:laundryorders.php");
    exit();
}
?>
