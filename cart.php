<?php
session_start();
include 'admin/connection.php';

    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }

    $stmt = $con->prepare("SELECT * FROM cart c
                                JOIN merch_exchange.products p ON c.product_id = p.product_id 
                                WHERE user_id = ?;");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;
    if($stmt->num_rows > 0) {
        $_SESSION['cart'] = 'empty';
    } else {
        $_SESSION['cart'] = 'has_items';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Cedric Bading</h1>
    <?php if($_SESSION['cart'] == 'has_items'): ?>
        <ul>
            <?php while($row = $result->fetch_assoc()) { ?>
                <li>
                    <img src="products/<?= $row['Product_Image']; ?>" width="50">
                    <?= $row['Photocard_Title']; ?> - &#8369; <?= $row['Price']; ?>
                    <br> Quantity: <?= $row['quantity']; ?>
                    <a href="cart_process.php?add=<?= $item['Product_ID']; ?>">➕</a>
                    <a href="cart_process.php?minus=<?= $item['Product_ID']; ?>">➖</a>
                    <a href="cart_process.php?remove=<?= $item['Product_ID']; ?>">❌ Remove</a>
                </li>
            <?php 
                    $total_price += $row['Price'] * $row['quantity'];
                    } ?>
        </ul>
        <p><strong>Total: &#8369; <?= $total_price; ?></strong></p>
        <a href="checkout.php">Proceed to Checkout</a>
        <br><br>
        <a href="cart_process.php?clear=true" style="color: red;">🗑️ Clear Cart</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>
