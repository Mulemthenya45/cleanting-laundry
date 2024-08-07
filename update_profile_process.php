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
        exit();
    }

    // Validate password
    if (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[a-zA-Z]/', $password)) {
        echo "<script>alert ('Password must be at least 8 characters long and contain both numerical and alphabetical characters.')</script>";
        exit();
    }

    // Basic password validation
    if ($password !== $confirmpassword) {
        echo "<script>alert('Password and confirm password do not match. Please try again.')</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validate contact number format (starts with "07" followed by eight digits)
    if (!preg_match('/^07\d{8}$/', $contactno)) {
        echo "<script>alert('Invalid contact number format. Please enter a valid contact number starting with '07' followed by eight digits.')</script>";
        exit();
    }

    // Update data in the clientinfo table with the hashed password
    $clientid = $_SESSION['clientid'];
    $sql = "UPDATE clientinfo SET name = '$name', email = '$email', contactno = '$contactno', password = '$hashedPassword' WHERE clientid = '$clientid'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Profile updated successfully.');</script>";
        
        // Redirect to userhome page
        echo "<script>window.location.href='userhome.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>
