<?php
require 'admin/connection.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 0;

$ordersQuery = "SELECT p.Product_Image, o.Order_ID, od.total_price, od.pickup_location, 
                        od.mode_of_payment, od.submitted_date, od.status
                FROM orders o 
                JOIN order_details od ON o.order_id = od.order_id
                JOIN products p ON o.product_ID = p.Product_ID
                WHERE o.user_id = ?  -- FILTER
                ORDER BY od.submitted_date DESC, o.order_id DESC"; 
$stmt = $conp->prepare($ordersQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$ordersResult = $stmt->get_result();


$tradesQuery = "SELECT t.trade_id, t.username, t.product_id, t.trade_name, 
                        t.trade_description, t.submitted_date, t.trade_offer, 
                        t.trade_status, p.Product_Image 
                FROM trade t 
                JOIN products p ON t.product_id = p.Product_ID
                WHERE t.user_id = ?  -- FILTER
                ORDER BY t.submitted_date DESC";
$stmt = $conp->prepare($tradesQuery);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$tradesResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order & Trade Tracker</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/order_tracker.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
</head>
<body>
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
<div class="container">
    <h2>Order & Trade Tracker</h2>
    <button id="toggleButton" class="toggle-btn" onclick="toggleTracker()">Show Trades</button>

    <!-- Orders Table -->
    <table id="ordersTable">
        <tr>
            <th>Product Image</th>
            <th>Order ID</th>
            <th>Total Price</th>
            <th>Pickup Location</th>
            <th>Mode of Payment</th>
            <th>Submitted Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($ordersResult)) : ?>
            <tr>
                <td><img src="products/<?php echo htmlspecialchars($row['Product_Image']); ?>" alt="Product Image" width="50"></td>
                <td><?php echo htmlspecialchars($row['Order_ID']); ?></td>
                <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['pickup_location']); ?></td>
                <td><?php echo htmlspecialchars($row['mode_of_payment']); ?></td>
                <td><?php echo htmlspecialchars($row['submitted_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Trades Table -->
    <table id="tradesTable" style="display: none;">
        <tr>
            <th>Product Image</th>
            <th>Trade ID</th>
            <th>Username</th>
            <th>Trade Name</th>
            <th>Description</th>
            <th>Trade Offer</th>
            <th>Submitted Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($tradesResult)) : ?>
            <tr>
                <td><img src="products/<?php echo htmlspecialchars($row['Product_Image']); ?>" alt="Product Image" width="50"></td>
                <td><?php echo htmlspecialchars($row['trade_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['trade_name']); ?></td>
                <td><?php echo htmlspecialchars($row['trade_description']); ?></td>
                <td><img src="trades/<?php echo htmlspecialchars($row['trade_offer']); ?>" alt="Trade Offer" width="50"></td>
                <td><?php echo htmlspecialchars($row['submitted_date']); ?></td>
                <td><?php echo htmlspecialchars($row['trade_status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
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
                        <li><a href="order_tracker.php">Trades</a></li>
                        <li><a href="cart.php">Shopping Bag</a></li>
                    </ul>
                </div>

                <div class="footer-right">
                    <a href="https://instagram.com/pocaswap" target="_blank"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="https://www.facebook.com/pocaswap" target="_blank"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="https://x.com/ssmucart" target="_blank"><img src="images/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>

    </footer>
</body>

<script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get("trade") === "true") {
            toggleTracker(); 
        }
    };
    
    function toggleTracker() {
        let ordersTable = document.getElementById("ordersTable");
        let tradesTable = document.getElementById("tradesTable");
        let button = document.querySelector("#toggleButton");
    
        if (ordersTable.style.display === "none") {
            ordersTable.style.display = "table";
            tradesTable.style.display = "none";
            button.innerText = "Show Trades";
        } else {
            ordersTable.style.display = "none";
            tradesTable.style.display = "table";
            button.innerText = "Show Orders";
        }
    }
</script>
</html>



