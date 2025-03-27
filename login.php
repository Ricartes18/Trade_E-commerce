<?php 
    include 'admin/connection.php';
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Login</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
                <p>LOGIN</p>
            </div>
        </nav>
    </header>

    <section class="content">
        <div class="container">

            <div class="branding">
                <img src="images/PoCaSwap Logo.png" alt="PoCaSwap Cards" class="cards">
                <h1 class="logo-text">PoCaSwap</h1>
                <p class="tagline">Shop. Swap. Collect</p>
            </div>

            <div class="login-box">
                <div class="login-circle"></div> 
                <h2 class="login-title">LOGIN</h2>
                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                    <label class="login-text" for="username">Username:</label>
                    <input type="text" name="username" placeholder="Username"><br>

                    <label class="login-text" for="password">Password:</label>
                    <input type="password" name="password" placeholder="Password">

                    <button type="submit" name="login" class="login-btn">LOGIN</button>
                </form>
                <p>Don't have an account? <a href="sign_up.php">Sign up</a></p>
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
                        $stmt = $con->prepare("SELECT user_id, password, role FROM info WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $stmt->store_result();
                        
                        // checks if password is correct
                        if ($stmt->num_rows > 0) {
                            $stmt->bind_result( $user_id,$hashed_password, $role);
                            $stmt->fetch();
                        
                            if (password_verify($password, $hashed_password)) {
                                $_SESSION['user_id'] = $user_id;
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
            </div>
        </div>
    </section>
</body>
</html>
