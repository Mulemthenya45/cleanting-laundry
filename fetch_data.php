<?php
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

// Fetch data for the status chart for laundry
$sql_status_laundry = "SELECT 
                    status,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                    SUM(CASE WHEN status = 'inProgress' THEN 1 ELSE 0 END) AS inprogress_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS complete_count
                FROM laundrydata
                GROUP BY status";
$result_status_laundry = $conn->query($sql_status_laundry);

$status_data_laundry = [];
while ($row_status_laundry = $result_status_laundry->fetch_assoc()) {
    $status_data_laundry[] = [
        'status' => $row_status_laundry['status'],
        'pending' => $row_status_laundry['pending_count'],
        'inprogress' => $row_status_laundry['inprogress_count'],
        'completed' => $row_status_laundry['complete_count']
    ];
}

// Fetch data for the status chart for dry cleaning
$sql_status_drycleaning = "SELECT 
                    status,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                    SUM(CASE WHEN status = 'inProgress' THEN 1 ELSE 0 END) AS inprogress_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS complete_count
                FROM drycleaningdata
                GROUP BY status";
$result_status_drycleaning = $conn->query($sql_status_drycleaning);

$status_data_drycleaning = [];
while ($row_status_drycleaning = $result_status_drycleaning->fetch_assoc()) {
    $status_data_drycleaning[] = [
        'status' => $row_status_drycleaning['status'],
        'pending' => $row_status_drycleaning['pending_count'],
        'inprogress' => $row_status_drycleaning['inprogress_count'],
        'completed' => $row_status_drycleaning['complete_count']
    ];
}

// Fetch data for the status chart for spot cleaning
$sql_status_spotcleaning = "SELECT 
                    status,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
                    SUM(CASE WHEN status = 'inProgress' THEN 1 ELSE 0 END) AS inprogress_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS complete_count
                FROM spot_cleaning_data
                GROUP BY status";
$result_status_spotcleaning = $conn->query($sql_status_spotcleaning);

$status_data_spotcleaning = [];
while ($row_status_spotcleaning = $result_status_spotcleaning->fetch_assoc()) {
    $status_data_spotcleaning[] = [
        'status' => $row_status_spotcleaning['status'],
        'pending' => $row_status_spotcleaning['pending_count'],
        'inprogress' => $row_status_spotcleaning['inprogress_count'],
        'completed' => $row_status_spotcleaning['complete_count']
    ];
}

// Combine the status data for laundry, dry cleaning, and spot cleaning
$status_data = [
    'laundry' => $status_data_laundry,
    'drycleaning' => $status_data_drycleaning,
    'spotcleaning' => $status_data_spotcleaning
];

// Close the database connection
$conn->close();

// Return the data as JSON
echo json_encode($status_data);
?>
