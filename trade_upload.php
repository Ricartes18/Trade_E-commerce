<?php
include 'admin/connection.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET" && empty($_GET)) {
    header("Location: index.php");
    exit();
} 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} 

// Retrieve Product_ID 
$Product_ID = isset($_GET['Product_ID']) ? $_GET['Product_ID'] : null;
$Product_Name = isset($_GET['Product_Name']) ? $_GET['Product_Name'] : 'Unknown Product';
$Product_Description = isset($_GET['Product_Description']) ? $_GET['Product_Description'] : 'No description available';
$Product_Image = isset($_GET['Product_Image']) ? $_GET['Product_Image'] : 'default.jpg';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardName = $_POST['Trade_Name'];
    $description = $_POST['Trade_Description'];
    $username = $_SESSION['username']; 
    $Product_ID = $_POST['Product_ID']; // Include product ID from GET request

    // Image Upload
    $imageName = $_FILES['Trade_Offer']['name'];
    $imageTmpName = $_FILES['Trade_Offer']['tmp_name'];
    $imageFolder = "trades/" . $imageName;

    if (move_uploaded_file($imageTmpName, $imageFolder)) {
        // Insert Trade Offer into Database with Product_ID
        $stmt = $conp->prepare("INSERT INTO trade (Product_ID, Trade_Name, Trade_Description, Trade_Offer, username, Trade_Status) 
                                VALUES (?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("issss", $Product_ID, $cardName, $description, $imageName, $username);

        if ($stmt->execute()) {
            echo "<script>alert('Trade submitted successfully!'); window.location='trade_upload.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload image.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Upload</title>
    <link rel="stylesheet" href="css\trade_upload.css">
    <link rel="stylesheet" href="css\header.css">
    <link rel="stylesheet" href="css\footer.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
        <header>
            <nav class="navbar">
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="order_tracker.php">Tracker</a></li>
                    <li><a href="redirection.php">Trades</a></li>
                </ul>
                <div class="logo">
                    <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
                </div>
                <div class="profile">
                    <a href="cart.php"><img src="images/shopping_bag.png" alt="shopping bag"> 
                        <?php 
                            if(isset($_SESSION['role'])){
                                if($_SESSION['role'] === "admin" ){
                                    echo "<a href='admin/dashboard.php'>Dashboard</a>";
                                } if(($_SESSION['role']) === "user"){
                                echo "<p>".$_SESSION['username']."</p>";
                                }
                                echo "<div class='logout'>".
                                    "<form action='logout.php' method='post'>". 
                                        "<button class='logout-btn' type='submit' name='logout'>".
                                            "<img src='images/logout_button.png' alt='Log out' class='logout-img'>".
                                        "</button>
                                    </form>
                                </div>";
                            } else {
                                echo "<a href='login.php'>Login</a> <a>|</a> <a href='sign_up.php'>Sign Up</a>";
                            }
                        ?>
                    </a>
                </div>
            </nav>
        </header>

        <div class="trade-container">
            <!-- Trade Offer Section (Left) -->
            <div class="trade-offer">
                <h2>Upload Trade Offer</h2>
                <form action="trade_upload.php" method="post" enctype="multipart/form-data">
                    <label>Trade Name:</label>
                    <input type="text" name="Trade_Name" required><br>

                    <label>Trade Description:</label>
                    <textarea name="Trade_Description" required></textarea><br>

                    <label>Upload Image:</label>
                    <input type="file" name="Trade_Offer" accept="image/*" required><br>
                    <input type="hidden" name="Product_ID" value="<?= $Product_ID ?>">

                    <button type="submit" class="trade-btn">Trade!</button>
                </form>
            </div>

            <!-- Requested Trade Section (Right) -->
            <div class="trade-request">
                <h2>Product You Want</h2>
                <div class="requested-product">
                    <img src="products/<?= $Product_Image ?>" alt="<?=$Product_Name?>">
                    <p><?=$Product_Name?></p>
                    <p><?=$Product_Description?></p>
                </div>
            </div>
        </div>
</body>

<!-- Footer -->
<footer class="footer">
        <div class="footer-wrapper">
            <div class="footer-center">
                <h2>PoCaSwap</h2>
                <p>Shop. Swap. Collect</p>
            </div>

            <div class="footer-bottom">
                <div class="footer-left">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="order_tracker.php">Tracker</a></li>
                        <li><a href="redirection.php">Trades</a></li>
                        <li><a href="cart.php">Shopping Bag</a></li>
                    </ul>
                </div>

                <div class="footer-right">
                    <a href="https://instagram.com/pocaswap"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="https://www.facebook.com/pocaswap"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="https://x.com/ssmucart"><img src="images/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>
</html>

