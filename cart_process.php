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

    if (isset($_GET['add'])) {
        $product_id = intval($_GET['add']);
        $stmt = $con->prepare("SELECT c.product_id, c.quantity, p.quantity 
                            FROM cart c
                            JOIN merch_exchange.products p ON c.product_id = p.product_id
                            WHERE c.user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['quantity'] < $_GET['qty']) {
            header('Location: cart.php');
            exit();
        } else {
            $stmt = $con->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $stmt->close();
            header('Location: cart.php');
            exit();
        }
    }

    if (isset($_GET['minus'])) {
        $product_id = intval($_GET['minus']);
        $stmt = $con->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ? AND quantity > 1");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();

        // Quan = 0 remove item from cart
        $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ? AND quantity <= 0");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
        header('Location: cart.php');
        exit();
    }

    if (isset($_GET['remove'])) {
        $product_id = intval($_GET['remove']);
        $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }


    if (isset($_GET['clear'])) {
        $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_GET['remove'])) {
        $product_id = intval($_GET['remove']);
        header('Location: cart.php');
        exit();
        }


    if (isset($_GET['clear'])) {
        $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['cart'] = 'empty';
        header('Location: cart.php');
        exit();
    }

    header("Location: product.php?id=$product_id");
    exit();
?>
