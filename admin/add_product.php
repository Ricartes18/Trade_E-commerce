<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $tradable = isset($_POST['tradable']) ? 1 : 0;

    // Upload directory outside the admin folder
    $target_dir = realpath(__DIR__ . '/../products/') . '/';
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Default image if no file is uploaded
    $image_name = "default.png";

    if (!empty($_FILES['image']['name'])) {
        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ["jpg", "jpeg", "png"];
        $allowed_mimes = ["image/jpeg", "image/png"];
        $file_mime = mime_content_type($file_tmp);

        // Validate file type
        if (in_array($file_ext, $allowed_exts) && in_array($file_mime, $allowed_mimes)) {
            $image_name = $file_name; // Keep original filename
            $target_file = $target_dir . $image_name;

            // Move the file to the correct folder
            move_uploaded_file($file_tmp, $target_file);
        }
    }

    // Insert into database
    $stmt = $conp->prepare("INSERT INTO products (Photocard_Title, Description, Price, Quantity, Tradable, Product_Image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $name, $description, $price, $quantity, $tradable, $image_name);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="number" name="price" placeholder="Price" required><br>
    <input type="number" name="quantity" placeholder="Quantity" required><br>
    <label><input type="checkbox" name="tradable"> Tradeable</label><br>
    <input type="file" name="image" accept=".jpg, .jpeg, .png"><br>
    <button type="submit">Add Product</button><br>
</form>

