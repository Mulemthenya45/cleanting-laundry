<?php
session_start();
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
            flex-grow: 1; /* Let the message take the available space */
        }

        ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin-right: 0; /* Remove the default margin */
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
                <li><a href=''>Admission</a></li>
                <li><a href='login.html' class='btn btn-success'>Sign out</a></li>
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
                            <option value="cotton" data-rate="2.5">Cotton</option>
                            <option value="wool" data-rate="3.0">Wool</option>
                            <option value="polyester" data-rate="2.0">Polyester</option>
                            <!-- Add more options as needed -->
                        </select>
                    </td>
                    <td><input type="number" class="form-control" placeholder="Enter weight" id="weight-0"></td>
                    <td>
                        <select class="form-select" id="soap-0">
                            <option value="regular">Regular Soap</option>
                            <option value="gentle">Gentle Soap</option>
                            <option value="scented">Scented Soap</option>
                            <!-- Add more options as needed -->
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
        
            <!-- ... (existing table structure) ... -->
        </table>
        
        <!-- Add the following button at the end of the table -->
        <button class="btn btn-primary" onclick="submitLaundry()">Submit Laundry</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        let rowIndex = 0;
        function calculateCost(index) {
        const rateSelect = document.getElementById(`material-${index}`);
        const rate = parseFloat(rateSelect.options[rateSelect.selectedIndex].getAttribute('data-rate')) || 0;
        const weight = parseFloat(document.getElementById(`weight-${index}`).value) || 0;
        const quantity = parseFloat(document.getElementById(`quantity-${index}`).value) || 0;
        const cost = weight * quantity * rate;
        document.getElementById(`cost-${index}`).textContent = `$${cost.toFixed(2)}`;

        // Calculate total cost whenever a cost is updated
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

        table.deleteRow(rowToRemove.rowIndex);

        // Update total cost by subtracting the cost of the removed row
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
        // ... (existing JavaScript code) ...

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
    </script>
</body>
</html>
