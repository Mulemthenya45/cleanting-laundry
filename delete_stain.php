<?php
// Check if the request method is POST and if the stain type is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stain'])) {
    // Retrieve the stain type from the POST data
    $stainType = $_POST['stain'];

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

    // Prepare and execute SQL statement to delete the stain type
    $stmt_delete = $conn->prepare("DELETE FROM stains WHERE StainType = ?");
    $stmt_delete->bind_param("s", $stainType);
    $stmt_delete->execute();

    // Check if the deletion was successful
    if ($stmt_delete->affected_rows > 0) {
        // Redirect back to the page after deletion
        header("Location: adjustprices.php");
    } else {
        // If no rows were affected, display an error message
        echo "Error: Failed to delete stain type.";
    }

    // Close the database connection
    $conn->close();
} else {
    // If the request method is not POST or if the stain type is not provided, redirect to the adjustprices.php page
    header("Location: adjustprices.php");
}
?>
