<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/sign_up.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Sign Up</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
                <p>SIGNUP</p>
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
                <h2 class="login-title">SIGNUP</h2>
                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                    <label class="login-text" for="username">Username:</label>
                    <input type="text" name="username" placeholder="Username"  id="username" required><br>

                    <label class="login-text" for="firstname">First Name:</label>
                    <input type="text" name="Firstname" placeholder="Juan" id="" required><br>

                    <label class="login-text" for="lastname">Last Name:</label>
                    <input type="text" name="Lastname" placeholder="Dela Cruz" id="" required><br>

                    <label class="login-text" for="phone_number">Phone Number:</label>
                    <input type="tel" name="phone" placeholder="09XXXXXXXXXX" id="" required maxlength="11"><br>

                    <label class="login-text" for="password">Password:</label>
                    <input type="password" name="password" placeholder="Password" required>

                    <button class="signup-btn" type="submit" name="sign_up" >Sign Up</button>
                </form>
                <p>Already have an account? <a href="login.php">Login</a></p>
                <?php 
                    include 'admin/connection.php';

                    if($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = trim($_POST['username']);
                        $firstname = trim($_POST['Firstname']);
                        $lastname = trim($_POST['Lastname']);
                        $phone_num = trim($_POST['phone']);
                        $password = trim($_POST['password']);
                    }
                    
                    // check if the form has unanswered inputs
                    if (empty($username) || empty($firstname) || empty($lastname) || empty($phone_num) || empty($password)) {
                        die("All fields are required.");
                    }

                    // SQL Query
                    $stmt = $con->prepare("SELECT username FROM info WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    // If username already exist, this code will run
                    if ($stmt->num_rows > 0) {
                        die("Username already taken. Choose another one.");
                    }
                    $stmt->close();

                    // to encrypt password when it is stored in a database
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // INSERT DATA to db
                    $stmt = $con->prepare("INSERT INTO info (username, firstname, lastname, phonenumber, password) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $username, $firstname, $lastname, $phone_num, $hashed_password);

                    
                    if ($stmt->execute()) {
                        echo "Sign-up successful! Redirecting...";
                        header("Refresh:2; url=login.php");
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                    $con->close();
                ?>
            </div>
        </div>
    </section>
</body>
</html>

