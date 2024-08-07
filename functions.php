<?php
// Start the session
function mpesa ($phone, $amount, $ordernum){
    define('CALLBACK_URL',' https://923e-196-108-160-187.ngrok-free.app/laundry/callback_url.php?orderid=');
$consumerKey = 'lEtdtqaoqCl3l8wGDe4v2AtNlAuPyCY7EaFxOmJA6dbOiEli';
$consumerSecret = 'Dp8mwkWyyHWdErcXdbOAXRnWz15jitWhCGCQXiWANqvGNl6LbYNJZQGIvYq8iSF1';
$BusinessShortCode = '174379';
$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$phone = preg_replace('/^0/','254', str_replace("+","",$phone));
$PartyA = $phone;
$PartyB = '174379';
$TransactionDesc = 'Pay Order';
 $Timestamp = date('YmdHis');
 $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
 $headers = ['Content-Type:application/json; charset=utf8'];
 
 $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
 $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

 # callback url
 $CallBackURL = 'https://morning-basin-87523.herokuapp.com/callback_url.php';  

 $curl = curl_init($access_token_url);
 curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
 curl_setopt($curl, CURLOPT_HEADER, FALSE);
 curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
 $result = curl_exec($curl);
 $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 $result = json_decode($result);
 $access_token = $result->access_token;  
 curl_close($curl);

 # header for stk push
 $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

 # initiating the transaction
 $curl = curl_init();
 curl_setopt($curl, CURLOPT_URL, $initiate_url);
 curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

 $curl_post_data = array(
   //Fill in the request parameters with valid values
   'BusinessShortCode' => $BusinessShortCode,
   'Password' => $Password,
   'Timestamp' => $Timestamp,
   'TransactionType' => 'CustomerPayBillOnline',
   'Amount' => $Amount,
   'PartyA' => $PartyA,
   'PartyB' => $BusinessShortCode,
   'PhoneNumber' => $PartyA,
   'CallBackURL' => $CallBackURL. $ordernum,
   'AccountReference' => $ordernum,
   'TransactionDesc' => $TransactionDesc
 );

 $data_string = json_encode($curl_post_data);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($curl, CURLOPT_POST, true);
 curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
 $curl_response = curl_exec($curl);
 $res = (array)(json_decode($curl_response));
 $ResponseCode = $res['ResponseCode'];
 return $ResponseCode;
};

?>