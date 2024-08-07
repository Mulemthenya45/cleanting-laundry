<?php

// Set response content type to application/json
header('Content-Type: application/json');

// Get the request body sent by Safaricom
$requestBody = file_get_contents('php://input');

// Log the request body for debugging purposes
file_put_contents('log.txt', $requestBody . PHP_EOL, FILE_APPEND);

// Decode the request body from JSON to an array
$data = json_decode($requestBody, true);

// Check if all required fields are present in the received data
if (
    isset($data['TransactionType'], $data['TransID'], $data['TransTime'], $data['TransAmount'],
    $data['MSISDN'], $data['BillRefNumber'], $data['ResultCode'], $data['ResultDesc'])
) {
    // Extract the transaction details from the request body
    $transactionType = $data['TransactionType'];
    $transactionId = $data['TransID'];
    $transactionTime = $data['TransTime'];
    $transactionAmount = $data['TransAmount'];
    $transactionPhoneNumber = $data['MSISDN'];
    $transactionReference = $data['BillRefNumber'];
    $transactionResultCode = $data['ResultCode'];
    $transactionResultDesc = $data['ResultDesc'];

    // Here you can perform any necessary actions based on the transaction details received
    // For example, you could update your database with the transaction details or send a notification to the user

    // Check if the transaction was successful
    if ($transactionResultCode == '0') {
        // Transaction completed successfully
        // You may perform further actions here, such as updating your database or sending a notification to the user
        $response = [
            'ResultCode' => '0',
            'ResultDesc' => 'Transaction completed successfully'
        ];
    } else {
        // Transaction failed
        // You may handle this case differently based on your requirements
        $response = [
            'ResultCode' => $transactionResultCode,
            'ResultDesc' => $transactionResultDesc
        ];
    }
} else {
    // Required fields are missing in the received data
    // You may handle this case differently based on your requirements
    $response = [
        'ResultCode' => '400',
        'ResultDesc' => 'Incomplete data received'
    ];
}

// Send the response back to Safaricom
echo json_encode($response);
?>
