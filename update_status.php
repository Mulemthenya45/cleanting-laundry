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
if(isset($_POST['laundryid']) && isset($_POST['status'])) {
    // Sanitize input to prevent SQL injection
    $laundryid = mysqli_real_escape_string($conn, $_POST['laundryid']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Prepare SQL statement to update the status
    $sql = "UPDATE laundrydata SET status = '$status' WHERE laundryid = '$laundryid'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Status updated successfully
        echo "<script>alert('Laundry updated successfully.');</script>";
        echo "<script>window.location.href='laundryorders.php';</script>";
        exit();
    } else {
        // Error updating status
        echo "Error updating status: " . $conn->error;
    }
} else {
    // Form data not received
    echo "<script>alert('Error updating laundry: " . $conn->error . "');</script>";
}

// Close the database connection
$conn->close();
?>
