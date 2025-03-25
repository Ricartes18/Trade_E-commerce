<?php
include 'connection.php';

if (!isset($_GET['id'])) {
    die("Product not found!");
}

$product_id = intval($_GET['id']);
$stmt = $conp->prepare("SELECT * FROM products WHERE Product_ID = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $tradable = isset($_POST['tradable']) ? 1 : 0;

    // Upload directory outside the admin folder
    $target_dir = realpath(__DIR__ . '/../products/') . '/';

    // Ensure the `products/` directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Keep existing image if no new file is uploaded
    $image_name = $product['Product_Image'];

    if (!empty($_FILES['image']['name'])) {
        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ["jpg", "jpeg", "png"];
        $allowed_mimes = ["image/jpeg", "image/png"];
        $file_mime = mime_content_type($file_tmp);

        if (in_array($file_ext, $allowed_exts) && in_array($file_mime, $allowed_mimes)) {
            // Delete the old image (only if it's not the default image)
            if (!empty($product['Product_Image']) && $product['Product_Image'] !== 'default.png') {
                $old_image_path = $target_dir . $product['Product_Image'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            // Save new image
            $image_name = $file_name;
            $target_file = $target_dir . $image_name;
            move_uploaded_file($file_tmp, $target_file);
        }
    }

    // Update product details in the database
    $stmt = $conp->prepare("UPDATE products SET Photocard_Title=?, Description=?, Price=?, Quantity=?, Tradable=?, Product_Image=? WHERE Product_ID=?");
    $stmt->bind_param("ssdissi", $name, $description, $price, $quantity, $tradable, $image_name, $product_id);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= $product['Photocard_Title'] ?>" required><br>
    <textarea name="description"><?= $product['Description'] ?></textarea><br>
    <input type="number" name="price" value="<?= $product['Price'] ?>" required><br>
    <input type="number" name="quantity" value="<?= $product['Quantity'] ?>" required><br>
    <label><input type="checkbox" name="tradable" <?= $product['Tradable'] ? 'checked' : '' ?>> Tradeable</label><br>

    <!-- Display current product image -->
    <p>Current Image:</p>
    <img src="../products/<?= !empty($product['Product_Image']) ? $product['Product_Image'] : 'default.png' ?>" width="100"><br>

    <!-- Upload new image -->
    <input type="file" name="image" accept=".jpg, .jpeg, .png"><br>
    <button type="submit">Update Product</button>
</form>
