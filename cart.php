<?php
session_start();
include 'admin/connection.php';

    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }

    $stmt = $con->prepare("SELECT *, c.quantity FROM cart c
                                JOIN merch_exchange.products p ON c.product_id = p.product_id 
                                WHERE user_id = ?;");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;
    ($result->num_rows > 0) ? $_SESSION['cart'] = 'has_items' : $_SESSION['cart'] = 'empty';
    $stmt->close();
    $con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Bading</h1>
    <?php if($_SESSION['cart'] == 'has_items'): ?>
        <form id="checkoutForm" action="checkout.php" method="POST">
            <ul>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <li>
                        <input type="checkbox"  name="checkout_items[]" value="<?= $row['Product_ID']; ?>" 
                        data-price="<?= floatval($row['Price']); ?>"
                        data-quantity="<?= intval($row['quantity']); ?>" onchange="updateTotal()">
                        <img src="products/<?= $row['Product_Image']; ?>" width="50">
                        
                        <?= $row['Photocard_Title']; ?> - &#8369; <?= $row['Price']; ?>
                        <br> Quantity: <?= $row['quantity']; ?>
                        <a href="cart_process.php?add=<?= $row['Product_ID']; ?>&qty=<?= $row['quantity']; ?>">➕</a>
                        <a href="cart_process.php?minus=<?= $row['Product_ID']; ?>">➖</a>
                        <a href="cart_process.php?remove=<?= $row['Product_ID']; ?>">❌ Remove</a>
                    </li>
                <?php } ?>
            </ul>
            <p><strong>Total: <span id="totalPrice">₱ 0.00</span></strong></p>
            <input type="hidden" name="total_price" id="totalPriceInput" value="0">
        <button type="submit" name="cart_checkout" id="checkoutBtn">Proceed to Checkout</button>
        <br><br>
        </form>

        
        <a href="cart_process.php?clear=true" style="color: red;">🗑️ Clear Cart</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let checkboxes = document.querySelectorAll('input[name="checkout_items[]"]');
        let checkoutBtn = document.getElementById('checkoutBtn');

        function checkSelection() {
            let checked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            checkoutBtn.disabled = !checked;
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkSelection);
        });

        checkSelection();
    });

    document.addEventListener("DOMContentLoaded", function () {
        let checkboxes = document.querySelectorAll('input[name="checkout_items[]"]');
        let totalPriceElement = document.getElementById('totalPrice');
        let checkoutBtn = document.getElementById('checkoutBtn');

        function updateTotal() {
            let total = 0;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    let price = parseFloat(checkbox.dataset.price) || 0;
                    let quantity = parseInt(checkbox.dataset.quantity) || 1;
                    total += price * quantity;
                }
            });

            totalPriceElement.textContent = `₱ ${total.toFixed(2)}`; 
            document.getElementById('totalPriceInput').value = total; 
            checkoutBtn.disabled = total === 0; 
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        updateTotal();
    });

</script>

</html>
