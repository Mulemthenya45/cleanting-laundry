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

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['drycleaningid']) && isset($_POST['weight']) && isset($_POST['special_treatment']) && isset($_POST['quantity']) && isset($_POST['cost'])) {
        // Retrieve form data
        $drycleaningid = $_POST['drycleaningid'];
        $weight = $_POST['weight'];
        $special_treatment = $_POST['special_treatment'];
        $quantity = $_POST['quantity'];
        $cost = $_POST['cost'];

        // Update the data in the drycleaningdata table
        $sql = "UPDATE drycleaningdata SET weight='$weight', special_treatment='$special_treatment', quantity='$quantity', cost='$cost' WHERE id='$drycleaningid'";

        if ($conn->query($sql) === TRUE) {
            // Redirect back to the page where the update was initiated
            header("Location: laundryorders.php?success=1");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Error: All fields are required";
    }
} else {
    echo "Error: Invalid request method";
}

// Close the database connection
$conn->close();
?>
