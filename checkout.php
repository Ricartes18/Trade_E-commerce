<?php
session_start();
include 'admin/connection.php';

    if (!isset($_POST['checkout_items'])) {
        header("Location: index.php");
        exit();
    }

    $checkout_items = isset($_POST['checkout_items']) ? (array) $_POST['checkout_items'] : [];
    $num_ordered = isset($_POST['num_ordered']) ? (array) $_POST['num_ordered'] : [];
    $price = isset($_POST['price']) ? (array) $_POST['price'] : [];

?>

<!DOCTYPE html>
<html lang= "en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="css/checkout.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
        <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
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
                    <img src="images/PoCaSwap Logo.png" alt="Logo">
                </div>
                <div class="profile">
                    <a href="#"><img src="images/shopping_bag.png" alt="shopping bag"> 
                        <?php 
                            if(isset($_SESSION['role'])){
                                if($_SESSION['role'] === "admin" ){
                                    echo "<a href='admin/dashboard.php'>Dashboard</a>";
                                } if(($_SESSION['role']) === "user"){
                                echo "<p>".$_SESSION['username']."</p>";
                                }
                                echo "<div class='logout'>".
                                    "<form action=".$_SERVER['PHP_SELF']." method='post'>". 
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
        
        <div class="receipt">
            <h1>User Information</h1>
            <?php  
                $stmt = $con->prepare("SELECT firstname, lastname, phonenumber FROM info WHERE user_id = ?");
                $stmt->bind_param('i', $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                echo "<h4>". $row['firstname'] . " " .$row['lastname'] . "</h4>";
                echo "<h4>". $row['phonenumber']."</h4>";
            ?>

            <form action="checkout_process.php" method="POST">
                <h1 class="p">Pick Up Location</h1>

                <div class="pmf">
                    <div class="pmfr">
                        <input type="radio" name="pl" id="hau" value="Holy Angel University - Main Gate" checked required>
                        <label for="hau">Holy Angel University - Main Gate</label>
                    </div>
                    
                    <div class="pmfr">
                        <input type="radio" name="pl" id="smc" value="SM Clark Main Entrance">
                        <label for="smc">SM City Clark - Main Entrance</label>
                    </div>
                    <div class="pmfr">
                        <input type="radio" name="pl" id="smt" value="SM Telebastagan - Food Court">
                        <label for="smt">SM Telebastagan - Food Court</label>
                    </div>
                    <div class="pmfr">
                        <input type="radio" name="pl" id="mqm" value="Marquee Mall - J.CO Entrance">
                        <label for="mqm">Marquee Mall - J.CO Entrance</label>
                    </div>
                </div>

                <h1 class="p">Products Ordered</h1>
                <div class="prod-ord">
            <?php 
                $placeholders = implode(',', array_fill(0, count($checkout_items), '?'));
                
                if(!isset($_POST['buy_now'])) {
                    $stmt = $con->prepare("SELECT *, p.photocard_title, p.price 
                                        FROM cart c
                                        JOIN merch_exchange.products p ON c.product_id = p.product_id
                                        WHERE user_id = ?
                                        AND c.product_id IN ($placeholders)"
                                        );

                    $types = str_repeat('i', count($checkout_items) + 1);
                    $params = array_merge([$_SESSION['user_id']], $checkout_items);
                    $stmt->bind_param($types, ...$params);
                } else {
                    $stmt = $conp->prepare("SELECT photocard_title, price
                                            FROM products
                                            WHERE product_id = ?");
                    $stmt->bind_param("i", $_POST['checkout_items']);
                }
                $stmt->execute();
                $result = $stmt->get_result();


                while($row = $result->fetch_assoc()) { ?>
                    <h5><?= $row['photocard_title'] ?> </h5>
                    <p>Quantity<strong><?= !empty($row['quantity']) ? $row['quantity'] : (!empty($_POST['num_ordered']) ? $_POST['num_ordered'] : ''); ?></strong></p>
                    <p>Price<strong><?= $row["price"]?></strong></p>
                    <input type="hidden" name="checkout_items[]" value="<?= !empty($row['Product_ID']) ? $row['Product_ID'] : (!empty($_POST['checkout_items']) ? $_POST['checkout_items'] : ''); ?>">
                    <input type="hidden" name="num_ordered[]" value="<?= !empty($row['quantity']) ? $row['quantity'] : (!empty($_POST['num_ordered']) ? $_POST['num_ordered'] : ''); ?>">
                    <input type="hidden" name="price[]" value="<?= $row['price']; ?>">
                <?php }; 
                    $stmt->close();
                    $con->close();
                ?>
                </div>
                <p>Total Price: &#8369;<?= !empty($_POST['total_price']) ? $_POST['total_price'] : ($_POST['price'] * $_POST['num_ordered']);?></p>

                <label for="payment_method">Select Payment Method:</label><br>
                <input type="radio" class="pm" name="payment_method" value="Cash on Meetup" required checked>Cash on Meetup<br>
                <input type="radio" class="pm" name="payment_method" value="GCASH" required>GCash<br>

                <div class="po-btn"><button class="place_order" name="place_order" type="submit">PLACE ORDER</button></div>
            </form>
            <a href="cart.php" class="back">< Back to Cart</a>
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
                            <li><a href="shopping_bag">Shopping Bag</a></li>
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
    </body>
</html>        
