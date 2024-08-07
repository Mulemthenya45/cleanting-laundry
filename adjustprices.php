<?php
// Start the session


// Check if the user is logged in


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

// Fetch material prices
$materialPrices = array();
$sql_material = "SELECT * FROM laundryprice";
$result_material = $conn->query($sql_material);
if ($result_material->num_rows > 0) {
    while ($row = $result_material->fetch_assoc()) {
        $materialPrices[$row['material_name']] = $row['rate'];
    }
}

// Fetch soap rates
$soapRates = array();
$sql_soap = "SELECT * FROM soaps";
$result_soap = $conn->query($sql_soap);
if ($result_soap->num_rows > 0) {
    while ($row = $result_soap->fetch_assoc()) {
        $soapRates[$row['soap_type_name']] = $row['rate'];
    }
}

// Fetch special treatment rates
$specialTreatmentRates = array();
$sql_special_treatment = "SELECT * FROM special_treatment_rates";
$result_special_treatment = $conn->query($sql_special_treatment);
if ($result_special_treatment->num_rows > 0) {
    while ($row = $result_special_treatment->fetch_assoc()) {
        $specialTreatmentRates[$row['treatment_type']] = $row['rate'];
    }
}
$dryCleaningRates = array();
$sql_drycleaning = "SELECT * FROM drycleaning_rates";
$result_drycleaning = $conn->query($sql_drycleaning);
if ($result_drycleaning->num_rows > 0) {
    while ($row = $result_drycleaning->fetch_assoc()) {
        $dryCleaningRates[$row['material_type']] = $row['rate'];
    }
}
$stainRates = array();
$sql_stains = "SELECT * FROM stains";
$result_stains = $conn->query($sql_stains);
if ($result_stains->num_rows > 0) {
    while ($row = $result_stains->fetch_assoc()) {
        $stainRates[$row['StainType']] = $row['Rate'];
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
            background-color: #f0f8ff; /* Aurora Blue background */
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
        .welcome-message {
            color: white;
            font-size: 1.9rem;
            margin-top: 5px;
            font-weight: bold;
            font-family: 'Monotype Corsiva', cursive;
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
        .form-group {
            margin-bottom: 20px;
        }
        input[type="number"] {
            width: 150px;
        }
        input[type="submit"] {
            width: 150px;
        }
    </style>
</head>
<body>
<nav> 
        <label class="logo">CleanTing</label> 
        <span class="welcome-message">Welcome to CleanTing </span>
        <div class="action-buttons">
            <a href="admindash.php" class="btn btn-primary">Dashboard</a>
           
        </div>
    </nav>

    <div class="container">
        <h2>Edit Material Rates</h2>
        <form action="update_prices.php" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>Material Name</th>
                        <th>Rate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materialPrices as $material => $rate) : ?>
                        <tr>
                            <td><?php echo $material; ?></td>
                            <td><input type="number" name="materials[<?php echo $material; ?>]" value="<?php echo $rate; ?>" step="0.01" class="form-control"></td>
                            <td>
                                <form action="delete_material.php" method="post" onsubmit="return confirm('Are you sure you want to delete this material?');">
                                    <input type="hidden" name="material" value="<?php echo $material; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Container for adding a new material -->
            <div id="newMaterialContainer" class="form-group"></div>

            <!-- Button for adding a new material -->
            <button type="button" class="btn btn-success add-material">Add New Material</button>
            <input type="submit" value="Update Material Rates" class="btn btn-primary">
        </form>

        <h2>Edit Soap Rates</h2>
        <form action="update_soap_rates.php" method="post">
    <table class="table">
        <thead>
            <tr>
                <th>Soap Type</th>
                <th>Rate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($soapRates as $soap => $rate) : ?>
                <tr>
                    <td><?php echo $soap; ?></td>
                    <td><input type="number" name="soaps[<?php echo $soap; ?>]" value="<?php echo $rate; ?>" step="0.01" class="form-control"></td>
                    <td>
                        <form action="delete_soap.php" method="post" onsubmit="return confirm('Are you sure you want to delete this soap type?');">
                            <input type="hidden" name="soap" value="<?php echo $soap; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Container for adding a new soap -->
    <div id="newSoapContainer" class="form-group"></div>

    <!-- Button for adding a new soap -->
    <button type="button" class="btn btn-success add-soap">Add New Soap Type</button>
    <input type="submit" value="Update Soap Rates" class="btn btn-primary">
</form>

<h2>Edit Special Treatment Rates</h2>
<form action="update_special_treatment_rates.php" method="post">
    <table class="table">
        <thead>
            <tr>
                <th>Treatment Type</th>
                <th>Rate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($specialTreatmentRates as $treatment => $rate) : ?>
                <tr>
                    <td><?php echo $treatment; ?></td>
                    <td><input type="number" name="special_treatments[<?php echo $treatment; ?>]" value="<?php echo $rate; ?>" step="0.01" class="form-control"></td>
                    <td>
                        <form action="delete_special_treatment.php" method="post" onsubmit="return confirm('Are you sure you want to delete this special treatment?');">
                            <input type="hidden" name="treatment" value="<?php echo $treatment; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Container for adding a new special treatment -->
    <div id="newSpecialTreatmentContainer" class="form-group"></div>

    <!-- Button for adding a new special treatment -->
    <button type="button" class="btn btn-success add-special-treatment">Add New Special Treatment</button>
    <input type="submit" value="Update Special Treatment Rates" class="btn btn-primary">
</form>
<h2>Edit Dry Cleaning Rates</h2>
<form action="update_drycleaning_rates.php" method="post">
    <table class="table">
        <thead>
            <tr>
                <th>Material Type</th>
                <th>Rate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through dry cleaning rates -->
            <?php foreach ($dryCleaningRates as $material => $rate) : ?>
                <tr>
                    <td><?php echo $material; ?></td>
                    <td><input type="number" name="drycleaning_rates[<?php echo $material; ?>]" value="<?php echo $rate; ?>" step="0.01" class="form-control"></td>
                    <td>
                        <form action="delete_drycleaning_material.php" method="post" onsubmit="return confirm('Are you sure you want to delete this dry cleaning material?');">
                            <input type="hidden" name="material" value="<?php echo $material; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Container for adding a new dry cleaning material -->
    <div id="newDryCleaningMaterialContainer" class="form-group"></div>

    <!-- Button for adding a new dry cleaning material -->
    <button type="button" class="btn btn-success add-drycleaning-material">Add New Dry Cleaning Material</button>
    <input type="submit" value="Update Dry Cleaning Rates" class="btn btn-primary">
</form>

<h2>Edit Stain Rates</h2>
<form action="update_stain_rates.php" method="post">
    <table class="table">
        <thead>
            <tr>
                <th>Stain Type</th>
                <th>Rate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through stain rates -->
            <?php foreach ($stainRates as $stain => $rate) : ?>
                <tr>
                    <td><?php echo $stain; ?></td>
                    <td><input type="number" name="stains[<?php echo $stain; ?>]" value="<?php echo $rate; ?>" step="0.01" class="form-control"></td>
                    <td>
                        <form action="delete_stain.php" method="post" onsubmit="return confirm('Are you sure you want to delete this stain type?');">
                            <input type="hidden" name="stain" value="<?php echo $stain; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Container for adding a new stain type -->
    <div id="newStainContainer" class="form-group"></div>

    <!-- Button for adding a new stain type -->
    <button type="button" class="btn btn-success add-stain">Add New Stain Type</button>
    <input type="submit" value="Update Stain Rates" class="btn btn-primary">
</form>

    </div>


<script>
    // Function to handle deletion of a material
    function deleteMaterial(material) {
        if (confirm("Are you sure you want to delete this material?")) {
            // Remove the corresponding input field
            document.querySelector(`input[name="materials[${material}]"]`).parentNode.remove();

            // Send AJAX request to delete the material
            // (AJAX code not shown here)
        }
    }

    // Event listener for delete material buttons
    const deleteMaterialButtons = document.querySelectorAll('.delete-material');
    deleteMaterialButtons.forEach(button => {
        button.addEventListener('click', function() {
            const material = this.getAttribute('data-material');
            deleteMaterial(material);
        });
    });

    // Function to handle addition of a new material
    function addMaterial() {
        const materialName = prompt("Enter the name of the new material:");
        if (materialName) {
            // Create a new input field for the material
            const newMaterialInput = `
                <div class="form-group">
                    <label for="${materialName}" class="form-label">${materialName} Rate:</label>
                    <input type="number" id="${materialName}" name="materials[${materialName}]" value="" step="0.01" class="form-control">
                    <button type="button" class="btn btn-danger delete-material" data-material="${materialName}">Delete</button>
                </div>`;
            // Append the new input field to the container
            document.getElementById('newMaterialContainer').insertAdjacentHTML('beforebegin', newMaterialInput);
        }
    }

    // Event listener for add material button
    document.querySelector('.add-material').addEventListener('click', addMaterial);

    // Function to handle deletion of a soap type
    function deleteSoap(soap) {
        if (confirm("Are you sure you want to delete this soap type?")) {
            // Remove the corresponding input field
            document.querySelector(`input[name="soaps[${soap}]"]`).parentNode.remove();

            // Send AJAX request to delete the soap type
            // (AJAX code not shown here)
        }
    }

    // Event listener for delete soap buttons
    const deleteSoapButtons = document.querySelectorAll('.delete-soap');
    deleteSoapButtons.forEach(button => {
        button.addEventListener('click', function() {
            const soap = this.getAttribute('data-soap');
            deleteSoap(soap);
        });
    });

    // Function to handle addition of a new soap type
    function addSoap() {
        const soapName = prompt("Enter the name of the new soap type:");
        if (soapName) {
            // Create a new input field for the soap type
            const newSoapInput = `
                <div class="form-group">
                    <label for="${soapName}" class="form-label">${soapName} Rate:</label>
                    <input type="number" id="${soapName}" name="soaps[${soapName}]" value="" step="0.01" class="form-control">
                    <button type="button" class="btn btn-danger delete-soap" data-soap="${soapName}">Delete</button>
                </div>`;
            // Append the new input field to the container
            document.getElementById('newSoapContainer').insertAdjacentHTML('beforebegin', newSoapInput);
        }
    }

    // Event listener for add soap button
    document.querySelector('.add-soap').addEventListener('click', addSoap);

    // Function to handle deletion of a special treatment
    function deleteSpecialTreatment(treatment) {
        if (confirm("Are you sure you want to delete this special treatment?")) {
            // Remove the corresponding input field
            document.querySelector(`input[name="special_treatments[${treatment}]"]`).parentNode.remove();

            // Send AJAX request to delete the special treatment
            // (AJAX code not shown here)
        }
    }

    // Event listener for delete special treatment buttons
    const deleteSpecialTreatmentButtons = document.querySelectorAll('.delete-special-treatment');
    deleteSpecialTreatmentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const treatment = this.getAttribute('data-treatment');
            deleteSpecialTreatment(treatment);
        });
    });

    // Function to handle addition of a new special treatment
    function addSpecialTreatment() {
        const treatmentName = prompt("Enter the name of the new special treatment:");
        if (treatmentName) {
            // Create a new input field for the special treatment
            const newTreatmentInput = `
                <div class="form-group">
                    <label for="${treatmentName}" class="form-label">${treatmentName} Rate:</label>
                    <input type="number" id="${treatmentName}" name="special_treatments[${treatmentName}]" value="" step="0.01" class="form-control">
                    <button type="button" class="btn btn-danger delete-special-treatment" data-treatment="${treatmentName}">Delete</button>
                </div>`;
            // Append the new input field to the container
            document.getElementById('newSpecialTreatmentContainer').insertAdjacentHTML('beforebegin', newTreatmentInput);
        }
    }

    // Event listener for add special treatment button
    document.querySelector('.add-special-treatment').addEventListener('click', addSpecialTreatment);

    function deleteDryCleaningMaterial(material) {
        if (confirm("Are you sure you want to delete this dry cleaning material?")) {
            // Remove the corresponding input field
            document.querySelector(`input[name="drycleaning_materials[${material}]"]`).parentNode.remove();

            // Send AJAX request to delete the material
            // (AJAX code not shown here)
        }
    }

    // Event listener for delete dry cleaning material buttons
    const deleteDryCleaningMaterialButtons = document.querySelectorAll('.delete-drycleaning-material');
    deleteDryCleaningMaterialButtons.forEach(button => {
        button.addEventListener('click', function() {
            const material = this.getAttribute('data-material');
            deleteDryCleaningMaterial(material);
        });
    });

    // Function to handle addition of a new dry cleaning material
  // Function to handle addition of a new dry cleaning material
    function addDryCleaningMaterial() {
        const materialName = prompt("Enter the name of the new dry cleaning material:");
        if (materialName) {
            // Create a new input field for the material
            const newMaterialInput = `
                <div class="form-group">
                    <label for="${materialName}" class="form-label">${materialName} Rate:</label>
                    <input type="number" id="${materialName}" name="drycleaning_rates[${materialName}]" value="" step="0.01" class="form-control">
                    <button type="button" class="btn btn-danger delete-drycleaning-material" data-material="${materialName}">Delete</button>
                </div>`;
            // Append the new input field to the container
            document.getElementById('newDryCleaningMaterialContainer').insertAdjacentHTML('beforebegin', newMaterialInput);
        }
    }

    // Event listener for add dry cleaning material button
    document.querySelector('.add-drycleaning-material').addEventListener('click', addDryCleaningMaterial);
    function deleteStain(stain) {
        if (confirm("Are you sure you want to delete this stain type?")) {
            // Remove the corresponding input field
            document.querySelector(`input[name="stains[${stain}]"]`).parentNode.remove();

            // Send AJAX request to delete the stain type
            // (AJAX code not shown here)
        }
    }

    // Event listener for delete stain buttons
    const deleteStainButtons = document.querySelectorAll('.delete-stain');
    deleteStainButtons.forEach(button => {
        button.addEventListener('click', function() {
            const stain = this.getAttribute('data-stain');
            deleteStain(stain);
        });
    });

    // Function to handle addition of a new stain type
    function addStain() {
        const stainName = prompt("Enter the name of the new stain type:");
        if (stainName) {
            // Create a new input field for the stain type
            const newStainInput = `
                <div class="form-group">
                    <label for="${stainName}" class="form-label">${stainName} Rate:</label>
                    <input type="number" id="${stainName}" name="stains[${stainName}]" value="" step="0.01" class="form-control">
                    <button type="button" class="btn btn-danger delete-stain" data-stain="${stainName}">Delete</button>
                </div>`;
            // Append the new input field to the container
            document.getElementById('newStainContainer').insertAdjacentHTML('beforebegin', newStainInput);
        }
    }

    // Event listener for add stain button
    document.querySelector('.add-stain').addEventListener('click', addStain);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
