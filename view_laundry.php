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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch laundry data for the current client
$sqlLaundry = "SELECT * FROM laundrydata WHERE clientid = $clientid";
$resultLaundry = $conn->query($sqlLaundry);

// Check if there are rows in the result for laundry data
$laundryData = array();
if ($resultLaundry->num_rows > 0) {
    while ($row = $resultLaundry->fetch_assoc()) {
        $laundryData[] = $row;
    }
}

// Fetch dry cleaning data for the current client
$sqlDryCleaning = "SELECT * FROM drycleaningdata WHERE clientid = $clientid";
$resultDryCleaning = $conn->query($sqlDryCleaning);

// Check if there are rows in the result for dry cleaning data
$dryCleaningData = array();
if ($resultDryCleaning->num_rows > 0) {
    while ($row = $resultDryCleaning->fetch_assoc()) {
        $dryCleaningData[] = $row;
    }
}

// Fetch spot cleaning data for the current client
$sqlSpotCleaning = "SELECT * FROM spot_cleaning_data WHERE clientid = $clientid";
$resultSpotCleaning = $conn->query($sqlSpotCleaning);

// Check if there are rows in the result for spot cleaning data
$spotCleaningData = array();
if ($resultSpotCleaning->num_rows > 0) {
    while ($row = $resultSpotCleaning->fetch_assoc()) {
        $spotCleaningData[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

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
            <?php if (!empty($laundryData)) : ?>
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
                            <th>Timestamp</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($laundryData as $laundry) : ?>
    <tr>
        <td><?php echo $laundry['laundryid']; ?></td>
        <td><?php echo $laundry['material']; ?></td>
        <td><?php echo $laundry['weight']; ?></td>
        <td><?php echo $laundry['soap']; ?></td>
        <td><?php echo $laundry['quantity']; ?></td>
        <td><?php echo $laundry['cost']; ?></td>
        <td><?php echo $laundry['status']; ?></td>
        <td><?php echo $laundry['timestamp']; ?></td>
        <td>
            <?php if ($laundry['status'] != 'completed' && $laundry['status'] != 'paid') : ?>
                <form action="clientupdate_laundry.php" method="post">
                    <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($laundry['status'] != 'completed' && $laundry['status'] != 'paid') : ?>
                <form id="deleteForm_<?php echo $laundry['laundryid']; ?>" action="delete_laundry.php" method="post">
                    <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $laundry['laundryid']; ?>)">Delete</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

                    </tbody>
                </table>
            <?php else : ?>
                <p>No laundry data available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Repeat the same structure for Dry Cleaning and Spot Cleaning sections -->
    <!-- Dry Cleaning section -->
    <div class="container">
        <h1 class="mt-3">Your Dry Cleaning Data</h1>

        <div class="table-container">
            <?php if (!empty($dryCleaningData)) : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dry Cleaning ID</th>
                            <th>Material Type</th>
                            <th>Weight</th>
                            <th>Special Treatment Type</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Timestamp</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dryCleaningData as $dryCleaning) : ?>
                            <tr>
                                <td><?php echo $dryCleaning['id']; ?></td>
                                <td><?php echo $dryCleaning['material_type']; ?></td>
                                <td><?php echo $dryCleaning['weight']; ?></td>
                                <td><?php echo $dryCleaning['special_treatment']; ?></td>
                                <td><?php echo $dryCleaning['quantity']; ?></td>
                                <td><?php echo $dryCleaning['cost']; ?></td>
                                <td><?php echo $dryCleaning['status']; ?></td>
                                <td><?php echo $dryCleaning['created_at']; ?></td>
                                <td>
                                    <?php if ($dryCleaning['status'] != 'completed' && $dryCleaning['status'] != 'paid') : ?>
                                        <form action="clientupdate_drycleaning.php" method="post">
                                            <input type="hidden" name="drycleaningid" value="<?php echo $dryCleaning['id']; ?>">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($dryCleaning['status'] != 'completed' && $dryCleaning['status'] != 'paid') : ?>
                                        <form id="deleteDryCleaningForm_<?php echo $dryCleaning['id']; ?>" action="delete_drycleaning.php" method="post">
                                            <input type="hidden" name="drycleaningid" value="<?php echo $dryCleaning['id']; ?>">
                                            <button type="button" class="btn btn-danger" onclick="confirmDeleteDryCleaning(<?php echo $dryCleaning['id']; ?>)">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No dry cleaning data available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Spot Cleaning section -->
    <div class="container">
        <h1 class="mt-3">Your Spot Cleaning Data</h1>

        <div class="table-container">
            <?php if (!empty($spotCleaningData)) : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Spot Cleaning ID</th>
                            <th>Material Type</th>
                            <th>Stain Type</th>
                            <th>Stain Size</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Timestamp</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($spotCleaningData as $spotCleaning) : ?>
                            <tr>
                                <td><?php echo $spotCleaning['id']; ?></td>
                                <td><?php echo $spotCleaning['material']; ?></td>
                                <td><?php echo $spotCleaning['stainType']; ?></td>
                                <td><?php echo $spotCleaning['stainSize']; ?></td>
                                <td><?php echo $spotCleaning['quantity']; ?></td>
                                <td><?php echo $spotCleaning['cost']; ?></td>
                                <td><?php echo $spotCleaning['status']; ?></td>
                                <td><?php echo $spotCleaning['timestamp']; ?></td>
                                <td>
                                    <?php if ($spotCleaning['status'] != 'completed' && $spotCleaning['status'] != 'paid') : ?>
                                        <form action="clientupdate_spotcleaning.php" method="post">
                                            <input type="hidden" name="spotcleaningid" value="<?php echo $spotCleaning['id']; ?>">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($spotCleaning['status'] != 'completed' && $spotCleaning['status'] != 'paid') : ?>
                                        <form id="deleteSpotCleaningForm_<?php echo $spotCleaning['id']; ?>" action="delete_spotcleaning.php" method="post">
                                            <input type="hidden" name="spotcleaningid" value="<?php echo $spotCleaning['id']; ?>">
                                            <button type="button" class="btn btn-danger" onclick="confirmDeleteSpotCleaning(<?php echo $spotCleaning['id']; ?>)">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No spot cleaning data available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function confirmDelete(laundryId) {
        if (confirm('Are you sure you want to delete this row?')) {
            // If the user clicks Yes, submit the form for deletion
            document.getElementById('deleteForm_' + laundryId).submit();
        } else {
            // If the user clicks No, do nothing
            return false;
        }
    }
    function confirmDeleteDryCleaning(dryCleaningId) {
    if (confirm('Are you sure you want to delete this dry cleaning record?')) {
        // If the user clicks Yes, submit the form for deletion
        document.getElementById('deleteDryCleaningForm_' + dryCleaningId).submit();
    } else {
        // If the user clicks No, do nothing
        return false;
    }
}
function confirmDeleteSpotCleaning(spotCleaningId) {
        if (confirm('Are you sure you want to delete this spot cleaning order?')) {
            // If the user clicks Yes, submit the form for deletion
            document.getElementById('deleteSpotCleaningForm_' + spotCleaningId).submit();
        } else {
            // If the user clicks No, do nothing
            return false;
        }
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
