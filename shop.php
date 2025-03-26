<?php
include 'admin/connection.php';
session_start();

$tradable_filter = isset($_GET['tradable']) ? $_GET['tradable'] : 'all';

$products = [];
$query = "SELECT Product_ID, Product_Image, Photocard_Title, Price, Tradable FROM products";
if ($tradable_filter == 'yes') {
    $query .= " WHERE Tradable = 1";
} elseif ($tradable_filter == 'no') {
    $query .= " WHERE Tradable = 0";
}

$stmt = $conp->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoCaSwap | Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
            background: #f9f9f9;
        }
        .filter-container {
            margin-bottom: 15px;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            max-width: 900px;
            margin: auto;
            justify-content: center;
            align-items: center;
        }
        .product-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
            width: 180px;
            transition: transform 0.3s ease-in-out;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
        }
        .product-title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .product-price {
            color: green;
            font-size: 13px;
        }
        .no-products {
            font-size: 18px;
            color: red;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 500px) {
            .container {
                grid-template-columns: repeat(1, 1fr);
            }
        }
    </style>
</head>
<body>
    <h1>PoCaSwap | Shop</h1>
    
    <div class="filter-container">
        <label for="tradable">Filter by:</label>
        <select id="tradable" onchange="filterProducts()">
            <option value="all" <?= ($tradable_filter == 'all') ? 'selected' : '' ?>>All</option>
            <option value="yes" <?= ($tradable_filter == 'yes') ? 'selected' : '' ?>>Tradable</option>
            <option value="no" <?= ($tradable_filter == 'no') ? 'selected' : '' ?>>Non-Tradable</option>
        </select>
    </div>

    <div class="container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="product.php?id=<?= htmlspecialchars($product['Product_ID']); ?>">
                        <img src="products/<?= !empty($product['Product_Image']) ? htmlspecialchars($product['Product_Image']) : 'default.png' ?>" 
                             alt="<?= htmlspecialchars($product['Photocard_Title']); ?>">
                    </a>
                    <div class="product-title"><?= htmlspecialchars($product['Photocard_Title']); ?></div>
                    <div class="product-price">&#8369;<?= number_format($product['Price'], 2); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">No products available</p>
        <?php endif; ?>
    </div>

    <script>
        function filterProducts() {
            var tradable = document.getElementById('tradable').value;
            window.location.href = "shop.php?tradable=" + tradable;
        }
    </script>

</body>
</html>
