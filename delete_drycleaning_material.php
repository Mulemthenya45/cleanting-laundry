<?php
// Check if material is provided and if it's a valid request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['material'])) {
    // Retrieve material name from the POST data
    $material = $_POST['material'];

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

    // Prepare and execute SQL statement to delete the material
    $sql = "DELETE FROM drycleaning_rates WHERE material_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $material);

    if ($stmt->execute()) {
        // Material deleted successfully
    // Display popup for "updated successfully" using JavaScript
    echo '<script>alert("Dry cleaning material deleted successfully.");</script>';
    // Redirect back to the page after 2 seconds
    echo '<script>window.setTimeout(function() { window.location.href = "adjustprices.php"; }, 2000);</script>';
} else {
    header("Location: adjustprices.php");
}
    // Close the database connection
    $conn->close();
} else {
    // Invalid request
    echo "Invalid request.";
}
?>
