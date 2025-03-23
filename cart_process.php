<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
}

if (isset($_GET['minus'])) {
    $product_id = intval($_GET['minus']);

    if(isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]--;

        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
    }


if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
}

header("Location: cart.php");
exit();
