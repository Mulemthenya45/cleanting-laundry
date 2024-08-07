<?php
// Start the session
session_start();

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

// Update the status of completed laundry to "paid"
$clientid = $_SESSION['clientid'];

// Update laundry status
$updateLaundryStatusSql = "UPDATE laundrydata SET status = 'paid' WHERE clientid = $clientid AND status = 'completed'";
if ($conn->query($updateLaundryStatusSql) !== TRUE) {
    echo "Error updating laundry status: " . $conn->error;
}

// Update dry cleaning status
$updateDryCleaningStatusSql = "UPDATE drycleaningdata SET status = 'paid' WHERE clientid = $clientid AND status = 'completed'";
if ($conn->query($updateDryCleaningStatusSql) !== TRUE) {
    echo "Error updating dry cleaning status: " . $conn->error;
}

// Update spot cleaning status
$updateSpotCleaningStatusSql = "UPDATE spot_cleaning_data SET status = 'paid' WHERE clientid = $clientid AND status = 'completed'";
if ($conn->query($updateSpotCleaningStatusSql) !== TRUE) {
    echo "Error updating spot cleaning status: " . $conn->error;
}

// Close the database connection
$conn->close();

// Redirect to userhome.php
header("Location: userhome.php");
exit();
?>
