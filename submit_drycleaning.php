<?php
// submit_drycleaning.php

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON data received from the client
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the JSON data was successfully decoded and if it contains the required field
    if ($data !== null && isset($data['dryCleaningData'])) {
        // Extract dry cleaning data from the request
        $dryCleaningData = $data['dryCleaningData'];

        // Connect to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "laundry";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query to insert data with client ID
        $stmt = $conn->prepare("INSERT INTO drycleaningdata (clientid, material_type, weight, special_treatment, quantity, cost, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");

        // Bind parameters and insert each row of dry cleaning data into the database
        foreach ($dryCleaningData as $row) {
            $stmt->bind_param("isssss", $clientid, $row['material'], $row['weight'], $row['specialTreatment'], $row['quantity'], $row['cost']);

            // Assign the client ID value
            $clientid = $row['clientid'];

            // Execute the statement
            $stmt->execute();
        }

        // Close the prepared statement
        $stmt->close();

        // Close the database connection
        $conn->close();

        // Send a success response to the client
        $response = ['success' => true];
        echo json_encode($response);
    } else {
        // Invalid JSON data or missing required fields
        $response = ['success' => false, 'message' => 'Invalid or missing data'];
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = ['success' => false, 'message' => 'Invalid request method'];
    echo json_encode($response);
}
?>
