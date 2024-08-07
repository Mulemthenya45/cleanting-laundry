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
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$contactno = $_SESSION['contactno'];
$clientid = $_SESSION['clientid'];

// Your database connection code goes here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laundry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Function to fetch material rates from the database
function getMaterialRates($conn) {
    $materialRates = array();
    $sql = "SELECT material_name, rate FROM laundryprice"; // Change 'laundryprice' to your actual material rates table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $materialRates[$row['material_name']] = $row['rate'];
        }
    }

    return $materialRates;
}

// Function to fetch soap rates from the database
function getSoapRates($conn) {
    $soapRates = array();
    $sql = "SELECT soap_type_name, rate FROM soaps"; // Change 'soaps' to your actual soap rates table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $soapRates[$row['soap_type_name']] = $row['rate'];
        }
    }

    return $soapRates;
}
// Function to fetch special treatment rates from the database
function getSpecialTreatmentRates($conn) {
    $specialTreatmentRates = array();
    $sql = "SELECT treatment_type, rate FROM special_treatment_rates"; // Change 'special_treatment_rates' to your actual special treatment rates table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $specialTreatmentRates[$row['treatment_type']] = $row['rate'];
        }
    }

    return $specialTreatmentRates;
}
// Function to fetch stain rates from the database
function getStainRates($conn) {
    $stainRates = array();
    $sql = "SELECT StainType, Rate FROM stains"; // Change 'stains' to your actual stains table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stainRates[$row['StainType']] = $row['Rate'];
        }
    }

    return $stainRates;
}
// Function to fetch dry cleaning rates from the database
function getDryCleaningRates($conn) {
    $dryCleaningRates = array();
    $sql = "SELECT material_type, rate FROM drycleaning_rates"; // Change 'drycleaning_rates' to your actual dry cleaning rates table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dryCleaningRates[$row['material_type']] = $row['rate'];
        }
    }

    return $dryCleaningRates;
}


$dryCleaningRates = getDryCleaningRates($conn);
$stainRates = getStainRates($conn);
$specialTreatmentRates = getSpecialTreatmentRates($conn);
$materialRates = getMaterialRates($conn);
$soapRates = getSoapRates($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Add your custom styles here */
        body {
            background-image: url('userhomeback.jpg');
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

        .dashboard-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-left: 0; /* Set margin-left to 0 to place buttons at the leftmost position */
        }

        .dashboard-button {
            width: 150px;
            height: 50px;
            font-size: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        /* Add styles for the user home image */
        .user-home-image {
            width:100%;
            height: 300px;/* Cover a quarter of the screen */
            margin-top: 0px; /* Adjust the margin as needed */
        }
        .headline {
    color: white; /* Blue color for the headline */
    font-family: 'Forte', cursive; /* Use the Forte font */
    margin-bottom: 20px; /* Add some space below the headline */
}
.with-background {
    background-image: url('userhomeback.jpg'); /* Replace 'userhomeback.png' with your actual image file */
    background-size: cover;
    background-position: center;
    color: white; /* Set text color to white or a color that contrasts well with the background */
    padding: 20px; /* Add some padding to the region */
}
    </style>
</head>
<body>
    <nav> 
        <label class="logo">CleanTing</label> 
        <span class="welcome-message">Welcome to cleanting, <?php echo $name; ?>! </span>
        <div class="action-buttons">
            <a href="contact.php" class="btn btn-primary">Contact</a>
            <a href="logout.php" class="btn btn-danger logout-button">Logout</a>
        </div>
    </nav>

    <!-- Insert user home image here -->
    <img src="userhome.png" alt="User Home Image" class="user-home-image">
    

    <div>
        <div class="row">
            <!-- Left Region (Buttons) -->
            <div class="col-md-4">
                <div class="dashboard-buttons ">
                <a href="laundrydescriptionpage.php" class="btn btn-primary dashboard-button">Add Laundry</a>
                    <a href="view_laundry.php" class="btn btn-primary dashboard-button">View Recent Laundry</a>
                    <a href="check_status.php" class="btn btn-info dashboard-button">My Laundry Status</a>
                    <a href="update_profile.php" class="btn btn-warning dashboard-button">Update Profile</a>
                    <a href="pay_laundry.php" class="btn btn-success dashboard-button">Pay</a>
                </div>
            </div>

            <!-- Middle Region (Describe Laundry Button) -->
            <div class="col-md-4 text-center mb-3 with-background">
    <h1 class="headline">These are the current CleanTing prices</h1>
    
        <h3>Material Rates</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($materialRates as $material => $rate) {
                    echo "<tr><td>$material</td><td>$rate</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h3>Special Treatment Rates</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Treatment Type</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch special treatment rates from the database
            $sqlSpecial = "SELECT treatment_type, rate FROM special_treatment_rates";
            $resultSpecial = $conn->query($sqlSpecial);

            if ($resultSpecial->num_rows > 0) {
                while ($row = $resultSpecial->fetch_assoc()) {
                    echo "<tr><td>" . $row['treatment_type'] . "</td><td>" . $row['rate'] . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No special treatment rates found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <h3>Dry Cleaning Rates</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Material Type</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dryCleaningRates = getDryCleaningRates($conn);
            foreach ($dryCleaningRates as $materialType => $rate) {
                echo "<tr><td>$materialType</td><td>$rate</td></tr>";
            }
            ?>
        </tbody>
    </table>
            </div>

            <!-- Right Region (Blank) -->
            <div class="col-md-4 with-background">
        <br><br><br><br><br><h3>Soap Rates</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Soap Type Name</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($soapRates as $soapTypeName => $rate) {
                    echo "<tr><td>$soapTypeName</td><td>$rate</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h3>Stain cleaning Rates per cm2</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Stain Type</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stainRates = getStainRates($conn);
            foreach ($stainRates as $stainType => $rate) {
                echo "<tr><td>$stainType</td><td>$rate</td></tr>";
            }
            ?>
        </tbody>
    </table>
            </div>
        </div>
    </div>
    <!-- ... Your script and Bootstrap JS ... -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
