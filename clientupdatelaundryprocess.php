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
    $laundryid = $_POST['laundryid'];
    $weight = $_POST['weight'];
    $soap = $_POST['soap'];
    $quantity = $_POST['quantity'];
    $cost = $_POST['cost'];

    // Update the laundry data in the database
    $sql = "UPDATE laundrydata SET weight=?, soap=?, quantity=?, cost=? WHERE laundryid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $weight, $soap, $quantity, $cost, $laundryid);

    if ($stmt->execute()) {
        // Redirect to a success page or handle success message
        echo "<script>alert('Laundry updated successfully.');</script>";
        echo "<script>window.location.href='view_laundry.php';</script>";
        exit();
    } else {
        // Display error message
        echo "<script>alert('Error updating laundry: " . $conn->error . "');</script>";
    }
}
?>
