<?php
// Database connection
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the submitted soap rates
    if (isset($_POST['soaps']) && is_array($_POST['soaps'])) {
        foreach ($_POST['soaps'] as $soap => $rate) {
            // Sanitize inputs
            $soap = $conn->real_escape_string($soap);
            $rate = floatval($rate); // Convert rate to float

            // Check if the soap exists in the database
            $existingSoapQuery = "SELECT * FROM soaps WHERE soap_type_name = '$soap'";
            $existingSoapResult = $conn->query($existingSoapQuery);

            if ($existingSoapResult->num_rows > 0) {
                // Update soap rate in the database
                $updateSoapQuery = "UPDATE soaps SET rate = $rate WHERE soap_type_name = '$soap'";
                $updateSoapResult = $conn->query($updateSoapQuery);

                if (!$updateSoapResult) {
                    echo "Error updating soap rate: " . $conn->error;
                }
            } else {
                // Insert new soap rate into the database
                $insertSoapQuery = "INSERT INTO soaps (soap_type_name, rate) VALUES ('$soap', $rate)";
                $insertSoapResult = $conn->query($insertSoapQuery);

                if (!$insertSoapResult) {
                    echo "Error adding new soap type: " . $conn->error;
                }
            }
        }
    }

    // Close database connection
    $conn->close();

    // Redirect back to the page with a success message
    header("Location: adjustprices.php?update=success");
    exit();
} else {
    // If the form is not submitted, redirect to the page
    header("Location: adjustprices.php");
    exit();
}
?>
