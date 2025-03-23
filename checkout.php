<?php
session_start();
include 'admin/connection.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $conp->prepare("SELECT * FROM products WHERE Product_ID IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($_SESSION['cart'])), ...array_keys($_SESSION['cart']));
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['Product_ID']];
        $cart_items[] = $row;
        $total_price += $row['Price'] * $row['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang= "en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intial-scale=1.0">
        <title>Checkout</title>
    </head>
    <body>
        <h1>Checkout</h1>
        <p>Total Price: <strong>&#8369; <?=number_format($total_price, 2); ?></strong></p>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li>
                    <?= $item['Photocard_Title']; ?> - Quantity: <?= $item['quantity']; ?>
                    <br> Price: &#8369; <?= number_format($item['Price'] * $item['quantity'], 2); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <form action="checkout_process.php" method="post">
            <label for="payment_method">Select Payment Method:</label><br>
            <input type="radio" name="payment_method" value="COD" required>Cash on Meetup <br>
            <input type="radio" name="payment_method" value="Bank Transfer" required>GCash<br><br>

            <button type="submit" name="place_order">Place Order</button>
        </form>

        <br>
        <a href="cart.php">Back to Cart</a>
    </body>
</html>        
