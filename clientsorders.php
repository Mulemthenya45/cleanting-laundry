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

// Fetch client information
$sql_clients = "SELECT * FROM clientinfo";
$result_clients = $conn->query($sql_clients);

// Close the database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Custom styles */
        body {
            background-color: #f0f8ff; /* Aurora Blue background */
            margin: 0;
            padding: 0;
        }

        nav {
            background: linear-gradient(to right, lightblue, blue);
            border-radius: 0 0 10px 10px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 10px;
            background-color: #4e7aad; /* Blue background for logo */
            border-radius: 50%; /* Oval shape */
            margin-right: 15px;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
        }
        .welcome-message {
            color: white;
            
            margin-top: 10px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.9rem;
           font-weight: bold;
            font-family: 'Monotype Corsiva', cursive;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;}
        .gradient-table{
            background: linear-gradient(#a0d2eb, #e5eaf5, #d0bdf4, #8458B3, #a28089);
            animation: gradientAnimation 15s infinite linear;
        }
        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
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
        h3.section-heading {
            color: #7e7fad; /* Change the color to your desired color */
            margin-top: 20px; /* Adjust margin as needed */
        }
        .action-buttons {
    display: flex; /* Use flexbox to align items horizontally */
    align-items: center; /* Center items vertically */
}

.action-buttons .btn {
    margin-left: 10px; /* Add some space between buttons */
}

    </style>
</head>
<body>
    <nav> 
        <label class="logo">CleanTing</label> 
        <span class="welcome-message">Welcome to CleanTing, <?php echo isset($_SESSION['admin_firstname']) ? $_SESSION['admin_firstname'] : 'Admin'; ?></span>
        <div class="action-buttons">
            <a href="admindash.php" class="btn btn-primary">Dashboard</a>
            <form method="post" action="printclientorders.php">
            <input type="submit" name="generate_pdf" class="btn btn-success" value="Print">
        </form>            
        <a href="adminhome.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3">Our Client Orders</h1>
        <br> <br>
        <?php
        // Loop through each client
        while ($row = $result_clients->fetch_assoc()) {
            $client_id = $row['clientid'];
            $client_name = $row['name'];
        ?>

        <h2><?php echo $client_name; ?></h2>

        <!-- Display laundry materials -->
        <h3 class="section-heading">Laundry Materials</h3>
        <table class="gradient-table">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>weight</th>
                    <th>Quantity</th>
                    <th>Soap Type</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>timestamp</th>

                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch laundry data for the current client
                $sql_laundry = "SELECT * FROM laundrydata WHERE clientid = $client_id";
                $result_laundry = $conn->query($sql_laundry);
                while ($laundry_row = $result_laundry->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $laundry_row['material'] . "</td>";
                    echo "<td>" . $laundry_row['weight'] . "</td>";
                    echo "<td>" . $laundry_row['quantity'] . "</td>";
                    echo "<td>" . $laundry_row['soap'] . "</td>";
                    echo "<td>" . $laundry_row['cost'] . "</td>";
                    echo "<td>" . $laundry_row['status'] . "</td>";
                    echo "<td>" . $laundry_row['timestamp'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="container">
       
    <h3 class="section-heading">Dry Cleaning</h3>
        <table class="gradient-table">
            <thead>
                <tr>
                    <th>Material Type</th>
                    <th>Quantity</th>
                    <th>Weight</th>
                    <th>special treatment</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch dry cleaning data for the current client
                $sql_drycleaning = "SELECT * FROM drycleaningdata WHERE clientid = $client_id";
                $result_drycleaning = $conn->query($sql_drycleaning);
                while ($drycleaning_row = $result_drycleaning->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $drycleaning_row['material_type'] . "</td>";
                    echo "<td>" . $drycleaning_row['quantity'] . "</td>";
                    echo "<td>" . $drycleaning_row['weight'] . "</td>";
                    echo "<td>" . $drycleaning_row['special_treatment'] . "</td>";
                    echo "<td>" . $drycleaning_row['cost'] . "</td>";
                    echo "<td>" . $drycleaning_row['status'] . "</td>";
                    echo "<td>" . $drycleaning_row['created_at'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table> 
     </div>
    <div class="container">
        <!-- Display spot cleaning data -->
        <h3 class="section-heading">Spot Cleaning</h3>
        <table class="gradient-table">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Quantity</th>
                    <th>stain type</th>
                    <th>cost</th>
                    <th>status</th>
                    <th>timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch spot cleaning data for the current client
                $sql_spotcleaning = "SELECT * FROM spot_cleaning_data WHERE clientid = $client_id";
                $result_spotcleaning = $conn->query($sql_spotcleaning);
                while ($spotcleaning_row = $result_spotcleaning->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $spotcleaning_row['material'] . "</td>";
                    echo "<td>" . $spotcleaning_row['quantity'] . "</td>";
                    echo "<td>" . $spotcleaning_row['stainType'] . "</td>";
                    echo "<td>" . $spotcleaning_row['cost'] . "</td>";
                    echo "<td>" . $spotcleaning_row['status'] . "</td>";
                    echo "<td>" . $spotcleaning_row['timestamp'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
       
        <?php } // End of while loop for clients ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
