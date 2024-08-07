<?php

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
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM clientinfo WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the hashed password
        $hashed_password= $row["password"];
        if (password_verify($password, $hashed_password )) {
            // Store user information in session variables
            $_SESSION['name'] = $row["name"];
            $_SESSION['email'] = $row["email"];
            $_SESSION['contactno'] = $row["contactno"];
            $_SESSION['clientid'] = $row["clientid"];

            // Redirect to the userhome.php page upon successful login
            header("Location: userhome.php");
            exit();
        } else {
            echo "Incorrect password. Provide the correct password.";
        }
    } else {
      echo "<script>alert (' User not found.');</script>";
      echo "<script>window.location.href='login.html';</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
