<?php
session_start();
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
  
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laundry";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

function getDryMaterialRates($conn) {
    $drymaterialRates = array();
    $sql = "SELECT material_type, rate FROM drycleaning_rates"; // Change 'rates_table' to your actual rates table name 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $drymaterialRates[$row['material_type']] = $row['rate'];
        }
    }

    return $drymaterialRates;
}

function getSpecialTreatmentRates($conn) {
    $specialTreatmentRates = array();
    $sql = "SELECT treatment_type, rate FROM special_treatment_rates"; // Use the table you created for special treatment rates

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $specialTreatmentRates[$row['treatment_type']] = $row['rate'];
        }
    }

    return $specialTreatmentRates;
}
function getStainRates($conn) {
    $stainRates = array();
    $sql = "SELECT StainType, Rate FROM stains"; // Replace 'stain_rates_table' with your actual table name

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stainRates[$row['StainType']] = $row['Rate'];
        }
    }

    return $stainRates;
}
$stainRates = getStainRates($conn);
$specialTreatmentRates = getSpecialTreatmentRates($conn);
$drymaterialRates = getDryMaterialRates($conn);
$materialRates = getMaterialRates($conn);
$soapRates = getSoapRates($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laundry Cost Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
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

        .navbar-message {
            color: white;
            font-size: 1.9rem;
            font-weight: bold;
            font-family: 'Monotype Corsiva', cursive;
            text-align: center;
            flex-grow: 1; 
        }

        ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin-right: 0;
        }

        ul li a {
            text-decoration: none;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
</head>
<body>

<nav> 
    <label class="logo">Cleanting</label> 
    <?php  
    if (isset($_SESSION['name'])) {
        $userName = $_SESSION['name'];
        
        echo "  <p class='navbar-message'>Laundry description for $userName!</p> <ul>
                <li><a href='userhome.php'>Home</a></li>
                <li><a href=''>Contact</a></li>
                <li><a href='pay_laundry.php'>Pay</a></li>
                <li><a href='logout.php' class='btn btn-success'>Sign out</a></li>
            </ul>
           ";
    } else {
        echo "<ul>
                <li><a href='userhome.php'>Home</a></li>
                <li><a href=''>Contact</a></li>
                <li><a href=''>Admission</a></li>
                <li><a href='login.html' class='btn btn-success'>Sign out</a></li>
            </ul>";
    }
    ?>
</nav>
<div class="section1">
    <label class="img_text">We clean best!</label>
    <img class="main_img" src="laundryguy.png" alt="">
</div>

<div class="container mt-5">
    <h2>Laundry Description</h2>
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
                    <select class="form-select" id="material-0">
            <?php
            foreach ($materialRates as $material => $rate) {
                echo "<option value='$material' data-rate='$rate'>$material</option>";
            }
            ?>
        </select>
                    </td>
                    <td><input type="number" class="form-control" placeholder="Enter weight" id="weight-0"></td>
                    <td>
                    <select class="form-select" id="soap-0">
        <?php
        foreach ($soapRates as $soapTypeName => $rate) {
            echo "<option value='$soapTypeName' data-rate='$rate'>$soapTypeName</option>";
        }
        ?>
    </select>
                    </td>
                    <td><input type="number" class="form-control" placeholder="Enter quantity" id="quantity-0"></td>
                    <td id="cost-0">Cost will be calculated</td>
                    <td>
                        <button class="btn btn-primary" onclick="calculateCost(0)">Calculate</button>
                        <button class="btn btn-danger" onclick="removeRow(0)">Remove</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Total Cost</td>
                    <td id="totalCost">0.00</td>
                    <td><button class="btn btn-success" onclick="addRow()">Add Row</button></td>
                </tr>
            </tfoot>
        
            
        </table>
        
        <!-- Add the following button at the end of the table -->
        <button class="btn btn-primary" onclick="submitLaundry()">Submit Laundry</button>
    </div>
    <div class="container mt-5">
    <h2>Dry Cleaning Description</h2>
    <table id="dryCleaningTable" class="table">
        <thead>
            <tr>
                <th>Material Type</th>
                <th>Weight (kg)</th>
                <th>Special Treatment</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr id="dryRow-0">
                <td>
                    <select class="form-select" id="dryMaterial-0">
                        <?php
                        foreach ($drymaterialRates as $material => $rate) {
                            echo "<option value='$material' data-rate='$rate'>$material</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input type="number" class="form-control" placeholder="Enter weight" id="dryWeight-0"></td>
                <td>
                    <select class="form-select" id="specialTreatment-0">
                        <?php
                        foreach ($specialTreatmentRates as $treatment => $rate) {
                            echo "<option value='$treatment' data-rate='$rate'>$treatment</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input type="number" class="form-control" placeholder="Enter quantity" id="dryQuantity-0"></td>
                <td id="dryCost-0">Cost will be calculated</td>
                <td>
                    <button class="btn btn-primary" onclick="calculateDryCleaningCost(0)">Calculate</button>
                    <button class="btn btn-danger" onclick="removeDryCleaningRow(0)">Remove</button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total Cost</td>
                <td id="totalDryCleaningCost">0.00</td>
                <td><button class="btn btn-success" onclick="addDryCleaningRow()">Add Row</button></td>
            </tr>
        </tfoot>
    </table>

    <!-- Add the following button at the end of the table -->
    <button class="btn btn-primary" onclick="submitDryCleaning()">Submit Dry Cleaning</button>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        let rowIndex = 0;
        function calculateCost(index) {
    const table = document.getElementById('laundryTable').getElementsByTagName('tbody')[0];
    const currentRowIndex = index;

    // Calculate cost for the current row
    const materialSelect = document.getElementById(`material-${currentRowIndex}`);
    const materialRate = parseFloat(materialSelect.options[materialSelect.selectedIndex].getAttribute('data-rate')) || 0;

    const weight = parseFloat(document.getElementById(`weight-${currentRowIndex}`).value) || 0;

    const soapSelect = document.getElementById(`soap-${currentRowIndex}`);
    const soapRate = parseFloat(soapSelect.options[soapSelect.selectedIndex].getAttribute('data-rate')) || 0;

    const quantity = parseFloat(document.getElementById(`quantity-${currentRowIndex}`).value) || 0;

    const cost = materialRate * weight * soapRate * quantity;

    document.getElementById(`cost-${currentRowIndex}`).textContent = `$${cost.toFixed(2)}`;

    // Recalculate total cost whenever a cost is updated
    calculateTotalCost();
}

function calculateTotalCost() {
    let totalCost = 0;
    let currentRowIndex = 0;

    while (true) {
        const costCell = document.getElementById(`cost-${currentRowIndex}`);
        if (!costCell) break;

        totalCost += parseFloat(costCell.textContent.replace('$', '')) || 0;
        currentRowIndex++;
    }

    document.getElementById('totalCost').textContent = `$${totalCost.toFixed(2)}`;
}


    function addRow() {
        const table = document.getElementById('laundryTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow(table.rows.length);

        // Clone the first row to get the structure and IDs
        const firstRow = table.rows[0];
        newRow.innerHTML = firstRow.innerHTML;

        // Update IDs to be unique for the new row
        rowIndex++;
        const newRowId = `row-${rowIndex}`;
        newRow.id = newRowId;
        newRow.querySelector(`#material-0`).id = `material-${rowIndex}`;
        newRow.querySelector(`#weight-0`).id = `weight-${rowIndex}`;
        newRow.querySelector(`#soap-0`).id = `soap-${rowIndex}`;
        newRow.querySelector(`#quantity-0`).id = `quantity-${rowIndex}`;
        newRow.querySelector(`#cost-0`).id = `cost-${rowIndex}`;

        // Clear values in the new row
        newRow.querySelector(`#weight-${rowIndex}`).value = '';
        newRow.querySelector(`#quantity-${rowIndex}`).value = '';
        newRow.querySelector(`#cost-${rowIndex}`).textContent = 'Cost will be calculated';

        // Add "Calculate" and "Remove" buttons for the new row
        const actionCell = newRow.querySelector('td:last-child');
        actionCell.innerHTML = `<button class="btn btn-primary" onclick="calculateCost(${rowIndex})">Calculate</button>
                               <button class="btn btn-danger" onclick="removeRow(${rowIndex})">Remove</button>`;

        // Recalculate total cost whenever a new row is added
        calculateTotalCost();
    }

    function removeRow(index) {
    const table = document.getElementById('laundryTable').getElementsByTagName('tbody')[0];
    const rowToRemove = document.getElementById(`row-${index}`);
    const costToRemove = parseFloat(document.getElementById(`cost-${index}`).textContent.replace('$', '')) || 0;

    // Remove the selected row
    table.removeChild(rowToRemove);

    // Update total cost by subtracting the cost of the removed row
    let totalCost = parseFloat(document.getElementById('totalCost').textContent.replace('$', '')) || 0;
    totalCost -= costToRemove;

    document.getElementById('totalCost').textContent = `$${totalCost.toFixed(2)}`;
    calculateTotalCost();
}

    

        function submitLaundry() {
        // Collect laundry data
        const laundryData = [];
        let currentRowIndex = 0;

        while (true) {
            const material = document.getElementById(`material-${currentRowIndex}`);
            if (!material) break;

            const weight = document.getElementById(`weight-${currentRowIndex}`).value;
            const soap = document.getElementById(`soap-${currentRowIndex}`);
            const quantity = document.getElementById(`quantity-${currentRowIndex}`).value;
            const cost = document.getElementById(`cost-${currentRowIndex}`).textContent.replace('$', '');

            laundryData.push({
                clientid: <?php echo $clientid; ?>, // Add this line to include the clientid
                material: material.value,
                weight: weight,
                soap: soap.value,
                quantity: quantity,
                cost: cost,
            });

            currentRowIndex++;
        }

        // Send the data to the backend using AJAX
        fetch('submit_laundry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ laundryData: laundryData }),
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server (if needed)
            console.log(data);

            if (data.success) {
                alert('Laundry data submitted successfully!');
            } else {
                alert('Error submitting laundry data. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting laundry data. Please try again.');
        });
    }

   

    function calculateDryCleaningCost(index) {
        const table = document.getElementById('dryCleaningTable').getElementsByTagName('tbody')[0];
        const currentRowIndex = index;

        // Calculate cost for the current row
        const materialSelect = document.getElementById(`dryMaterial-${currentRowIndex}`);
        const materialRate = parseFloat(materialSelect.options[materialSelect.selectedIndex].getAttribute('data-rate')) || 0;

        const weight = parseFloat(document.getElementById(`dryWeight-${currentRowIndex}`).value) || 0;

        const specialTreatmentSelect = document.getElementById(`specialTreatment-${currentRowIndex}`);
        const treatmentRate = parseFloat(specialTreatmentSelect.options[specialTreatmentSelect.selectedIndex].getAttribute('data-rate')) || 0;

        const quantity = parseFloat(document.getElementById(`dryQuantity-${currentRowIndex}`).value) || 0;

        const cost = materialRate * weight * treatmentRate * quantity;

        document.getElementById(`dryCost-${currentRowIndex}`).textContent = `$${cost.toFixed(2)}`;

        // Recalculate total cost whenever a cost is updated
        calculateTotalDryCleaningCost();
    }

    function calculateTotalDryCleaningCost() {
    let totalCost = 0;
    let currentRowIndex = 0;

    while (true) {
        const costCell = document.getElementById(`dryCost-${currentRowIndex}`);
        if (!costCell) break;

        totalCost += parseFloat(costCell.textContent.replace('$', '')) || 0;
        currentRowIndex++;
    }

    document.getElementById('totalDryCleaningCost').textContent = `$${totalCost.toFixed(2)}`;
}


function addDryCleaningRow() {
    const table = document.getElementById('dryCleaningTable').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow(table.rows.length);

    // Clone the first row to get the structure and IDs
    const firstRow = table.rows[0];
    newRow.innerHTML = firstRow.innerHTML;

    // Update IDs to be unique for the new row
    rowIndex++;
    const newRowId = `dryRow-${rowIndex}`;
    newRow.id = newRowId;
    newRow.querySelector(`#dryMaterial-0`).id = `dryMaterial-${rowIndex}`;
    newRow.querySelector(`#dryWeight-0`).id = `dryWeight-${rowIndex}`;
    newRow.querySelector(`#specialTreatment-0`).id = `specialTreatment-${rowIndex}`;
    newRow.querySelector(`#dryQuantity-0`).id = `dryQuantity-${rowIndex}`;
    newRow.querySelector(`#dryCost-0`).id = `dryCost-${rowIndex}`;

    // Clear values in the new row
    newRow.querySelector(`#dryWeight-${rowIndex}`).value = '';
    newRow.querySelector(`#dryQuantity-${rowIndex}`).value = '';
    newRow.querySelector(`#dryCost-${rowIndex}`).textContent = 'Cost will be calculated';

    // Add "Calculate" and "Remove" buttons for the new row
    const actionCell = newRow.querySelector('td:last-child');
    actionCell.innerHTML = `<button class="btn btn-primary" onclick="calculateDryCleaningCost(${rowIndex})">Calculate</button>
                           <button class="btn btn-danger" onclick="removeDryCleaningRow(${rowIndex})">Remove</button>`;

    // Recalculate total cost whenever a new row is added
    calculateTotalDryCleaningCost();
}


    function removeDryCleaningRow(index) {
        const table = document.getElementById('dryCleaningTable').getElementsByTagName('tbody')[0];
        const rowToRemove = document.getElementById(`dryRow-${index}`);
        const costToRemove = parseFloat(document.getElementById(`dryCost-${index}`).textContent.replace('$', '')) || 0;

        // Remove the selected row
        table.removeChild(rowToRemove);

        // Update total cost by subtracting the cost of the removed row
        let totalCost = parseFloat(document.getElementById('totalDryCleaningCost').textContent.replace('$', '')) || 0;
        totalCost -= costToRemove;

        document.getElementById('totalDryCleaningCost').textContent = `$${totalCost.toFixed(2)}`;
        calculateTotalDryCleaningCost();
    }

    function submitDryCleaning() {
    const dryCleaningData = []; // Array to store dry cleaning data
    let currentRowIndex = 0;

    // Iterate through each row of the dry cleaning table
    while (true) {
        const material = document.getElementById(`dryMaterial-${currentRowIndex}`);
        if (!material) break;

        // Extract data from the current row
        const weight = document.getElementById(`dryWeight-${currentRowIndex}`).value;
        const specialTreatment = document.getElementById(`specialTreatment-${currentRowIndex}`).value;
        const quantity = document.getElementById(`dryQuantity-${currentRowIndex}`).value;
        const cost = document.getElementById(`dryCost-${currentRowIndex}`).textContent.replace('$', '');

        // Construct an object representing the current row of data
        const rowData = {
            material: material.value,
            weight: weight,
            specialTreatment: specialTreatment,
            quantity: quantity,
            cost: cost,
            clientid: <?php echo $clientid; ?> // Add clientid to each row
        };

        // Add the row data object to the array
        dryCleaningData.push(rowData);

        // Move to the next row
        currentRowIndex++;
    }

    // Send the data to the backend using AJAX
    fetch('submit_drycleaning.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ dryCleaningData: dryCleaningData }),
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response from the server
        console.log(data);
        if (data.success) {
            alert('Dry cleaning data submitted successfully!');
        } else {
            alert('Error submitting dry cleaning data. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting dry cleaning data. Please try again.');
    });
}

    </script>

<div class="container mt-5">
        <h2>Spot Cleaning Description</h2>
        <table id="spotCleaningTable" class="table">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Type of Stain</th>
                    <th>Approx size of Stain cm2</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <tr id="spotRow-0">
    <td>
        <select class="form-select" id="spotMaterial-0">
            <?php
            foreach ($materialRates as $material => $rate) {
                echo "<option value='$material' data-rate='$rate'>$material</option>";
            }
            ?>
        </select>
    </td>
    <td>
        <select class="form-select" id="stainType-0">
            <?php
            foreach ($stainRates as $stain => $rate) {
                echo "<option value='$stain' data-rate='$rate'>$stain</option>";
            }
            ?>
        </select>
    </td>
    <td><input type="text" class="form-control" placeholder="Enter stain size" id="stainSize-0"></td>
    <td><input type="number" class="form-control" placeholder="Enter quantity" id="spotQuantity-0"></td>
    <td id="spotCost-0">Cost will be calculated</td>
    <td>
        <button class="btn btn-primary" onclick="calculateSpotCleaningCost(0)">Calculate</button>
        <button class="btn btn-danger" onclick="removeSpotCleaningRow(0)">Remove</button>
    </td>
</tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Total Cost</td>
                    <td id="totalSpotCleaningCost">0.00</td>
                    <td><button class="btn btn-success" onclick="addSpotCleaningRow()">Add Row</button></td>
                </tr>
            </tfoot>
        </table>

       
      <!-- Add the following button at the end of the table -->
<button class="btn btn-primary" onclick="submitSpotCleaning()">Submit Spot Cleaning</button>

    </div>


<script>
let spotRowIndex = 0;

function calculateSpotCleaningCost(index) {
    const table = document.getElementById('spotCleaningTable').getElementsByTagName('tbody')[0];
    const currentRowIndex = index;

    // Calculate cost for the current row
    const stainTypeSelect = document.getElementById(`stainType-${currentRowIndex}`);
    const rate = parseFloat(stainTypeSelect.options[stainTypeSelect.selectedIndex].getAttribute('data-rate')) || 0;

    const stainSize = parseFloat(document.getElementById(`stainSize-${currentRowIndex}`).value) || 0;

    const quantity = parseFloat(document.getElementById(`spotQuantity-${currentRowIndex}`).value) || 0;

    const cost = rate * stainSize * quantity;

    document.getElementById(`spotCost-${currentRowIndex}`).textContent = `$${cost.toFixed(2)}`;

    // Recalculate total cost whenever a cost is updated
    calculateTotalSpotCleaningCost();
}

function calculateTotalSpotCleaningCost() {
    let totalCost = 0;
    let currentRowIndex = 0;

    while (true) {
        const costCell = document.getElementById(`spotCost-${currentRowIndex}`);
        if (!costCell) break;

        totalCost += parseFloat(costCell.textContent.replace('$', '')) || 0;
        currentRowIndex++;
    }

    document.getElementById('totalSpotCleaningCost').textContent = `$${totalCost.toFixed(2)}`;
}

function addSpotCleaningRow() {
    const table = document.getElementById('spotCleaningTable').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow(table.rows.length);

    // Clone the first row to get the structure and IDs
    const firstRow = table.rows[0];
    newRow.innerHTML = firstRow.innerHTML;

    // Update IDs to be unique for the new row
    spotRowIndex++;
    const newRowId = `spotRow-${spotRowIndex}`;
    newRow.id = newRowId;
    newRow.querySelector(`#stainType-0`).id = `stainType-${spotRowIndex}`;
    newRow.querySelector(`#stainSize-0`).id = `stainSize-${spotRowIndex}`;
    newRow.querySelector(`#spotQuantity-0`).id = `spotQuantity-${spotRowIndex}`;
    newRow.querySelector(`#spotCost-0`).id = `spotCost-${spotRowIndex}`;

    // Clear values in the new row
    newRow.querySelector(`#stainSize-${spotRowIndex}`).value = '';
    newRow.querySelector(`#spotQuantity-${spotRowIndex}`).value = '';
    newRow.querySelector(`#spotCost-${spotRowIndex}`).textContent = 'Cost will be calculated';

    // Add "Calculate" and "Remove" buttons for the new row
    const actionCell = newRow.querySelector('td:last-child');
    actionCell.innerHTML = `<button class="btn btn-primary" onclick="calculateSpotCleaningCost(${spotRowIndex})">Calculate</button>
                           <button class="btn btn-danger" onclick="removeSpotCleaningRow(${spotRowIndex})">Remove</button>`;

    // Recalculate total cost whenever a new row is added
    calculateTotalSpotCleaningCost();
}

function removeSpotCleaningRow(index) {
    const table = document.getElementById('spotCleaningTable').getElementsByTagName('tbody')[0];
    const rowToRemove = document.getElementById(`spotRow-${index}`);
    const costToRemove = parseFloat(document.getElementById(`spotCost-${index}`).textContent.replace('$', '')) || 0;

    // Remove the selected row
    table.removeChild(rowToRemove);

    // Update total cost by subtracting the cost of the removed row
    let totalCost = parseFloat(document.getElementById('totalSpotCleaningCost').textContent.replace('$', '')) || 0;
    totalCost -= costToRemove;

    document.getElementById('totalSpotCleaningCost').textContent = `$${totalCost.toFixed(2)}`;
    calculateTotalSpotCleaningCost();
}
function submitSpotCleaning() {
    const spotCleaningData = []; // Array to store spot cleaning data
    let currentRowIndex = 0;

    // Iterate through each row of the spot cleaning table
    while (true) {
        const material = document.getElementById(`spotMaterial-${currentRowIndex}`);
        if (!material) break;

        // Extract data from the current row
        const stainType = document.getElementById(`stainType-${currentRowIndex}`).value;
        const stainSize = document.getElementById(`stainSize-${currentRowIndex}`).value;
        const quantity = document.getElementById(`spotQuantity-${currentRowIndex}`).value;
        const cost = document.getElementById(`spotCost-${currentRowIndex}`).textContent.replace('$', '');

        // Construct an object representing the current row of data
        const rowData = {
            material: material.value,
            stainType: stainType,
            stainSize: stainSize,
            quantity: quantity,
            cost: cost,
            clientid: <?php echo $clientid; ?> 
        };

        // Add the row data object to the array
        spotCleaningData.push(rowData);

        // Move to the next row
        currentRowIndex++;
    }

    // Send the data to the backend using AJAX
    fetch('submit_spot_cleaning.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ spotCleaningData: spotCleaningData }),
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response from the server
        console.log(data);
        if (data.success) {
            alert('Spot cleaning dattta submitted successfully!');
        } else {
            alert('Error submitting spot cleaning data. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting spot cleaning data. Please try again.');
    });
}


    </script>


</body>
</html>
