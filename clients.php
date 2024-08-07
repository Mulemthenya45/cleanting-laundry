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

// Check if delete button is clicked
if(isset($_POST['delete'])){
    // Get the clientid of the client to be deleted
    $clientid = $_POST['clientid'];

    // Prepare a DELETE statement
    $sql = "DELETE FROM clientinfo WHERE clientid = '$clientid'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Client deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting client: " . $conn->error . "');</script>";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f8ff; /* Aurora Blue background */
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(to right, lightblue, blue);
            border-radius: 0 0 10px 10px;
            position: relative;
            padding: 10px;
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

        .content {
            margin-top: 20px;
            padding: 20px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th, .user-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-table th {
            background-color: #4e7aad;
            color: white;
        }

        .user-table tr:hover {
            background-color: #f2f2f2;
        }

        .btn-update, .btn-delete {
            padding: 6px 10px;
            margin-right: 5px;
        }

        .btn-update {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <span class="logo">CleanTing</span>
    </nav>

    <div class="content">
        <h2>Users</h2>
        <table class="user-table">
            <thead>
                <tr>
                <th>Clientid</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
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

                // Fetch user data from the database
                $sql = "SELECT clientid, name, email, contactno, password FROM clientinfo";
                $result = $conn->query($sql);

                // Check if there are any users
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<td>" . $row["clientid"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["contactno"] . "</td>";
                        echo "<td>" . $row["password"] . "</td>";
                        echo "<td>
                                <a href='adminupdate_client.php?clientid=" . $row["clientid"] . "' class='btn btn-update'>Update</a>
                                <form method='post'>
                                    <input type='hidden' name='clientid' value='" . $row["clientid"] . "'>
                                    <button type='submit' name='delete' class='btn btn-delete'>Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                        
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                ?>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>