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

// Fetch number of clients from clientinfo table
$sql_clients = "SELECT COUNT(*) AS total_clients FROM clientinfo";
$result_clients = $conn->query($sql_clients);
$row_clients = $result_clients->fetch_assoc();
$total_clients = $row_clients['total_clients'];

// Fetch total number of orders
$sql_orders = "SELECT COUNT(*) AS total_orders FROM (
                    SELECT * FROM laundrydata
                    UNION ALL
                    SELECT * FROM drycleaningdata
                    UNION ALL
                    SELECT * FROM spot_cleaning_data
               ) AS combined_orders";
$result_orders = $conn->query($sql_orders);
$row_orders = $result_orders->fetch_assoc();
$total_orders = $row_orders['total_orders'];

// Fetch total number of laundry orders
$sql_laundry_orders = "SELECT COUNT(*) AS total_laundry_orders FROM laundrydata";
$result_laundry_orders = $conn->query($sql_laundry_orders);
$row_laundry_orders = $result_laundry_orders->fetch_assoc();
$total_laundry_orders = $row_laundry_orders['total_laundry_orders'];

// Fetch total number of dry cleaning orders
$sql_drycleaning_orders = "SELECT COUNT(*) AS total_drycleaning_orders FROM drycleaningdata";
$result_drycleaning_orders = $conn->query($sql_drycleaning_orders);
$row_drycleaning_orders = $result_drycleaning_orders->fetch_assoc();
$total_drycleaning_orders = $row_drycleaning_orders['total_drycleaning_orders'];

// Fetch total number of spot cleaning orders
$sql_spotcleaning_orders = "SELECT COUNT(*) AS total_spotcleaning_orders FROM spot_cleaning_data";
$result_spotcleaning_orders = $conn->query($sql_spotcleaning_orders);
$row_spotcleaning_orders = $result_spotcleaning_orders->fetch_assoc();
$total_spotcleaning_orders = $row_spotcleaning_orders['total_spotcleaning_orders'];

// Calculate total number of orders
$total_orders = $total_laundry_orders + $total_drycleaning_orders + $total_spotcleaning_orders;


// Fetch data for the total cost of paid materials for laundry, dry cleaning, and spot cleaning
$sql_paid_materials = "SELECT 
                            SUM(CASE WHEN typeofcleaning = 'laundry' THEN cost ELSE 0 END) AS laundry_cost,
                            SUM(CASE WHEN typeofcleaning = 'drycleaning' THEN cost ELSE 0 END) AS drycleaning_cost,
                            SUM(CASE WHEN typeofcleaning = 'spotcleaning' THEN cost ELSE 0 END) AS spotcleaning_cost
                        FROM paid_materials";
$result_paid_materials = $conn->query($sql_paid_materials);
$row_paid_materials = $result_paid_materials->fetch_assoc();

// Calculate total cost for each type of cleaning
$total_laundry_cost = $row_paid_materials['laundry_cost'];
$total_drycleaning_cost = $row_paid_materials['drycleaning_cost'];
$total_spotcleaning_cost = $row_paid_materials['spotcleaning_cost'];

// Calculate total value of paid materials
$total_paid_materials = $total_laundry_cost + $total_drycleaning_cost + $total_spotcleaning_cost;



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f8ff; /* Light Blue background */
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background: linear-gradient(to right, lightblue, blue); /* Gradient from blue to aurora blue */
            border-radius: 0 0 10px 10px; /* Rounded corners at the bottom */
            position: relative;
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

        .logout-button {
            color: red;
            font-size: 1rem;
            margin-top: 10px;
            position: left;
            left: 10px;
        }

        .menu {
            width: 200px;
            background-color: #add8e6; /* Light Blue background for the menu */
            height: calc(100vh - 20px); /* Adjusted height considering margin */
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 60px;
        }

        .menu-item {
            padding: 10px 20px;
            border-bottom: 1px solid #87cefa; /* Light Sky Blue border between menu items */
        }

        .content {
            margin-left: 220px; /* Adjusted margin to avoid overlapping with the menu */
            padding: 20px;
        }

        .section {
            background-color: #ffffff; /* White background for sections */
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .stat-display {
            background-color: #87cefa; /* Light Sky Blue background for statistical displays */
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-align: center;
        }

        .dummy-button {
            margin-right: 10px;
        }
    .custom-card .card-text {
    font-size: 1.2rem;
}
.custom-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease-in-out;
    background-color: #f8f9fa; /* Light gray background color */
}

