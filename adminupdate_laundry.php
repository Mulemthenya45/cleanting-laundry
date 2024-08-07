<?php
// Start the session


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

// Check if laundryid is set and not empty
if(isset($_POST['laundryid']) && !empty($_POST['laundryid'])) {
    // Retrieve laundryid from POST data
    $laundryid = $_POST['laundryid'];

    // Fetch laundry details based on laundryid
    $sql = "SELECT * FROM laundrydata WHERE laundryid = $laundryid";
    $result = $conn->query($sql);

    // Check if there is exactly one row found
    if ($result->num_rows == 1) {
        // Fetch the row
        $row = $result->fetch_assoc();
        // Store the retrieved values in variables
        $material = $row['material'];
        $weight = $row['weight'];
        $soap = $row['soap'];
        $quantity = $row['quantity'];
        $cost = $row['cost'];
    } else {
        // Redirect to an error page or handle the error accordingly
        echo "Error: No laundry data found for the provided laundry ID.";
        exit();
    }
} else {
    // Redirect to an error page or handle the error accordingly
    echo "Error: Laundry ID not provided.";
    exit();
}

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

$materialRates = getMaterialRates($conn);
$soapRates = getSoapRates($conn);
echo "<script>";
echo "var soapRates = " . json_encode($soapRates) . ";";
echo "</script>";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Laundry</title>
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
        <h1 class="mt-3">Update Laundry</h1>

        <form action="adminupdatelaundryprocess.php" method="post">
        <table id="laundryTable" class="table">
    <thead>
        <tr>
            <th>Laundry Material</th>
            <th>Weight (kg)</th>
            <th>Soap Type</th>
            <th>Quantity</th>
            <th>Cost</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr id="row-0">
        <td>
        <input type="hidden" name="material" value="<?php echo $material; ?>">
        <?php echo $material; ?>
    </td>
            <td><input type="number" class="form-control" placeholder="Enter weight" id="weight-0" name="weight" value="<?php echo $weight; ?>"></td>
            <td>
                            <select class="form-select" name="soap">
                            <?php
                            // Fetch soap type names and rates from the database
                            $sql = "SELECT soap_type_name, rate FROM soaps";
                            $result = $conn->query($sql);

                            // Check if there are rows in the result
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    // Check if the current soap type matches the initial soap type
                                    if ($row["soap_type_name"] == $soap) {
                                        // Output the initial soap type as selected
                                        echo "<option value='" . $row["soap_type_name"] . "' selected>" . $row["soap_type_name"] . "</option>";
                                    } else {
                                        // Output other soap types
                                        echo "<option value='" . $row["soap_type_name"] . "'>" . $row["soap_type_name"] . "</option>";
                                    }
                                }
                            } else {
                                echo "<option value=''>No soap types found</option>";
                            }
                            ?>
                            </select>
            </td>
            <td><input type="number" class="form-control" placeholder="Enter quantity" id="quantity-0" name="quantity" value="<?php echo $quantity; ?>"></td>
            <td><input type="text" class="form-control" id="cost-0" name="cost" value="<?php echo $cost; ?>" readonly></td>
            <td>
                <button type="button" class="btn btn-primary" onclick="calculateCost(0)">Calculate</button>
                <button type="submit" class="btn btn-success">Update</button>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">Total Cost</td>
            <td id="totalCost">0.00</td>
        </tr>
    </tfoot>
</table>
<input type="hidden" name="laundryid" value="<?php echo $laundryid; ?>">
<input type="hidden" name="cost" id="hidden-cost">

        </form>
    </div>
    <script>
function calculateCost(rowId) {
    // Fetch the selected material, soap type, weight, and quantity
    var material = '<?php echo $material; ?>';
    var soap = document.querySelector("[name='soap']").value;
    var weight = document.getElementById('weight-' + rowId).value;
    var quantity = document.getElementById('quantity-' + rowId).value;

    // Fetch the rate of the selected material from the database
    var materialRate = <?php echo $materialRates[$material]; ?>;

    // Fetch the rate of the selected soap type from the SOAP rates array
    var soapRate = soapRates[soap];
    
    // Calculate the total cost
    var cost = (parseFloat(weight) * materialRate * parseFloat(quantity) * soapRate).toFixed(2);

    // Update the cost field with the calculated cost
    document.getElementById('cost-' + rowId).value = cost;

    // Set the calculated cost value to the hidden input field
    document.getElementById('hidden-cost').value = cost;
}
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
