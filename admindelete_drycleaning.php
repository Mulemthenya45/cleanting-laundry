<?php
// Start the session
session_start();


// Check if the drycleaningid is provided in the POST request
if(isset($_POST['drycleaningid']) && !empty($_POST['drycleaningid'])) {
    // Retrieve drycleaningid from POST data
    $drycleaningid = $_POST['drycleaningid'];

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

    // Prepare a SQL statement to delete the dry cleaning record
    $sql = "DELETE FROM drycleaningdata WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("i", $drycleaningid);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the page where the delete operation was initiated
        header("Location:laundryorders.php");
        exit();
    } else {
        // Display an error message if the deletion fails
        echo "Error: Failed to delete the dry cleaning record.";
    }

    // Close the prepared statement
    $stmt->close();

    // Close the database connection
    $conn->close();
} else {
    // Redirect to an error page or handle the error accordingly
    echo "Error: Dry cleaning ID not provided.";
    exit();
}
?>