.custom-card:hover {
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.custom-card .card-body {
    text-align: center;
}

.custom-card .card-title {
    color: #4e7aad; /* Blue color for card titles */
    font-weight: bold;
}

.custom-card .card-text {
    font-size: 1.2rem;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CleanTing</a>
            <span class="welcome-message">Welcome to CleanTing, <?php echo isset($_SESSION['admin_firstname']) ? $_SESSION['admin_firstname'] : 'Admin'; ?></span>
            <a href="adminlogout.php" class="btn btn-danger logout-button">Logout</a>
            <!-- Add your navigation links here -->
        </div>
    </nav>

    <div class="menu">
        <div> <label class="logo">CleanTing</label> </div>
        <div class="menu-item">Dashboard</div>
        <div class="menu-item">
        <a href="clients.php" class="btn btn-primary dashboard-button" >Clients</a>
        </div>
        <div class="menu-item">
            <a href="laundryorders.php" class="btn btn-primary dashboard-button" >Orders</a>
        </div> <div class="menu-item">
    <div class="menu-item">
        <a href="clientsorders.php" class="btn btn-primary dashboard-button" >ClientOrders</a>
    </div>
            <a href="adjustprices.php" class="btn btn-primary dashboard-button" >Adjust Prices</a>
        </div>
        <div class="menu-item">
        <a href="signin.php" class="btn btn-primary dashboard-button" >Add Client</a>
        </div>
        <div class="menu-item">
        <a href="transactions.php" class="btn btn-success" >Trasanctions</a>
        </div>
        <div class="menu-item">Settings</div>
    </div>

    <div class="content">
    <div class="section">
        <div class="row">
            <div class="col-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Number of Clients</h5>
                        <p class="card-text"><?php echo $total_clients; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Number of Laundry Orders</h5>
                        <p class="card-text"><?php echo $total_laundry_orders; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Number of Dry Cleaning Orders</h5>
                        <p class="card-text"><?php echo $total_drycleaning_orders; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Number of Spot Cleaning Orders</h5>
                        <p class="card-text"><?php echo $total_spotcleaning_orders; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text"><?php echo $total_orders; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
    <h2>Total Orders by Cleaning Type</h2>
    <div class="row">
        <div class="col-md-6">
            <canvas id="orderChart2" width="400" height="200"></canvas>
        </div>
        
        <div class="col-md-6">
        <h2>Current materials in transit</h2>
        <canvas id="orderChart1" width="400" height="200"></canvas>

        </div>
    </div> <div class="section">

    <h2>Share of Total Value by Cleaning Type</h2>
    <div class="row">
        <div class="col-md-6">
    <canvas id="pieChart" width="50px" height="20px"></canvas>
</div>
</div>
</div>
</div>

        <div class="section">
            <h2>Settings</h2>
            <button class="btn btn-primary dummy-button">Save Changes</button>
            <button class="btn btn-secondary dummy-button">Cancel</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Get the canvas element
        var ctx = document.getElementById('orderChart2').getContext('2d');
        
        // Data for the chart
        var data = {
            labels: ['Laundry', 'Dry Cleaning', 'Spot Cleaning'],
            datasets: [{
                label: 'Number of Orders',
                data: [<?php echo $total_laundry_orders; ?>, <?php echo $total_drycleaning_orders; ?>, <?php echo $total_spotcleaning_orders; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)', // Red color for laundry
                    'rgba(54, 162, 235, 0.5)', // Blue color for dry cleaning
                    'rgba(255, 206, 86, 0.5)'  // Yellow color for spot cleaning
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)', // Red color for laundry
                    'rgba(54, 162, 235, 1)', // Blue color for dry cleaning
                    'rgba(255, 206, 86, 1)'  // Yellow color for spot cleaning
                ],
                borderWidth: 1
            }]
        };

        // Chart configuration
        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Create the bar chart
        var orderChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
       
        
        var ctxPie = document.getElementById('pieChart').getContext('2d');

// Calculate the percentage of each type of cleaning
var totalValue = <?php echo $total_paid_materials; ?>;
var laundryPercentage = <?php echo $total_laundry_cost; ?> / totalValue * 100;
var drycleaningPercentage = <?php echo $total_drycleaning_cost; ?> / totalValue * 100;
var spotcleaningPercentage = <?php echo $total_spotcleaning_cost; ?> / totalValue * 100;

// Labels for the pie chart with total value and percentage
var labels = [
    'Laundry: $<?php echo $total_laundry_cost; ?> (' + laundryPercentage.toFixed(2) + '%)',
    'Dry Cleaning: $<?php echo $total_drycleaning_cost; ?> (' + drycleaningPercentage.toFixed(2) + '%)',
    'Spot Cleaning: $<?php echo $total_spotcleaning_cost; ?> (' + spotcleaningPercentage.toFixed(2) + '%)'
];
var pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Laundry', 'Dry Cleaning', 'Spot Cleaning'],
        datasets: [{
            label: 'Share of Total Value',
            data: [<?php echo $total_laundry_cost; ?>, <?php echo $total_drycleaning_cost; ?>, <?php echo $total_spotcleaning_cost; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)', // Red color for laundry
                'rgba(54, 162, 235, 0.5)', // Blue color for dry cleaning
                'rgba(255, 206, 86, 0.5)'  // Yellow color for spot cleaning
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)', // Red color for laundry
                'rgba(54, 162, 235, 1)', // Blue color for dry cleaning
                'rgba(255, 206, 86, 1)'  // Yellow color for spot cleaning
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        var label = context.label || '';
                        var value = context.parsed || 0;
                        var percentage = (value / <?php echo $total_paid_materials; ?> * 100).toFixed(2);
                        return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

    </script>
        <script src="dashboard.js"></script>

</body>
</html>