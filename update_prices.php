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
    // Process the submitted material rates
    if (isset($_POST['materials']) && is_array($_POST['materials'])) {
        foreach ($_POST['materials'] as $material => $rate) {
            // Sanitize inputs
            $material = $conn->real_escape_string($material);
            $rate = floatval($rate); // Convert rate to float

            // Check if the material exists in the database
            $existingMaterialQuery = "SELECT * FROM laundryprice WHERE material_name = '$material'";
            $existingMaterialResult = $conn->query($existingMaterialQuery);

            if ($existingMaterialResult->num_rows > 0) {
                // Update material rate in the database
                $updateMaterialQuery = "UPDATE laundryprice SET rate = $rate WHERE material_name = '$material'";
                $updateMaterialResult = $conn->query($updateMaterialQuery);

                if (!$updateMaterialResult) {
                    echo "Error updating material rate: " . $conn->error;
                }
            } else {
                // Insert new material rate into the database
                $insertMaterialQuery = "INSERT INTO laundryprice (material_name, rate) VALUES ('$material', $rate)";
                $insertMaterialResult = $conn->query($insertMaterialQuery);

                if (!$insertMaterialResult) {
                    echo "Error adding new material: " . $conn->error;
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
