<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    // Redirect to the sign-in page if not logged in
    header("Location: signin.php");
    exit();
}

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
    // Retrieve form data
    $spotcleaningid = $_POST['spotcleaningid'];
    $stainSize = $_POST['stainSize'];
    $quantity = $_POST['quantity'];
    $cost = $_POST['cost'];

    // Update the spot cleaning data in the database
    $sql = "UPDATE spot_cleaning_data SET stainSize=?, quantity=?, cost=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $stainSize, $quantity, $cost, $spotcleaningid);

    if ($stmt->execute()) {
        // Redirect to a success page or handle success message
        echo "<script>alert('Spot cleaning updated successfully.');</script>";
        echo "<script>window.location.href='view_laundry.php';</script>";
        exit();
    } else {
        // Display error message
        echo "<script>alert('Error updating spot cleaning: " . $conn->error . "');</script>";
    }
}
?>
