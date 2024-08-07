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

// Check if the form data is received
if(isset($_POST['spotcleaningid']) && isset($_POST['status'])) {
    // Sanitize input to prevent SQL injection
    $spotcleaningid = mysqli_real_escape_string($conn, $_POST['spotcleaningid']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Prepare SQL statement to update the status
    $sql = "UPDATE spot_cleaning_data SET status = '$status' WHERE id = '$spotcleaningid'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Status updated successfully
        echo "<script>alert('Spot cleaning order updated successfully.');</script>";
        echo "<script>window.location.href='laundryorders.php';</script>";
        exit();
    } else {
        // Error updating status
        echo "Error updating status: " . $conn->error;
    }
} else {
    // Form data not received
    echo "<script>alert('Error updating spot cleaning order: " . $conn->error . "');</script>";
}

// Close the database connection
$conn->close();
?>
