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
    // Process the submitted special treatment rates
    if (isset($_POST['special_treatments']) && is_array($_POST['special_treatments'])) {
        foreach ($_POST['special_treatments'] as $treatment => $rate) {
            // Sanitize inputs
            $treatment = $conn->real_escape_string($treatment);
            $rate = floatval($rate); // Convert rate to float

            // Print submitted values to the console for debugging
            echo "Treatment: $treatment, Rate: $rate\n";

            // Check if the special treatment exists in the database
            $existingTreatmentQuery = "SELECT * FROM special_treatment_rates WHERE treatment_type = '$treatment'";
            $existingTreatmentResult = $conn->query($existingTreatmentQuery);

            if ($existingTreatmentResult->num_rows > 0) {
                // Update special treatment rate in the database
                $updateTreatmentQuery = "UPDATE special_treatment_rates SET rate = $rate WHERE treatment_type = '$treatment'";
                $updateTreatmentResult = $conn->query($updateTreatmentQuery);

                if (!$updateTreatmentResult) {
                    echo "Error updating special treatment rate: " . $conn->error;
                }
            } else {
                // Insert new special treatment rate into the database
                $insertTreatmentQuery = "INSERT INTO special_treatment_rates (treatment_type, rate) VALUES ('$treatment', $rate)";
                $insertTreatmentResult = $conn->query($insertTreatmentQuery);

                if (!$insertTreatmentResult) {
                    echo "Error adding new special treatment: " . $conn->error;
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
