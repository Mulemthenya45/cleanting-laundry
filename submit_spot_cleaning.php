<?php
// Assuming your database credentials are stored in a separate file like config.php


// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data sent from the client
    $postData = json_decode(file_get_contents('php://input'), true);

    // Check if the spot cleaning data is present in the received data
    if (isset($postData['spotCleaningData']) && !empty($postData['spotCleaningData'])) {
         $servername = "localhost";
         $username = "root";
         $password = "";
         $dbname = "laundry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare a SQL statement to insert spot cleaning data into the database
        $stmt = $conn->prepare("INSERT INTO spot_cleaning_data (material, stainType, stainSize, quantity, cost, clientid, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");

        // Bind parameters
        $stmt->bind_param("sssids", $material, $stainType, $stainSize, $quantity, $cost, $clientId);

        // Iterate over each spot cleaning data row and insert it into the database
        foreach ($postData['spotCleaningData'] as $row) {
            $material = $row['material'];
            $stainType = $row['stainType'];
            $stainSize = $row['stainSize'];
            $quantity = $row['quantity'];
            $cost = $row['cost'];
            $clientId = $row['clientid'];

            // Execute the SQL statement
            $stmt->execute();
        }

        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();

        // Send a success response back to the client
        echo json_encode(array('success' => true));
    } else {
        // Send a failure response if spot cleaning data is missing
        echo json_encode(array('success' => false, 'error' => 'Spot cleaning data is missing.'));
    }
} else {
    // Send a failure response for non-POST requests
    echo json_encode(array('success' => false, 'error' => 'Invalid request method.'));
}
?>
