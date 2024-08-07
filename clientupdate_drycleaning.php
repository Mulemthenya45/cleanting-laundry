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

// Check if drycleaningid is set and not empty
if(isset($_POST['drycleaningid']) && !empty($_POST['drycleaningid'])) {
    // Retrieve drycleaningid from POST data
    $drycleaningid = $_POST['drycleaningid'];

    // Fetch dry cleaning details based on drycleaningid
    $sql = "SELECT * FROM drycleaningdata WHERE id = $drycleaningid";
    $result = $conn->query($sql);

    // Check if there is exactly one row found
    if ($result->num_rows == 1) {
        // Fetch the row
        $row = $result->fetch_assoc();
        // Store the retrieved values in variables
        $material_type = $row['material_type'];
        $quantity = $row['quantity'];
        $cost = $row['cost'];
        $special_treatment = $row['special_treatment'];
        $weight = $row['weight'];
    } else {
        // Redirect to an error page or handle the error accordingly
        echo "Error: No dry cleaning data found for the provided dry cleaning ID.";
        exit();
    }
} else {
    // Redirect to an error page or handle the error accordingly
    echo "Error: Dry cleaning ID not provided.";
    exit();
}

function getMaterialRates($conn) {
    $materialRates = array();
    $sql = "SELECT material_type, rate FROM drycleaning_rates"; // Corrected table name to 'drycleaning_rates'

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $materialRates[$row['material_type']] = $row['rate'];
        }
    }

    return $materialRates;
}

function getSpecialTreatmentOptions($conn) {
    $specialTreatments = array();
    $sql = "SELECT treatment_type, rate FROM special_treatment_rates"; // Adjust table name as needed

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $specialTreatments[$row['treatment_type']] = $row['rate'];
        }
    }

    return $specialTreatments;
}

$materialRates = getMaterialRates($conn);
$specialTreatments = getSpecialTreatmentOptions($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Dry Cleaning</title>
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
        <h1 class="mt-3">Update Dry Cleaning</h1>

        <form action="clientupdatadrycleaningprocess.php" method="post">
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>Material Type:</strong></td>
                        <td><?php echo $material_type; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Weight (kg):</strong></td>
                        <td><input type="text" class="form-control" name="weight" value="<?php echo $weight; ?>"></td>
                    </tr>
                    <td><strong>Special Treatment:</strong></td>
                        <td>
                            <select class="form-control" name="special_treatment">
                                <?php
                                // Output special treatment options as select dropdown options
                                foreach ($specialTreatments as $treatmentType => $rate) {
                                    $selected = ($treatmentType == $special_treatment) ? "selected" : "";
                                    echo "<option value='$treatmentType' $selected>$treatmentType</option>";
                                }
                                ?>
                            </select>
                        </td>
                    <tr>
                        <td><strong>Quantity:</strong></td>
                        <td><input type="number" class="form-control" name="quantity" value="<?php echo $quantity; ?>"></td>
                    </tr>
                    <tr>
                        <td><strong>Cost:</strong></td>
                        <td><input type="text" class="form-control" name="cost" value="<?php echo $cost; ?>" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="button" class="btn btn-primary" onclick="calculateCost()">Calculate</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="drycleaningid" value="<?php echo $drycleaningid; ?>">
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <script>
    function calculateCost() {
        // Fetch the selected material, weight, special treatment, and quantity
        var weight = parseFloat(document.querySelector("input[name='weight']").value);
        var quantity = parseFloat(document.querySelector("input[name='quantity']").value);
        var specialTreatment = document.querySelector("select[name='special_treatment']").value;

        // Log the fetched values for debugging
        console.log("Weight:", weight);
        console.log("Quantity:", quantity);
        console.log("Special Treatment:", specialTreatment);

        // Fetch the rate of the selected material from the database
        var materialRate = <?php echo $materialRates[$material_type]; ?>;
        
        // Fetch the rate of the selected special treatment from the array based on the selected treatment type
        var specialTreatmentRate = <?php echo json_encode($specialTreatments); ?>[specialTreatment];

        // Log the fetched rates for debugging
        console.log("Material Rate:", materialRate);
        console.log("Special Treatment Rate:", specialTreatmentRate);

        // Calculate the total cost
        var cost = weight * materialRate * quantity * specialTreatmentRate;

        // Update the cost field with the calculated cost
        document.querySelector("input[name='cost']").value = cost.toFixed(2);
    }
</script>


</body>
</html>
