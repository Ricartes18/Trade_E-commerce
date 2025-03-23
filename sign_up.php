<?php 
    include 'admin/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>SIGN UP</h1>
        <form action="sign_up_process.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="XxBoszxX"  id="username" required><br>

            <label for="firstname">First Name:</label>
            <input type="text" name="Firstname" placeholder="Juan" id="" required><br>

            <label for="lastname">Last Name:</label>
            <input type="text" name="Lastname" placeholder="Dela Cruz" id="" required><br>

            <label for="phone_number">Phone Number:</label>
            <input type="tel" name="phone" placeholder="09XXXXXXXXXX" id="" required maxlength="11"><br>

            <label for="password">Password:</label>
            <input type="text" name="password" placeholder="Password" required>
            <button type="submit" name="sign_up">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>

