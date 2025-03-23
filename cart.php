<?php
session_start();
include 'admin/connection.php';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Cedric Bading</h1>
    <?php if(!empty($cart_items)): ?>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li>
                    <img src="products/<?= $item['Product_Image']; ?>" width="50">
                    <?= $item['Photocard_Title']; ?> - &#8369; <?= $item['Price']; ?>
                    <br> Quantity: <?= $item['quantity']; ?>
                    <a href="cart_process.php?add=<?= $item['Product_ID']; ?>">➕</a>
                    <a href="cart_process.php?minus=<?= $item['Product_ID']; ?>">➖</a>
                    <a href="cart_process.php?remove=<?= $item['Product_ID']; ?>">❌ Remove</a>
                </li>
            <?php endforeach; ?>
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