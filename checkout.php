<?php
session_start();
include 'admin/connection.php';

// if ($_SESSION['cart'] == 'empty') {
//     header("Location: cart.php");
//     exit();
// }

    $checkout_items = isset($_POST['checkout_items']) ? (array) $_POST['checkout_items'] : [];
    $num_ordered = isset($_POST['num_ordered']) ? (array) $_POST['num_ordered'] : [];
    $price = isset($_POST['price']) ? (array) $_POST['price'] : [];

?>

<!DOCTYPE html>
<html lang= "en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="css/checkout.css">
    </head>
    <body>
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
            <h1>Pick Up Location</h1>

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

            <h1>Products Ordered</h1>
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
                <p>Quantity <?= !empty($row['quantity']) ? $row['quantity'] : (!empty($_POST['num_ordered']) ? $_POST['num_ordered'] : ''); ?></p>
                <p>Price <?= $row["price"]?></p>
                <input type="hidden" name="checkout_items[]" value="<?= !empty($row['Product_ID']) ? $row['Product_ID'] : (!empty($_POST['checkout_items']) ? $_POST['checkout_items'] : ''); ?>">
                <input type="hidden" name="num_ordered[]" value="<?= !empty($row['quantity']) ? $row['quantity'] : (!empty($_POST['num_ordered']) ? $_POST['num_ordered'] : ''); ?>">
                <input type="hidden" name="price[]" value="<?= $row['price']; ?>">
            <?php }; 
                $stmt->close();
                $con->close();
            ?>
            <p>Total Price: &#8369;<?= !empty($_POST['total_price']) ? $_POST['total_price'] : ($_POST['price'] * $_POST['num_ordered']);?></p>

            <label for="payment_method">Select Payment Method:</label><br>
            <input type="radio" name="payment_method" value="Cash on Meetup" required checked>Cash on Meetup <br>
            <input type="radio" name="payment_method" value="GCASH" required>GCash<br><br>

            <button name="place_order" type="submit">PLACE ORDER</button>
        </form>

        <br>
        <a href="cart.php">Back to Cart</a>
    </body>
</html>        
