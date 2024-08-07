<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    // Redirect to the sign-in page if not logged in
    header("Location: signin.php");
    exit();
}

// Retrieve user information from session variables
$clientid = $_SESSION['clientid'];

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

// Fetch completed laundry data for the current client
$sqlLaundry = "SELECT * FROM laundrydata WHERE clientid = $clientid AND status = 'Completed'";
$resultLaundry = $conn->query($sqlLaundry);

// Check if there are rows in the result for laundry data
$completedLaundry = array();
$totalCost = 0;
if ($resultLaundry->num_rows > 0) {
    while ($row = $resultLaundry->fetch_assoc()) {
        $completedLaundry[] = $row;
        $totalCost += $row['cost'];
    }
}

// Fetch completed dry cleaning data for the current client
$sqlDryCleaning = "SELECT * FROM drycleaningdata WHERE clientid = $clientid AND status = 'Completed'";
$resultDryCleaning = $conn->query($sqlDryCleaning);

// Check if there are rows in the result for dry cleaning data
$completedDryCleaning = array();
if ($resultDryCleaning->num_rows > 0) {
    while ($row = $resultDryCleaning->fetch_assoc()) {
        $completedDryCleaning[] = $row;
        $totalCost += $row['cost'];
    }
}

// Fetch completed spot cleaning data for the current client
$sqlSpotCleaning = "SELECT * FROM spot_cleaning_data WHERE clientid = $clientid AND status = 'Completed'";
$resultSpotCleaning = $conn->query($sqlSpotCleaning);

// Check if there are rows in the result for spot cleaning data
$completedSpotCleaning = array();
if ($resultSpotCleaning->num_rows > 0) {
    while ($row = $resultSpotCleaning->fetch_assoc()) {
        $completedSpotCleaning[] = $row;
        $totalCost += $row['cost'];
    }
}

// Fetch client's M-Pesa number
$sqlClientInfo = "SELECT contactno FROM clientinfo WHERE clientid = $clientid";
$resultClientInfo = $conn->query($sqlClientInfo);
$clientMpesaNumber = "";
if ($resultClientInfo->num_rows > 0) {
    $row = $resultClientInfo->fetch_assoc();
    $clientMpesaNumber = $row['contactno'];
}

// Close the database connection
$conn->close();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Add your custom styles here */
        body {
            background-color: #f0f8ff; /* Aurora Blue background */
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
        }

        .payment-details {
            margin-bottom: 20px;
        }

        .navbar {
            background: linear-gradient(to right, lightblue, blue); /* Gradient from blue to aurora blue */
            border-radius: 0 0 10px 10px; /* Rounded corners at the bottom */
            margin-bottom: 20px;
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
            font-family: 'Forte', cursive;
            font-size: 1.5rem;
            padding: 10px;
            background-color: #4e7aad; /* Blue background for logo */
            border-radius: 50%; /* Oval shape */
            margin-right: 15px;
        }

        .logout-button {
            color: white;
            font-size: 1rem;
            margin-top: 10px;
            position: absolute;
            right: 10px;
        }

        .back-button {
            color: white;
            font-size: 1rem;
            margin-top: 10px;
            position: absolute;
            left: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CleanTing</a>
            <a href="userhome.php" class="btn btn-danger logout-button">Back</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3">Client Payment</h1>

        <div class="payment-details">
            <h2>Payment Details</h2>
            <p>Total Cost: KES <?php echo number_format($totalCost, 2); ?></p>
            <p>Client M-Pesa Number: <?php echo $clientMpesaNumber; ?></p>
        </div>

        <?php if (!empty($completedLaundry)) : ?>
            <h2>Completed Laundry</h2>
            <table class="table">
                <!-- Table header -->
                <thead>
                    <tr>
                        <th>Laundry ID</th>
                        <th>Material</th>
                        <th>Weight (kg)</th>
                        <th>Soap Type</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body -->
                    <?php foreach ($completedLaundry as $laundry) : ?>
                        <tr>
                            <td><?php echo $laundry['laundryid']; ?></td>
                            <td><?php echo $laundry['material']; ?></td>
                            <td><?php echo $laundry['weight']; ?></td>
                            <td><?php echo $laundry['soap']; ?></td>
                            <td><?php echo $laundry['quantity']; ?></td>
                            <td><?php echo $laundry['cost']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No completed laundry for payment.</p>
        <?php endif; ?>

        <!-- Include completed dry cleaning table if data is available -->
        <?php if (!empty($completedDryCleaning)) : ?>
            <h2>Completed Dry Cleaning</h2>
            <table class="table">
                <!-- Table header -->
                <thead>
                    <tr>
                        <th>Dry Cleaning ID</th>
                        <th>Material Type</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body -->
                    <?php foreach ($completedDryCleaning as $dryCleaning) : ?>
                        <tr>
                            <td><?php echo $dryCleaning['id']; ?></td>
                            <td><?php echo $dryCleaning['material_type']; ?></td>
                            <td><?php echo $dryCleaning['quantity']; ?></td>
                            <td><?php echo $dryCleaning['cost']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Include completed spot cleaning table if data is available -->
        <?php if (!empty($completedSpotCleaning)) : ?>
            <h2>Completed Spot Cleaning</h2>
            <table class="table">
                <!-- Table header -->
                <thead>
                    <tr>
                        <th>Spot Cleaning ID</th>
                        <th>Material Type</th>
                        <th>Stain Type</th>
                        <th>Stain Size</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body -->
                    <?php foreach ($completedSpotCleaning as $spotCleaning) : ?>
                        <tr>
                            <td><?php echo $spotCleaning['id']; ?></td>
                            <td><?php echo $spotCleaning['material']; ?></td>
                            <td><?php echo $spotCleaning['stainType']; ?></td>
                            <td><?php echo $spotCleaning['stainSize']; ?></td>
                            <td><?php echo $spotCleaning['quantity']; ?></td>
                            <td><?php echo $spotCleaning['cost']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Payment Form -->
        <form id="paymentForm" action="action.php" method="post">
    <input type="hidden" name="totalCost" value="<?php echo intval($totalCost); ?>"> <!-- Convert total cost to integer -->
    <input type="hidden" name="clientMpesaNumber" value="<?php echo $clientMpesaNumber; ?>">
    <button type="submit" name="submit" class="btn btn-primary">Make Payment</button>
</form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
