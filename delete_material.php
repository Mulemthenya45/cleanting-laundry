<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['material'])) {
        $material = $_POST['material'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "laundry";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Delete associated rows from laundrydata table
        $sql_delete_laundrydata = "DELETE FROM laundrydata WHERE material = ?";
        $stmt = $conn->prepare($sql_delete_laundrydata);
        $stmt->bind_param("s", $material);
        $stmt->execute();
        $stmt->close();

        // Delete material from laundryprice table
        $sql_delete_material = "DELETE FROM laundryprice WHERE material_name = ?";
        $stmt = $conn->prepare($sql_delete_material);
        $stmt->bind_param("s", $material);
        $stmt->execute();
        $stmt->close();

        $conn->close();
        header("Location: adjustprices.php"); // Redirect back to the edit rates page
        exit();
    }
}
?>
