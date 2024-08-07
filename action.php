<?php
session_start();

if (isset($_POST['submit'])) {

    $amount = $_POST['totalCost']; // Removed the dollar sign
    $phone_number = $_POST['clientMpesaNumber']; // Removed the dollar sign
    $phone_number = '254' . substr($phone_number, 1);

    $consumer_key = 'lEtdtqaoqCl3l8wGDe4v2AtNlAuPyCY7EaFxOmJA6dbOiEli';
    $consumer_secret = 'Dp8mwkWyyHWdErcXdbOAXRnWz15jitWhCGCQXiWANqvGNl6LbYNJZQGIvYq8iSF1';

    $Business_Code = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $Type_of_Transaction = 'CustomerPayBillOnline';
    $Token_URL = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $OnlinePayment = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $CallBackURL = 'https://e790-197-248-104-2.ngrok-free.app/laundry/callback.php';
    $Time_Stamp = date("Ymdhis");
    $password = base64_encode($Business_Code . $Passkey . $Time_Stamp);

    $curl_request = curl_init();
    curl_setopt($curl_request, CURLOPT_URL, $Token_URL);
    $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
    curl_setopt($curl_request, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($curl_request, CURLOPT_HEADER, false);
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
    $curl_request_response = curl_exec($curl_request);

    $token = json_decode($curl_request_response)->access_token;

    $curl_Tranfer2 = curl_init();
    curl_setopt($curl_Tranfer2, CURLOPT_URL, $OnlinePayment);
    curl_setopt($curl_Tranfer2, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token));

    $curl_Tranfer2_post_data = [
        'BusinessShortCode' => $Business_Code,
        'Password' => $password,
        'Timestamp' => $Time_Stamp,
        'TransactionType' => $Type_of_Transaction,
        'Amount' => $amount,
        'PartyA' => $phone_number,
        'PartyB' => $Business_Code,
        'PhoneNumber' => $phone_number,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => 'Subscription System',
        'TransactionDesc' => 'Test transaction',
    ];

    $data2_string = json_encode($curl_Tranfer2_post_data);

    curl_setopt($curl_Tranfer2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_Tranfer2, CURLOPT_POST, true);
    curl_setopt($curl_Tranfer2, CURLOPT_POSTFIELDS, $data2_string);
    curl_setopt($curl_Tranfer2, CURLOPT_HEADER, false);
    curl_setopt($curl_Tranfer2, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl_Tranfer2, CURLOPT_SSL_VERIFYHOST, 0);
    $curl_Tranfer2_response = json_decode(curl_exec($curl_Tranfer2));

    // Check if the response code is zero
   // Check if the response code is zero
if ($curl_Tranfer2_response->ResponseCode === "0") {
    // Store Merchant Request ID and amount paid in session
    $_SESSION['merchant_request_id'] = $curl_Tranfer2_response->MerchantRequestID;
    $_SESSION['amount_paid'] = $amount;
    
    // Redirect to the receipt page
    header("Location: receipt.php");
    exit(); // Stop further execution
} else {
    // If not successful, display an error message or handle accordingly
    echo "Payment request failed.";
}

}
?>
