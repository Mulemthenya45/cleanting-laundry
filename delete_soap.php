<?php
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['soap'])) {
        $soap = $_POST['soap'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "laundry";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Delete associated rows from any table where soap is referenced
        // Example: $sql_delete_laundrydata = "DELETE FROM laundrydata WHERE soap = ?";
        // $stmt = $conn->prepare($sql_delete_laundrydata);
        // $stmt->bind_param("s", $soap);
        // $stmt->execute();
        // $stmt->close();

        // Delete soap from soaps table
        $sql_delete_soap = "DELETE FROM soaps WHERE soap_type_name = ?";
        $stmt = $conn->prepare($sql_delete_soap);
        $stmt->bind_param("s", $soap);
        $stmt->execute();
        $stmt->close();

        $conn->close();
        header("Location: adjustprices.php"); // Redirect back to the edit rates page
        exit();
    }
}
?>
