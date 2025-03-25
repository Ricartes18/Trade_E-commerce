<?php
include 'admin/connection.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardName = $_POST['Trade_Name'];
    $description = $_POST['Trade_Description'];
    $username = $_SESSION['username']; 

    // Image Upload
    $imageName = $_FILES['Trade_Offer']['name'];
    $imageTmpName = $_FILES['Trade_Offer']['tmp_name'];
    $imageFolder = "trades/" . $imageName;

    if (move_uploaded_file($imageTmpName, $imageFolder)) {
        // Insert Trade Offer into Database
        $stmt = $conp->prepare("INSERT INTO trade (Trade_Name, Trade_Description, Trade_Offer, username, Trade_Status) 
                                VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssss", $cardName, $description, $imageName, $username);

        if ($stmt->execute()) {
            // Retrieve the last inserted product ID
            $tradeId = $stmt->insert_id;

            echo "<script>alert('Trade submitted successfully! Product ID: $tradeId'); window.location='trade_upload.php';</script>";
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
    <link rel="stylesheet" href="css\navbar.css">
    <link rel="stylesheet" href="css\footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">Tracker</a></li>
                <li><a href="#">Trades</a></li>
            </ul>
            <div class="logo">
                <img src="images\PoCaSwap Logo.png" alt="Logo">
            </div>
            <div class="profile">
                <a href="#"><img src="images\shopping_bag.png" alt="shopping bag"> @username</a>
            </div>
        </nav>

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

                    <button type="submit" class="trade-btn">Trade!</button>
                </form>
            </div>

            <!-- Requested Trade Section (Right) -->
            <div class="trade-request">
                <h2>Product You Want</h2>
                <div class="requested-product">
                    <img src="images/sample-product.jpg" alt="Requested Product">
                    <p>Example Product Name</p>
                    <p>Example product description goes here.</p>
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
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Shop</a></li>
                        <li><a href="#">Tracker</a></li>
                        <li><a href="#">Trades</a></li>
                        <li><a href="#">Shopping Bag</a></li>
                    </ul>
                </div>

                <div class="footer-right">
                    <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>
</html>

