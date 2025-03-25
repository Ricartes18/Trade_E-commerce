<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$next_filter = ($filter == 'tradable') ? 'non-tradable' : 'tradable';
$toggle_label = ($filter == 'tradable') ? 'Show Non-Tradable' : 'Show Tradable';

if ($filter == 'tradable') {
    $stmt = $conp->prepare("SELECT * FROM products WHERE Tradable = 1");
} elseif ($filter == 'non-tradable') {
    $stmt = $conp->prepare("SELECT * FROM products WHERE Tradable = 0");
} else {
    $stmt = $conp->prepare("SELECT * FROM products");
}

$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-btn {
            padding: 10px 20px;
            background: blue;
            color: white;
            text-decoration: none;
        }

        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            margin: 15% auto;
            width: 300px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Cedric Pisot</h1>
    <a href="add_product.php" class="add-btn">➕ Add Product</a>

    <div class="filter-buttons">
        <button onclick="toggleFilter('<?= $next_filter ?>')"><?= $toggle_label ?></button>
        <button onclick="filterProducts('all')">View All</button>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Tradable</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
            <tr>
                <td><img src="../products/<?= !empty($row['Product_Image']) ? $row['Product_Image'] : 'default.png' ?>" width="50"></td>
                <td><?= $row['Photocard_Title'] ?></td>
                <td>&#8369;<?= $row['Price'] ?></td>
                <td><?= $row['Tradable'] ? 'Yes' : 'No' ?></td>
                <td><?= $row['Quantity'] ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['Product_ID'] ?>">Edit</a> |
                    <a href="#" onclick="confirmDelete(<?= $row['Product_ID'] ?>)">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you absolutely sure you want to delete this product?</p>
            <button id="confirmDeleteBtn">Yes, Delete</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <script>
        let deleteProductId = null;

        function confirmDelete(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                deleteProductId = productId;
                document.getElementById("deleteModal").style.display = "block";
            }
        }

        document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
            if (deleteProductId) {
                window.location.href = "delete_product.php?id=" + deleteProductId;
            }
        });

        function closeModal() {
            document.getElementById("deleteModal").style.display = "none";
        }

        function toggleFilter(filter) {
            window.location.href = "products.php?filter=" + filter;
        }

        function filterProducts(filter) {
            window.location.href = "products.php?filter=" + filter;
        }
    </script>
</body>
</html>
