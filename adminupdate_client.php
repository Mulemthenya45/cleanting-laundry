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

// Fetch client information from the database using clientid from the URL
if(isset($_GET['clientid'])){
    $clientid = $_GET['clientid'];
    $sql = "SELECT * FROM clientinfo WHERE clientid = $clientid";
    $result = $conn->query($sql);

    // Check if there is a row in the result
    $clientInfo = array();
    if ($result->num_rows > 0) {
        $clientInfo = $result->fetch_assoc();
    }
} else {
    // Redirect to the appropriate page if clientid is not set
    header("Location: users.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Profile</title>
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

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <nav> 
        <label class="logo">CleanTing</label> 
        <span class="welcome-message">Welcome to CleanTing Admin Panel! </span>
        <div class="action-buttons">
            <a href="admindash.php" class="btn btn-primary">Dashboard</a>
            <a href="login.html" class="btn btn-danger logout-button">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3">Update Profile</h1>

        <form action="adminclientupdateverify.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($clientInfo['name']) ? $clientInfo['name'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($clientInfo['email']) ? $clientInfo['email'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="contactno" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contactno" name="contactno" value="<?php echo isset($clientInfo['contactno']) ? $clientInfo['contactno'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div> 
            <div class="mb-3">
                <label for="confirmpassword" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
