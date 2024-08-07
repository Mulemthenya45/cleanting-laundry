<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f8ff; /* Light Blue background */
            margin: 0;
            padding: 0;
            height: 100vh; /* 100% of the viewport height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: #add8e6; /* Light Blue background for the container */
            padding: 20px;
            border-radius: 10px; /* Add rounded corners for a nicer look */
            width: 33%; /* Cover one-third of the viewport width */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Login</h2>
        
        <?php
        // Add your PHP code here to display the login message if needed
        ?>

        <form action="adminverify.php" method="post">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="firstname" name="firstname" pattern="[A-Za-z]+" title="only aletters of the alphabet" required>
            </div>

            <div class="mb-3">
                <label for="secondname" class="form-label">Second Name:</label>
                <input type="text" class="form-control" id="secondname" name="secondname" pattern="[A-Za-z]+" title="only aletters of the alphabet" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
