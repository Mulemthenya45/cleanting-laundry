<?php
// Start the session

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

// Check if the form is submitted and the treatment is set
if (isset($_POST['treatment'])) {
    // Sanitize the treatment input to prevent SQL injection
    $treatment = $conn->real_escape_string($_POST['treatment']);

    // Prepare the SQL statement to delete the special treatment
    $sql = "DELETE FROM special_treatment_rates WHERE treatment_type = '$treatment'";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the edit page with a success message
        header("Location: adjustprices.php?message=Special treatment deleted successfully");
        exit();
    } else {
        // Redirect back to the edit page with an error message
        header("Location: adjustprices.php?error=Error deleting special treatment: " . $conn->error);
        exit();
    }
} else {
    // If treatment is not set, redirect back to the edit page
    header("Location: adjustprices.php.php");
    exit();
}

// Close the database connection
$conn->close();
?>
