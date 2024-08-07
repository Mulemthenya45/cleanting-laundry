<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laundry";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $secondname = $_POST["secondname"];
    $password = hashPassword($_POST["password"]);
    $email = $_POST["email"];

    // Insert data into admincredentials table
    $sql = "INSERT INTO admincredentials (firstname, secondname, password, email) VALUES ('$firstname', '$secondname', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" required><br>

        <label for="secondname">Second Name:</label>
        <input type="text" name="secondname" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
