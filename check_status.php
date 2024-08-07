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

// Fetch laundry data for the current client using a prepared statement
$sql = "SELECT laundryid, material, weight, soap, quantity, cost, status FROM laundrydata WHERE clientid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $clientid);
$stmt->execute();
$result = $stmt->get_result();

// Check for errors in executing the prepared statement
if (!$stmt) {
    echo "Error in executing prepared statement for laundry data: " . $conn->error;
} elseif (!$result) {
    echo "Error in fetching laundry data: " . $conn->error;
} else {
    // Check if there are rows in the result
    $pendingLaundry = array();
    $inProgressLaundry = array();
    $completedLaundry = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Store all columns in the $row array
            switch ($row['status']) {
                case 'pending':
                    $pendingLaundry[] = $row;
                    break;
                case 'InProgress':
                    $inProgressLaundry[] = $row;
                    break;
                case 'completed':
                    $completedLaundry[] = $row;
                    break;
                default:
                    // Handle unexpected status values if needed
                    break;
            }
        }
    } else {
        echo "No laundry data found for the current client.";
    }
}

// Close the prepared statement
$stmt->close();

// Fetch dry cleaning data for the current client using a prepared statement
$sql_dry = "SELECT id, material_type, weight, quantity, cost, status FROM drycleaningdata WHERE clientid = ?";
$stmt_dry = $conn->prepare($sql_dry);
$stmt_dry->bind_param("i", $clientid);
$stmt_dry->execute();
$result_dry = $stmt_dry->get_result();

// Check for errors in executing the prepared statement
if (!$stmt_dry) {
    echo "Error in executing prepared statement for dry cleaning data: " . $conn->error;
} elseif (!$result_dry) {
    echo "Error in fetching dry cleaning data: " . $conn->error;
} else {
    // Check if there are rows in the result
    $pendingDryCleaning = array();
    $inProgressDryCleaning = array();
    $completedDryCleaning = array();

    if ($result_dry->num_rows > 0) {
        while ($row_dry = $result_dry->fetch_assoc()) {
            // Store all columns in the $row array
            switch ($row_dry['status']) {
                case 'pending':
                    $pendingDryCleaning[] = $row_dry;
                    break;
                case 'InProgress':
                    $inProgressDryCleaning[] = $row_dry;
                    break;
                case 'completed':
                    $completedDryCleaning[] = $row_dry;
                    break;
                default:
                    // Handle unexpected status values if needed
                    break;
            }
        }
    } else {
        echo "No dry cleaning data found for the current client.";
    }
}

// Close the prepared statement
$stmt_dry->close();
?>

