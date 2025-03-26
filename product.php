<?php 
    include 'admin/connection.php';
    session_start();
    if(!isset($_GET['id']) || $_GET['id'] === "") {
        header('Location: index.php');
        die('Product not found!');
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
    <title>Document</title>
</head>
<body>
    <h1><?= $row['Photocard_Title']; ?></h1>
    <img width='300' src="products/<?php echo $row['Product_Image']; ?>" alt="<?= $row['Photocard_Title'] ?>">
    <p><?= $row['Description'];?></p>
    <p>Price: &#8369; <?= $row['Price'];?></p>
    <p>Quantity: <?= $row['Quantity'];?></p>
    <?php
        if($row['Tradable']) : ?>
        <p style="color: green; font-weight: bold;">Tradable</p>
        <a href="">Trade</a>
    <?php endif; ?>
    <form action="cart_process.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $row['Product_ID'];?>">
        <input type="number" name="quantity" id="quantity_input" value=1 min=1 max=<?= $row["Quantity"]?>>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
    <form action="checkout.php" method="POST">
    <input type="hidden" name="checkout_items" value="<?= $row['Product_ID'];?>">
    <input type="hidden" name="num_ordered" id="quantity_buy" value=1>
    <input type="hidden" name="price" value=<?= $row['Price']?>>
        <button type="submit" name="buy_now">Buy Now</button>
    </form>

</body>

<script>
    document.getElementById('quantity_input').addEventListener('input', function () {
        document.getElementById('quantity_buy').value = this.value;
    });
</script>

</html>
