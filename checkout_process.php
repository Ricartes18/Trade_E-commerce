<?php
session_start();
include 'admin/connection.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $username = $_SESSION['user_id'];
    $total_price = 0;
    $order_details= [];

    if(!isset($_POST['payment_method']) || !in_array($_POST['payment_method'], ['Cash on Meetup', 'GCash'])) {
        die("Invalid payment method selected.");
    }
    $payment_method = $_POST['mode_of_payment'];

    $pickup_location = "Sa Puke ni Sof";

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conp->prepare("SELECT Photocard_Title, Price FROM products WHERE Product_ID = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($title, $price);
        $stmt->fetch();
        $stmt->close();

    $stmt = $conp->prepare("INSERT INTO orders (user_id, product_id, total_price, num_ordered, pickup_location, mode_of_payment, submitted_date, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Pending')");
    $stmt->bind_param("iidiss", $user_id, $product_id, $total_price, $quantity, $pickup_location, $mode_of_payment);

    if($stmt->execute()) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        echo "Error processing order: " . $stmt->error;
    }
    $stmt->close();

}

$conp->close();

echo "Order placed successfully! Redirecting...";
header("Refresh:2; url=index.php");
}