<!-- Your HTML and display code goes here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Laundry</title>
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
        <span class="welcome-message">Welcome to CleanTing, <?php echo $name; ?>! </span>
        <div class="action-buttons">
            <a href="userhome.php" class="btn btn-primary">Home</a>
            <a href="logout.php" class="btn btn-danger logout-button">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3"> Your Recent Laundry</h1>

        <div class="table-container">
            <?php if (!empty($pendingLaundry)) : ?>
                <h2>Pending Laundry</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Laundry ID</th>
                            <th>Material</th>
                            <th>Weight (kg)</th>
                            <th>Soap Type</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php foreach ($pendingLaundry as $laundry) : ?>
                <tr>
                    <td><?php echo $laundry['laundryid']; ?></td>
                    <td><?php echo $laundry['material']; ?></td>
                    <td><?php echo $laundry['weight']; ?></td>
                    <td><?php echo $laundry['soap']; ?></td>
                    <td><?php echo $laundry['quantity']; ?></td>
                    <td><?php echo $laundry['cost']; ?></td>
                    <td><?php echo $laundry['status']; ?></td>
                    <td>
                        <form action="clientupdate_laundry.php" method="post">
                            <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                    <td>
                        <form id="deleteForm_<?php echo $laundry['laundryid']; ?>" action="delete_laundry.php" method="post">
                            <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $laundry['laundryid']; ?>)">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
                </table>
            <?php endif; ?>
            <?php if (!empty($inProgressLaundry)) : ?>
    <h2>In Progress Laundry</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Laundry ID</th>
                <th>Material</th>
                <th>Weight (kg)</th>
                <th>Soap Type</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inProgressLaundry as $laundry) : ?>
                <tr>
                    <td><?php echo $laundry['laundryid']; ?></td>
                    <td><?php echo $laundry['material']; ?></td>
                    <td><?php echo $laundry['weight']; ?></td>
                    <td><?php echo $laundry['soap']; ?></td>
                    <td><?php echo $laundry['quantity']; ?></td>
                    <td><?php echo $laundry['cost']; ?></td>
                    <td><?php echo $laundry['status']; ?></td>
                  
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if (!empty($completedLaundry)) : ?>
    <h2>Completed Laundry</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Laundry ID</th>
                <th>Material</th>
                <th>Weight (kg)</th>
                <th>Soap Type</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($completedLaundry as $laundry) : ?>
                <tr>
                    <td><?php echo $laundry['laundryid']; ?></td>
                    <td><?php echo $laundry['material']; ?></td>
                    <td><?php echo $laundry['weight']; ?></td>
                    <td><?php echo $laundry['soap']; ?></td>
                    <td><?php echo $laundry['quantity']; ?></td>
                    <td><?php echo $laundry['cost']; ?></td>
                    <td><?php echo $laundry['status']; ?></td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

            <!-- Repeat the above structure for InProgress and Completed laundry -->
       
        </div>
        
    </div>
    <?php if (!empty($pendingDryCleaning)) : ?>
    <h2>Pending Dry Cleaning</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Dry Cleaning ID</th>
                <th>Material Type</th>
                <th>Weight</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Status</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingDryCleaning as $dryclean) : ?>
                <tr>
                    <td><?php echo $dryclean['id']; ?></td>
                    <td><?php echo $dryclean['material_type']; ?></td>
                    <td><?php echo $dryclean['weight']; ?></td>
                    <td><?php echo $dryclean['quantity']; ?></td>
                    <td><?php echo $dryclean['cost']; ?></td>
                    <td><?php echo $dryclean['status']; ?></td>
                    <td>
                        <form action="clientupdate_drycleaning.php" method="post">
                            <input type="hidden" name="drycleanid" value="<?php echo $dryclean['id']; ?>">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                    <td>
                        <form id="deleteDryForm_<?php echo $dryclean['id']; ?>" action="delete_drycleaning.php" method="post">
                            <input type="hidden" name="drycleanid" value="<?php echo $dryclean['id']; ?>">
                            <button type="button" class="btn btn-danger" onclick="confirmDryDelete(<?php echo $dryclean['id']; ?>)">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if (!empty($inProgressDryCleaning)) : ?>
    <h2>In Progress Dry Cleaning</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Dry Cleaning ID</th>
                <th>Material Type</th>
                <th>Weight</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inProgressDryCleaning as $dryclean) : ?>
                <tr>
                    <td><?php echo $dryclean['id']; ?></td>
                    <td><?php echo $dryclean['material_type']; ?></td>
                    <td><?php echo $dryclean['weight']; ?></td>
                    <td><?php echo $dryclean['quantity']; ?></td>
                    <td><?php echo $dryclean['cost']; ?></td>
                    <td><?php echo $dryclean['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if (!empty($completedDryCleaning)) : ?>
    <h2>Completed Dry Cleaning</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Dry Cleaning ID</th>
                <th>Material Type</th>
                <th>Weight</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($completedDryCleaning as $dryclean) : ?>
                <tr>
                    <td><?php echo $dryclean['id']; ?></td>
                    <td><?php echo $dryclean['material_type']; ?></td>
                    <td><?php echo $dryclean['weight']; ?></td>
                    <td><?php echo $dryclean['quantity']; ?></td>
                    <td><?php echo $dryclean['cost']; ?></td>
                    <td><?php echo $dryclean['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
