<?php
// Replace these with your actual database credentials
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

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $contactno = mysqli_real_escape_string($conn, $_POST["contactno"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirmpassword = mysqli_real_escape_string($conn, $_POST["confirmpassword"]);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please enter a valid email address.');</script>";
        echo '<script>window.setTimeout(function() { window.location.href = "signin.php"; }, 2000);</script>';
        exit();
    }
    if (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[a-zA-Z]/', $password)) {
        echo "<script>alert ('Password must be at least 8 characters long and contain both numerical and alphabetical characters.')</script>";
        echo '<script>window.setTimeout(function() { window.location.href = "signin.php"; }, 2000);</script>';
        exit();
    }
    // Basic password validation
    if ($password !== $confirmpassword) {
        echo "<script>alert('Password and confirm password do not match. Please try again.')</script>";
        echo '<script>window.setTimeout(function() { window.location.href = "signin.php"; }, 2000);</script>';
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);




// Validate contact number format (starts with "07" followed by eight digits)
 if (!preg_match('/^07\d{8}$/', $contactno)) {
    echo "<script>alert('Invalid contact number format. Please enter a valid contact number starting with '07' followed by eight digits.')</script>";
    exit();
}

// Check if the user already exists in the system
$sqlCheckUser = "SELECT * FROM clientinfo WHERE email = '$email'";
$resultCheckUser = $conn->query($sqlCheckUser);

if ($resultCheckUser->num_rows > 0) {
    echo "<script>alert('User with this email already exists. Please sign in with your existing account.')</script>";
    echo '<script>window.setTimeout(function() { window.location.href = "signin.php"; }, 2000);</script>';
    exit();
}

// Get the current number of clients
$sqlCount = "SELECT COUNT(*) AS totalClients FROM clientinfo";
$resultCount = $conn->query($sqlCount);

if ($resultCount->num_rows > 0) {
    $row = $resultCount->fetch_assoc();
    $currentClients = $row['totalClients'];
} else {
    $currentClients = 0;
}

// Generate the new client ID
$newClientID = $currentClients + 1;

// Insert data into the clientinfo table with the hashed password
$sql = "INSERT INTO clientinfo (name, email, contactno, password, clientid) VALUES ('$name', '$email', '$contactno', '$hashedPassword', '$newClientID')";

if ($conn->query($sql) === TRUE) {
    echo "Sign-in successful. Welcome, $name!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
session_start();

// Store user information in session variables
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['contactno'] = $contactno;
$_SESSION['clientid'] = $newClientID;

// Redirect to the user dashboard
header("Location: userhome.php");
exit();
}
?>
