<?php 

    include 'admin/connection.php';
    session_start();
    if(!isset($_GET['id'])) {
        die('Product not found!');
    }

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    } 

    $product_id = intval($_GET['id']);
    $stmt = $conp->prepare("SELECT * FROM products WHERE Product_ID = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo 'Product not found!';
    }

    $row = $result->fetch_assoc();

    $recently_viewed = isset($_COOKIE['recently_viewed']) ? json_decode($_COOKIE['recently_viewed'], true) : [];

    // if it exists already, it will be removed to not have duplicates
    if (($key = array_search($product_id, $recently_viewed)) !== false) {
        unset($recently_viewed[$key]);
    }

    // show it in the first
    array_unshift($recently_viewed, $product_id);

    //limits the recently viewed to 5
    $recently_viewed = array_slice($recently_viewed, 0, 5);

    // store the new recently viewed in cookie
    setcookie('recently_viewed', json_encode($recently_viewed), time() + (86400 * 7), "/"); // Expires in 7 days

    $stmt->close();
    $conp->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Product</title>
    <style>
        input[type="number"] {
            font-family: 'Changa One';
            font-weight: normal;
            font-size: 16px;
            color: #5A21A5;
            -moz-appearance: textfield; /* Firefox */
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none; /* Chrome, Safari, Edge */
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">Tracker</a></li>
                <li><a href="#">Trades</a></li>
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
    <section class="photocard-section">
        <div class="photocard-container">
            <div class="photocard-top">
                <div class="photocard-img">
                    <img src="products/<?php echo $row['Product_Image']; ?>" alt="<?= $row['Photocard_Title'] ?>">
                </div>
                <div class="photocard-desc">
                    <h1><?= $row['Photocard_Title']; ?></h1>
                    <p class="photocard-price">PHP <?= $row['Price'];?></p>
                    <h2>Description: </h2>
                    <p class="photocard-desc-text"><?= nl2br($row['Description']);?></p>
                    <p class="photocard-qty">Quantity: <?= $row['Quantity'];?></p>
                </div> 
            </div>
            <div class="photocard-bottom">
                <!-- Quantity Picker on Top -->
                <div class="quantity-picker">
                <form action="cart_process.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $row['Product_ID'];?>">

                    <label for="quantity_input" class="quantity-label">Quantity</label>
                    
                    <div class="quantity-picker">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                        <input type="number" name="quantity" id="quantity_input" value="1" min="1" max="<?= $row['Quantity'] ?>">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                    </div>

                    <!-- Button Row (Now Includes Add to Cart) -->
                    
                        <button class="add-cart-btn" type="submit" name="add_to_cart">ADD TO CART</button>
                    
                </form>
                </div>

                <!-- Buttons Below -->
                <div class="button-row">
                    <div class="buy-button">
                        <form action="checkout.php" method="POST">
                            <input type="hidden" name="checkout_items" value="<?= $row['Product_ID'];?>">
                            <input type="hidden" name="num_ordered" id="quantity_buy" value="1">
                            <input type="hidden" name="price" value="<?= $row['Price'] ?>">
                            <button class ="buy-btn" type="submit" name="buy_now">BUY NOW</button>
                        </form>
                    </div>
                    <div class="trade-button">
                        <?php if ($row['Tradable']) : ?>
                           <form action="trade_upload.php" method="GET">
                                <input type="hidden" name="Product_Description" value="<?= $row['Description']; ?>">
                                <input type="hidden" name="Product_Name" value="<?= $row['Photocard_Title']; ?>">
                                <input type="hidden" name="Product_Image" value="<?= $row['Product_Image']; ?>">
                                <button class="trade-btn" type="submit" name="trade">TRADE</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="added-cart-alert">
                    <?php if (isset($_SESSION['cart_success'])): ?>
                        <p class="cart-message"><?= $_SESSION['cart_success']; ?></p>
                        <?php unset($_SESSION['cart_success']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
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
</body>

<script>
    function changeQuantity(change) {
        let quantityInput = document.getElementById('quantity_input');
        let quantityBuy = document.getElementById('quantity_buy');
        let currentValue = parseInt(quantityInput.value);
        let maxValue = parseInt(quantityInput.max);
        let minValue = parseInt(quantityInput.min);

        let newValue = currentValue + change;
        if (newValue >= minValue && newValue <= maxValue) {
            quantityInput.value = newValue;
            quantityBuy.value = newValue;
        }
    }


    document.getElementById('quantity_input').addEventListener('input', function () {
        document.getElementById('quantity_buy').value = this.value;
    });
</script>

</html>
