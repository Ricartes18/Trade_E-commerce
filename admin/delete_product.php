<?php
include 'connection.php';

if (!isset($_GET['id'])) {
    die("Product not found!");
}

$product_id = intval($_GET['id']);

$stmt = $conp->prepare("SELECT Product_Image FROM products WHERE Product_ID = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$procut = $result->fetch_assoc();

if (!empty($product['Product_Image']) && $product['Product_Image'] !== 'default.png') {
    $image_path = realpath(__DIR__ . '/../products/' . $product['Product_Image']);
    if ($image_path && file_exists($image_path)) {
        unlink($image_path);
    }
}

$stmt = $conp->prepare("DELETE FROM products WHERE Product_ID = ?");
$stmt->bind_param('i', $product_id);

if ($stmt->execute()) {
    header("Location: products.php");
    exit();
} else {
    echo "Error: Could not delete the product.";
}
?>


