<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    // Redirect to the sign-in page if not logged in
    header("Location: signin.php");
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

// Fetch laundry data for the current client with status 'completed'
$clientid = $_SESSION['clientid'];
$sqlLaundry = "SELECT * FROM laundrydata WHERE clientid = $clientid AND status = 'completed'";
$resultLaundry = $conn->query($sqlLaundry);

// Calculate total cost
$totalCostLaundry = 0;
if ($resultLaundry->num_rows > 0) {
    while ($row = $resultLaundry->fetch_assoc()) {
        $totalCostLaundry += $row['cost'];
    }
}

// Reset the pointer to the beginning of the result set
$resultLaundry->data_seek(0);

// Fetch dry cleaning data for the current client with status 'completed'
$sqlDryCleaning = "SELECT * FROM drycleaningdata WHERE clientid = $clientid AND status = 'completed'";
$resultDryCleaning = $conn->query($sqlDryCleaning);

// Calculate total cost for dry cleaning
$totalCostDryCleaning = 0;
if ($resultDryCleaning->num_rows > 0) {
    while ($row = $resultDryCleaning->fetch_assoc()) {
        $totalCostDryCleaning += $row['cost'];
    }
}

// Reset the pointer to the beginning of the result set
$resultDryCleaning->data_seek(0);

// Fetch spot cleaning data for the current client with status 'completed'
$sqlSpotCleaning = "SELECT * FROM spot_cleaning_data WHERE clientid = $clientid AND status = 'completed'";
$resultSpotCleaning = $conn->query($sqlSpotCleaning);

// Calculate total cost for spot cleaning
$totalCostSpotCleaning = 0;
if ($resultSpotCleaning->num_rows > 0) {
    while ($row = $resultSpotCleaning->fetch_assoc()) {
        $totalCostSpotCleaning += $row['cost'];
    }
}


$resultSpotCleaning->data_seek(0);


// Check if the user has paid for laundry
if ($resultLaundry->num_rows > 0) {
    while ($row = $resultLaundry->fetch_assoc()) {
        $materialId = $row['laundryid'];
        $material = $row['material'];
        $weight = $row['weight'];
        $cost = $row['cost'];
        $merchantId = $_SESSION['merchant_request_id'];

        $insertLaundrySql = "INSERT INTO paid_materials (material_id, material, weight, cost, merchant_id, clientid, typeofcleaning) VALUES ('$materialId', '$material', '$weight', '$cost', '$merchantId', '$clientid', 'laundry')";
        if ($conn->query($insertLaundrySql) !== TRUE) {
            $error = true;
            echo "Error inserting laundry material details: " . $conn->error;
        }
    }
}
$resultLaundry->data_seek(0);
// Check if the user has paid for dry cleaning
if ($resultDryCleaning->num_rows > 0) {
    while ($row = $resultDryCleaning->fetch_assoc()) {
        $materialId = $row['id'];
        $material = $row['material_type'];
        $weight = ''; // Adjust if dry cleaning has weight information
        $cost = $row['cost'];
        $merchantId = $_SESSION['merchant_request_id'];

        $insertDryCleaningSql = "INSERT INTO paid_materials (material_id, material, weight, cost, merchant_id, clientid, typeofcleaning) VALUES ('$materialId', '$material', '$weight', '$cost', '$merchantId','$clientid', 'drycleaning')";
        if ($conn->query($insertDryCleaningSql) !== TRUE) {
            $error = true;
            echo "Error inserting dry cleaning material details: " . $conn->error;
        }
    }
}
$resultDryCleaning->data_seek(0);

// Check if the user has paid for spot cleaning
if ($resultSpotCleaning->num_rows > 0) {
    while ($row = $resultSpotCleaning->fetch_assoc()) {
        $materialId = $row['id'];
        $material = $row['material'];
        $weight = ''; // Adjust if spot cleaning has weight information
        $cost = $row['cost'];
        $merchantId = $_SESSION['merchant_request_id'];

        $insertSpotCleaningSql = "INSERT INTO paid_materials (material_id, material, weight, cost, merchant_id, clientid, typeofcleaning) VALUES ('$materialId', '$material', '$weight', '$cost', '$merchantId','$clientid', 'spotcleaning')";
        if ($conn->query($insertSpotCleaningSql) !== TRUE) {
            $error = true;
            echo "Error inserting spot cleaning material details: " . $conn->error;
        }
    }
}
$resultSpotCleaning->data_seek(0);


