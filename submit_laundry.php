<?php
// submit_laundry.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['laundryData'])) {
        $laundryData = $data['laundryData'];

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
        $stmt = $conn->prepare("INSERT INTO laundrydata (clientid, material, weight, soap, quantity, cost, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");

        foreach ($laundryData as $row) {
            $stmt->bind_param("isssss", $clientid, $row['material'], $row['weight'], $row['soap'], $row['quantity'], $row['cost']);
            
            // Assign the client ID value
            $clientid = $row['clientid'];
            
            $stmt->execute();
        }

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Send a response to the client (you can customize the response as needed)
        echo json_encode(['success' => true, 'message' => 'Laundry data submitted successfully']);
    } else {
        // Invalid request
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
