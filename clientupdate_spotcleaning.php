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

// Check if spotcleaningid is set and not empty
if (isset($_POST['spotcleaningid']) && !empty($_POST['spotcleaningid'])) {
    // Retrieve spotcleaningid from POST data
    $spotcleaningid = $_POST['spotcleaningid'];

    // Fetch spot cleaning details based on spotcleaningid
    $sql = "SELECT * FROM spot_cleaning_data WHERE id = $spotcleaningid";
    $result = $conn->query($sql);

    // Check if there is exactly one row found
    if ($result->num_rows == 1) {
        // Fetch the row
        $row = $result->fetch_assoc();
        // Store the retrieved values in variables
        $material = $row['material'];
        $stainType = $row['stainType'];
        $stainSize = $row['stainSize'];
        $quantity = $row['quantity'];
        $cost = $row['cost'];
    } else {
        // Redirect to an error page or handle the error accordingly
        echo "Error: No spot cleaning data found for the provided spot cleaning ID.";
        exit();
    }
} else {
    // Redirect to an error page or handle the error accordingly
    echo "Error: Spot Cleaning ID not provided.";
    exit();
}

// Function to fetch material rates from the database
function getMaterialRates($conn) {
    $materialRates = array();
    $sql = "SELECT material_name, rate FROM laundryprice"; // Change 'rates_table' to your actual rates table name 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $materialRates[$row['material_name']] = $row['rate'];
        }
    }

    return $materialRates;
}

// Function to fetch stain type rates from the database
function getStainTypeRates($conn) {
    $stainTypeRates = array();
    $sql = "SELECT StainType, Rate FROM stains"; // Change 'stain_types' to your actual stain types rates table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stainTypeRates[$row['StainType']] = $row['Rate'];
        }
    }

    return $stainTypeRates;
}

$materialRates = getMaterialRates($conn);
$stainTypeRates = getStainTypeRates($conn);

echo "<script>";
echo "var stainTypeRates = " . json_encode($stainTypeRates) . ";";
echo "</script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Spot Cleaning</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        .btn-update {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Update Spot Cleaning</h1>

        <form action="clientspotcleaningprocess.php" method="post">
            <table class="table">
                <tr>
                    <th>Material Type</th>
                    <th>Stain Type</th>
                    <th>Stain Size (Enter manually)</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" id="material" name="material" value="<?php echo $material; ?>" readonly></td>
                    <td>
                        <select class="form-select" id="stainType" name="stainType">
                            <?php
                            // Fetch stain type names and rates from the database
                            $sql = "SELECT StainType, rate FROM stains";
                            $result = $conn->query($sql);

                            // Check if there are rows in the result
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    // Check if the current stain type matches the initial stain type
                                    if ($row["StainType"] == $stainType) {
                                        // Output the initial stain type as selected
                                        echo "<option value='" . $row["StainType"] . "' selected>" . $row["StainType"] . "</option>";
                                    } else {
                                        // Output other stain types
                                        echo "<option value='" . $row["StainType"] . "'>" . $row["StainType"] . "</option>";
                                    }
                                }
                            } else {
                                echo "<option value=''>No stain types found</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="stainSize" name="stainSize" value="<?php echo $stainSize; ?>">
                    </td>
                    <td><input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>"></td>
                    <td><input type="text" class="form-control" id="cost" name="cost" value="<?php echo $cost; ?>" readonly></td>
                </tr>
            </table>
            <button type="button" class="btn btn-primary" onclick="calculateCost()">Calculate</button>
            <button type="submit" class="btn btn-success">Update</button>
            <input type="hidden" name="spotcleaningid" value="<?php echo $spotcleaningid; ?>">
            <input type="hidden" name="cost" id="hidden-cost">
        </form>
    </div>
    <script>
    function calculateCost() {
        // Fetch the selected stain type, stain size, quantity
        var stainType = document.getElementById('stainType').value;
        var stainSize = document.getElementById('stainSize').value;
        var quantity = document.getElementById('quantity').value;

        // Fetch the rate of the selected stain type from the stain type rates array
        var stainTypeRate = stainTypeRates[stainType];

        // Fetch the rate of the selected material
        var materialRate = <?php echo $materialRates[$material]; ?>;
        
        // Convert the stain size to a number
        var stainSizeValue = parseFloat(stainSize);

        // Calculate the total cost
        var cost = (materialRate * stainTypeRate * stainSizeValue * quantity).toFixed(2);

        // Update the cost field with the calculated cost
        document.getElementById('cost').value = cost;

        // Set the calculated cost value to the hidden input field
        document.getElementById('hidden-cost').value = cost;
    }
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
