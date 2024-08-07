<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f8ff; /* Aurora Blue background */
            margin: 0;
            padding: 0;
        }
        .image-container {
            width: 100%;
            height: 33vh; /* Cover one-third of the screen */
            overflow: hidden;
        }
        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .navbar {
            background: linear-gradient(to right, lightblue, blue); /* Gradient from blue to aurora blue */
            border-radius: 0 0 10px 10px; /* Rounded corners at the bottom */
            position: relative;
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
            font-family: 'Forte', cursive;
            font-size: 1.5rem;
            padding: 10px;
            background-color: #4e7aad; /* Blue background for logo */
            border-radius: 50%; /* Oval shape */
            margin-right: 15px;
        }

        .we-clean-best {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem; /* Slightly bigger font than the logo */
            color: lightblue; /* Palace Script MT theme color */
            font-family: 'Algerian', cursive;
        }

        .login-button {
            margin-left: auto; /* Push the button to the right */
            background-color: #4e7aad;
        }
        .centered-login {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem; /* Bigger font size */
        }

        .system-info {
            background-color: #ffffff; /* Set the background color you desire */
            padding: 20px;
            border-radius: 10px; /* Add rounded corners for a nicer look */
            margin-top: 20px; /* Adjust the margin as needed */
        }

        .info-image {
            width: 100%; /* Make the image take the full width of its container */
            height: auto; /* Maintain the aspect ratio */
            margin-top: 20px; /* Adjust the margin as needed */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CleanTing</a>
            <div class="we-clean-best">WE CLEAN BEST!</div>
            <a href="adminlogin.php" class="btn btn-primary login-button">Login</a>
            <!-- Add your navigation links here -->
        </div>
    </nav>
    <div class="image-container">
        <img src="adminpic1.png" alt="Admin Background" class="background-image">
    </div>
    <!-- Add the rest of your admin homepage content here -->
    <a href="adminlogin.php" class="btn btn-primary centered-login"> Login!</a><br><br>
    <div class="system-info mt-4">
        <br><br><h3>Welcome to CleanTing Admin Portal</h3>
        <p>
            CleanTing is a powerful laundry cost calculator system designed to streamline your workflow.
            Stay updated with the latest features, manage user accounts, and ensure smooth system operation.
        </p>

        <h4>Upcoming Features:</h4>
        <ul>
            <li>AI analytics: [PowerBI integration]</li>
            <li>Self Price update: [Laundry rates]</li>
        </ul>

        <h4>Reminders:</h4>
        <ul>
            <li>Update status of client laundry</li>
            <li>Check system health regularly</li>
        </ul>

        <!-- Add the image here -->
        <img src="laundrylastimage.png" alt="Info Image" class="info-image">

        <p>
            Need assistance? Contact our support team at <a href="mailto:support@cleanting.com">support@cleanting.com</a>.
        </p>
    </div>

    <!-- ... (remaining HTML code) ... -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
