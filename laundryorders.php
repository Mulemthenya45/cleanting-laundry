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

// Fetch laundry data for the current client
$sql = "SELECT * FROM laundrydata WHERE status != 'paid'";
$result = $conn->query($sql);

// Check if there are rows in the result
$laundryData = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $laundryData[] = $row;
    }
}
$sqlDryCleaning = "SELECT * FROM drycleaningdata WHERE status != 'paid'";
$resultDryCleaning = $conn->query($sqlDryCleaning);

// Check if there are rows in the result
$dryCleaningData = array();
if ($resultDryCleaning->num_rows > 0) {
    while ($row = $resultDryCleaning->fetch_assoc()) {
        $dryCleaningData[] = $row;
    }
}
$sqlSpotCleaning = "SELECT * FROM spot_cleaning_data WHERE status != 'paid'";
$resultSpotCleaning = $conn->query($sqlSpotCleaning);

// Check if there are rows in the result
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
        <span class="welcome-message">Welcome to CleanTing Admin </span>
        <div class="action-buttons">
            <a href="admindash.php" class="btn btn-primary">Dashboard</a>
            <a href="adminhome.php" class="btn btn-danger logout-button">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3"> Our Laundry Orders </h1>

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
                            <th>Update</th>
                            <th>Status</th>
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
        <td>
            <form action="adminupdate_laundry.php" method="post">
                <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </td>
        <td>
    <form action="update_status.php" method="post">
        <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
        <select name="status" onchange="this.form.submit()">
            <option value="pending" <?php if($laundry['status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="InProgress" <?php if($laundry['status'] == 'InProgress') echo 'selected'; ?>>InProgress</option>
            <option value="completed" <?php if($laundry['status'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select>
    </form>
</td>
        <td>
    <form id="deleteForm_<?php echo $laundry['laundryid']; ?>" action="admindelete_laundry.php" method="post">
        <input type="hidden" name="laundryid" value="<?php echo $laundry['laundryid']; ?>">
        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $laundry['laundryid']; ?>)">Delete</button>
    </form>
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
    
<div class="container">
    <h1 class="mt-3"> Our Dry Cleaning Orders </h1>

    <div class="table-container">
        <?php if (!empty($dryCleaningData)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Dry Cleaning ID</th>
                        <th>material type</th>
                        <th>Quantity</th>
                        <th>special treatment</th>
                        <th>Cost</th>
                        <th>Update</th>
                        <th>Status</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dryCleaningData as $dryCleaning) : ?>
                        <tr>
                            <td><?php echo $dryCleaning['id']; ?></td>
                            <td><?php echo $dryCleaning['material_type']; ?></td>
                            <td><?php echo $dryCleaning['quantity']; ?></td>
                            <td><?php echo $dryCleaning['special_treatment']; ?></td>
                            <td><?php echo $dryCleaning['cost']; ?></td>
                            <td>
                                <form action="adminupdate_drycleaning.php" method="post">
                                    <input type="hidden" name="drycleaningid" value="<?php echo $dryCleaning['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                            <td>
                                <form action="update_drycleaning_status.php" method="post">
                                    <input type="hidden" name="drycleaningid" value="<?php echo $dryCleaning['id']; ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" <?php if ($dryCleaning['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                        <option value="inProgress" <?php if ($dryCleaning['status'] == 'inProgress') echo 'selected'; ?>>In Progress</option>
                                        <option value="completed" <?php if ($dryCleaning['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form id="deleteDryCleaningForm_<?php echo $dryCleaning['id']; ?>" action="admindelete_drycleaning.php" method="post">
                                    <input type="hidden" name="drycleaningid" value="<?php echo $dryCleaning['id']; ?>">
                                    <button type="button" class="btn btn-danger" onclick="confirmDeleteDryCleaning(<?php echo $dryCleaning['id']; ?>)">Delete</button>
                                </form>
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
<div class="container">
        <h1 class="mt-3">Spot Cleaning Orders</h1>

        <div class="table-container">
            <?php if (!empty($spotCleaningData)) : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Spot Cleaning ID</th>
                            <th>Material</th>
                            <th>Stain Type</th>
                            <th>Stain Size</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Update</th>
                            <th>Status</th>
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
                                <td>
                                    <form action="adminupdate_spotcleaning.php" method="post">
                                        <input type="hidden" name="spotcleaningid" value="<?php echo $spotCleaning['id']; ?>">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="update_spotcleaning_status.php" method="post">
                                        <input type="hidden" name="spotcleaningid" value="<?php echo $spotCleaning['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="Pending" <?php if ($spotCleaning['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="inProgress" <?php if ($spotCleaning['status'] == 'inProgress') echo 'selected'; ?>>In Progress</option>
                                            <option value="completed" <?php if ($spotCleaning['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form id="deleteSpotCleaningForm_<?php echo $spotCleaning['id']; ?>" action="admindelete_spotcleaning.php" method="post">
                                        <input type="hidden" name="spotcleaningid" value="<?php echo $spotCleaning['id']; ?>">
                                        <button type="button" class="btn btn-danger" onclick="confirmDeleteSpotCleaning(<?php echo $spotCleaning['id']; ?>)">Delete</button>
                                    </form>
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
        if (confirm('Are you sure you want to delete this dry cleaning order?')) {
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
