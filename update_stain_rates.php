<?php
// Check if form data is provided and if it's a valid request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stains'])) {
    // Retrieve stain rates from the POST data
    $stainRates = $_POST['stains'];

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

    // Prepare and execute SQL statement to update or insert stain rates
    $stmt_update = $conn->prepare("UPDATE stains SET Rate = ? WHERE StainType = ?");
    $stmt_insert = $conn->prepare("INSERT INTO stains (StainType, Rate) VALUES (?, ?)");

    foreach ($stainRates as $stainType => $rate) {
        // Check if the stain type already exists in the database
        $check_stmt = $conn->prepare("SELECT * FROM stains WHERE StainType = ?");
        $check_stmt->bind_param("s", $stainType);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Stain type exists, update its rate
            $stmt_update->bind_param("ds", $rate, $stainType);
            $stmt_update->execute();
        } else {
            // Stain type doesn't exist, insert new record
            $stmt_insert->bind_param("sd", $stainType, $rate);
            $stmt_insert->execute();
        }
    }

    // Check if any updates or insertions were successful
    if (($stmt_update->affected_rows > 0) || ($stmt_insert->affected_rows > 0)) {
        // Display popup for "updated successfully" using JavaScript
        echo '<script>alert("Stain rates updated successfully.");</script>';
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
