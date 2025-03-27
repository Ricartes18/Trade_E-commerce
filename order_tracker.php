<?php
require 'admin/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$ordersQuery = "SELECT od.orderdetails_id, od.order_Id, od.total_price, od.pickup_location, od.mode_of_payment, 
                       od.submitted_date, od.status, p.Product_Image 
                FROM order_details od 
                JOIN products p ON od.order_Id = p.Product_ID
                ORDER BY od.submitted_date DESC";
$ordersResult = mysqli_query($conp, $ordersQuery);

$tradesQuery = "SELECT t.trade_id, t.username, t.product_id, t.trade_name, t.trade_description, 
                       t.trade_offer, t.trade_status, p.Product_Image 
                FROM trade t 
                JOIN products p ON t.product_id = p.Product_ID
                ORDER BY t.trade_id DESC";
$tradesResult = mysqli_query($conp, $tradesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order & Trade Tracker</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <style>
        
    html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    background: linear-gradient(to bottom left, #E44093, #5A21A5);
    font-family: "Changa One";
    }

    .container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    h2 {
        color: #fff;
        font-weight: normal;
    }

    .toggle-btn {
        font-family: 'Dela Gothic One';
        background-color: #fff;
        color: black;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 20px;
        transition: transform 0.3s ease-out; 
    }

    .toggle-btn:hover {
        background-color: #000;
        color: #fff;
        transform: scale(1.1);
    }

    table {
        font-family: 'Dela Gothic One';
        font-weight: normal;
        color: #000;
        width: 90%;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0; 
        background: white;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden; 
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background: #fff;
        color: #000
    }

    tr:nth-child(even) {
        background: #f2f2f2;
    }

    tr:hover {
        background: #ddd;
    }

    td img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
    }

    td:first-child {
        text-align: left;
        padding-left: 10px;
    }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="order_tracker.php">Tracker</a></li>
            <li><a href="order_tracker.php">Trades</a></li>
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
                <td><?php echo htmlspecialchars($row['order_Id']); ?></td>
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
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($tradesResult)) : ?>
            <tr>
                <td><img src="uploads/<?php echo htmlspecialchars($row['Product_Image']); ?>" alt="Product Image" width="50"></td>
                <td><?php echo htmlspecialchars($row['trade_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['trade_name']); ?></td>
                <td><?php echo htmlspecialchars($row['trade_description']); ?></td>
                <td><?php echo htmlspecialchars($row['trade_offer']); ?></td>
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
                    <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>

    </footer>
</body>

<script>
function toggleTracker() {
    let ordersTable = document.getElementById("ordersTable");
    let tradesTable = document.getElementById("tradesTable");
    let button = document.querySelector("toggleButto");

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

