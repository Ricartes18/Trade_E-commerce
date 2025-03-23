<?php 
    include 'admin/connection.php';
    session_start();
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
        <h1>LOG IN</h1>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="XxBoszxX"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password">
            <button type="submit" name="login">Log in</button>
        </form>
        <p>Don't have an account? <a href="sign_up.php">Sign up</a></p>
    </div>

</body>
</html>
<?php
    if(isset($_POST['login'])){
        include 'admin/connection.php';
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        //check inputs if they are filled up
        if(empty($username)){
                    echo "Please enter your username";
                } elseif (empty($password)){
                    echo "Please enter your password";
                }

        // SQL Query
        $stmt = $con->prepare("SELECT password, role FROM info WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // checks if password is correct
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['role'] = $role;
                
                if ($role === 'admin'){
                    header('Location: admin/dashboard.php');
                } else {
                    header("Location: index.php");
                }
                
            } else {
                echo "Invalid password.";
            }

            $stmt->close();
            $con->close();

        }
    }
?>