// Close the database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        /* Navigation bar styles */
        .navbar {
            background-color: #2c3e50; /* Adjust color as per your project theme */
            color: white;
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

        .navbar img {
            height: 50px; /* Adjust logo size */
            width: auto;
        }

        /* Receipt content styles */
        body {
            background-color: #ecf0f1; /* Adjust background color as per your project theme */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .receipt-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #2c3e50; /* Adjust header background color */
            color: white;
        }

        .total-cost {
            text-align: right;
            font-weight: bold;
        }

        .exit-button {
            margin-top: 20px;
            text-align: center;
        }

        .exit-button button {
            background-color: #2c3e50; /* Adjust button background color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .exit-button button:hover {
            background-color: #34495e; /* Adjust button hover color */
        }

        .welcome-message {
            color: white;
            font-size: 1.9rem;
            margin-top: 5px;
            font-weight: bold;
            text-align: center;
            flex-grow: 1;
            font-family: 'Monotype Corsiva', cursive;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <label class="logo">CleanTing</label> 
        <div class="welcome-message">Thank you for cleaning with us <?php echo $_SESSION['name']; ?></div>
    </div>

    <!-- Receipt content -->
    <div class="receipt-container">
       
        <h1>Payment Receipt</h1>
        <h2>Laundry cleaning</h2>
        <!-- Laundry table -->
        <table>
            <thead>
                <tr>
                    <th>Laundry ID</th>
                    <th>Material</th>
                    <th>Weight (kg)</th>
                    <th>Soap Type</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultLaundry->num_rows > 0) {
                    while ($row = $resultLaundry->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['laundryid']; ?></td>
                            <td><?php echo $row['material']; ?></td>
                            <td><?php echo $row['weight']; ?></td>
                            <td><?php echo $row['soap']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="7">No completed laundry to display.</td>
                    </tr>
                <?php } ?>
                <!-- Display the total cost row for laundry -->
                <tr class="total-cost">
                    <td colspan="5" style="text-align: right;">Laundry Subtotal:</td>
                    <td><?php echo $totalCostLaundry; ?></td>
                    <td></td> <!-- Placeholder for time -->
                </tr>
            </tbody>
        </table>

        <!-- Dry cleaning table -->
        <h2>Dry Cleaning</h2>
        <table>
            <thead>
                <tr>
                    <th>Dry Cleaning ID</th>
                    <th>Material Type</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultDryCleaning->num_rows > 0) {
                    while ($row = $resultDryCleaning->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['material_type']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5">No completed dry cleaning to display.</td>
                    </tr>
                <?php } ?>
                <!-- Display the total cost row for dry cleaning -->
                <tr class="total-cost">
                    <td colspan="4" style="text-align: right;">Dry Cleaning Subtotal:</td>
                    <td><?php echo $totalCostDryCleaning; ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Spot cleaning table -->
       <!-- Spot cleaning table -->
<h2>Spot Cleaning</h2>
<table>
    <thead>
        <tr>
            <th>Spot Cleaning ID</th>
            <th>Material Type</th>
            <th>Stain Type</th>
            <th>Stain Size</th>
            <th>Quantity</th>
            <th>Cost</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
    <?php
        // Fetch spot cleaning data for the current client with status 'completed'
        $sqlSpotCleaning = "SELECT * FROM spot_cleaning_data WHERE clientid = $clientid AND status = 'completed'";
        $resultSpotCleaning = $conn->query($sqlSpotCleaning);

        if ($resultSpotCleaning->num_rows > 0) {
            while ($row = $resultSpotCleaning->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['material']; ?></td>
                    <td><?php echo $row['stainType']; ?></td>
                    <td><?php echo $row['stainSize']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['cost']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="7">No completed spot cleaning to display.</td>
            </tr>
        <?php } ?>
        <!-- Display the total cost row for spot cleaning -->
        <tr class="total-cost">
            <td colspan="6" style="text-align: right;">Spot Cleaning Subtotal:</td>
            <td><?php echo $totalCostSpotCleaning; ?></td>
        </tr>
    </tbody>
</table>

<!-- Display the overall total cost -->
<div class="total-cost">
    <p>Total Cost: <?php echo $totalCostLaundry + $totalCostDryCleaning + $totalCostSpotCleaning; ?></p>
</div>


        <!-- Print receipt and exit button code after -->
        <form method="post" action="generate.php">
            <input type="text" name="laundryid" value="<?php echo $laundryid; ?>" hidden>
            <button type="submit" name="generate_pdf">Print Receipt</button>
        </form>
        <div class="exit-button"> <!-- Add additional content or styling as needed -->
            <form method="post" action="update_status_and_redirect.php">
                <button type="submit" name="exit_page">Exit Page</button>
            </form>
        </div>
    </div>
</body>
</html>
