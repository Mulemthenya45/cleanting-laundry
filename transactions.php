<?php 
session_start();
if (!isset($_SESSION['admin_firstname'])) {
    // Redirect to the sign-in page if not logged in
    header("Location: adminlogin.php");
    exit();
} 

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

// Fetch data for each type of cleaning
$sqlLaundry = "SELECT *, DATE_FORMAT(timestamp, '%b %d, %Y %h:%i %p') AS formatted_timestamp FROM paid_materials WHERE typeofcleaning = 'laundry'";
$resultLaundry = $conn->query($sqlLaundry);

$sqlDryCleaning = "SELECT *, DATE_FORMAT(timestamp, '%b %d, %Y %h:%i %p') AS formatted_timestamp FROM paid_materials WHERE typeofcleaning = 'drycleaning'";
$resultDryCleaning = $conn->query($sqlDryCleaning);

$sqlSpotCleaning = "SELECT *, DATE_FORMAT(timestamp, '%b %d, %Y %h:%i %p') AS formatted_timestamp FROM paid_materials WHERE typeofcleaning = 'spotcleaning'";
$resultSpotCleaning = $conn->query($sqlSpotCleaning);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Add your custom styles here */
        body {
            background-color: #f0f8ff; /* Aurora Blue background */
            margin: 0;
            padding: 0;
        }

        nav {
            background: linear-gradient(to right, lightblue, blue);
            border-radius: 0 0 10px 10px;
            position: relative;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            font-weight: bold;
            font-family: 'Forte', cursive;
            font-size: 1.5rem;
            padding: 10px;
            background-color: #4e7aad; /* Blue background for logo */
            border-radius: 50%; /* Oval shape */
            margin-right: 15px;
        }
        .welcome-message {
            color: white;
            font-size: 1.9rem;
            margin-top: 5px;
            font-weight: bold;
            font-family: 'Monotype Corsiva', cursive;
        }

        .logout-button {
            margin-left: auto;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4e7aad;
            color: white;
        }
    </style>
</head>
<body>
    <nav> 
        <label class="logo">CleanTing</label> 
        <span class="welcome-message">Welcome to CleanTing Admin </span>
        <div class="action-buttons">
            <a href="admindash.php" class="btn btn-primary">Dashboard</a>
            <a href="adminhome.php" class="btn btn-danger logout-button">Logout</a>
        </div>
    </nav>

    <!-- Container for content -->
    <div class="container">
        <h1>Admin Transactions</h1>

        <!-- Laundry transactions table -->
        <h2>Laundry Transactions</h2>
        <table>
        <thead>
            <tr>
            <th>Client ID</th>
                <th>Laundry ID</th>
                <th>Material</th>
                <th>Weight</th>
                <th>Cost</th>
                <th>Transaction Id</th>
                <th>Timestamp</th>
                <!-- Add more columns if needed -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the result set and display data
            if ($resultLaundry->num_rows > 0) {
                while ($row = $resultLaundry->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['clientid'] . "</td>";
                    echo "<td>" . $row['material_id'] . "</td>";
                    echo "<td>" . $row['material'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "<td>" . $row['cost'] . "</td>";
                    echo "<td>" . $row['merchant_id'] . "</td>";
                    // Display timestamp information
                    echo "<td>" . $row['formatted_timestamp'] . "</td>";
                    // Add more columns if needed
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No laundry transactions found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <h2>Dry Cleaning Transactions</h2>
    <table>
        <thead>
            <tr>
            <th>Client ID</th>
                <th>Dry Cleaning ID</th>
                <th>Material</th>
                <th>weight</th>
                <th>Cost</th>
                <th>Transaction Id</th>
                <th>Timestamp</th>
                <!-- Add more columns if needed -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the result set and display data
            if ($resultDryCleaning->num_rows > 0) {
                while ($row = $resultDryCleaning->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['clientid'] . "</td>";
                    echo "<td>" . $row['material_id'] . "</td>";
                    echo "<td>" . $row['material'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "<td>" . $row['cost'] . "</td>";
                    echo "<td>" . $row['merchant_id'] . "</td>";
                    // Display timestamp information
                    echo "<td>" . $row['formatted_timestamp'] . "</td>";
                    // Add more columns if needed
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No dry cleaning transactions found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <h2>Spot Cleaning Transactions</h2>
    <table>
        <thead>
            
            <tr>
            <th>Dry Client ID</th>
                <th>Dry Cleaning ID</th>
                <th>Material</th>
                <th>weight</th>
                <th>Cost</th>
                <th>Transaction Id</th>
                <th>Timestamp</th>
                <!-- Add more columns if needed -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the result set and display data
            if ($resultSpotCleaning->num_rows > 0) {
                while ($row = $resultSpotCleaning->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['clientid'] . "</td>";
                    echo "<td>" . $row['material_id'] . "</td>";
                    echo "<td>" . $row['material'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "<td>" . $row['cost'] . "</td>";
                    echo "<td>" . $row['merchant_id'] . "</td>";
                    // Display timestamp information
                    echo "<td>" . $row['formatted_timestamp'] . "</td>";
                    // Add more columns if needed
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No spot cleaning transactions found.</td></tr>";
            }
            ?> 
            </tbody>
            </table>
            
            <!-- Form for generating PDF -->
            <form method="post" action="generatetransactions.php">
                <input type="text" name="laundryid" hidden> <!-- Adjust this if you need to pass any data -->
                <button type="submit" name="generate_pdf">Print Receipt</button>
            </form>
</body>
</html>