<?php
include 'admin/connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if(isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $stmt = $con->prepare("INSERT INTO cart (user_id ,product_id, quantity) VALUES (?, ?, ?)
                                    ON DUPLICATE KEY 
                                    UPDATE quantity = quantity + VALUES(quantity)");
    $stmt->bind_param('iii', $user_id,$product_id, $_POST['quantity']);

    

    if ($stmt->execute()) {
        echo "Added to cart successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $con->close();
}

// if (isset($_GET['add'])) {
//     $product_id = intval($_GET['add']);
//     $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
// }

// if (isset($_GET['minus'])) {
//     $product_id = intval($_GET['minus']);

//     if(isset($_SESSION['cart'][$product_id])) {
//         $_SESSION['cart'][$product_id]--;

//         if ($_SESSION['cart'][$product_id] <= 0) {
//             unset($_SESSION['cart'][$product_id]);
//         }
//     }
// }

// if (isset($_GET['remove'])) {
//     $product_id = intval($_GET['remove']);
//     unset($_SESSION['cart'][$product_id]);
//     }


if (isset($_GET['clear'])) {
    $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: cart.php');
    exit();
}

header("Location: product.php?id=$product_id");
exit();

