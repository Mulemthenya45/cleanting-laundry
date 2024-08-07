<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laundry Cost Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<nav> 
   <label class="logo">Cleanting</label> 
   <ul>
    
    <li><a href="contact.php">Contact</a></li>
    <li><a href="login.html"class="btn btn-success">Login</a></li>
   </ul>
</nav>

<div class="section1">
    <label class="img_text">We clean best!</label>
    <img class="main_img" src="laundryguy.png" alt="">
</div>

<div class="container mt-5">
    <h2 class="mb-4">Sign In</h2>
    <form action="signinverify.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
    <label for="contactno" class="form-label">Contact Number:</label>
    <input type="text" class="form-control" id="contactno" name="contactno" pattern="07\d{8}" title="Please enter a valid contact number starting with 07 and followed by 8 digits" required>
</div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="confirmpassword" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
        </div>

        

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
