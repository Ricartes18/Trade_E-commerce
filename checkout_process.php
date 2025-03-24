<?php
    session_start();
    include 'admin/connection.php';

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    } 

    $checkout_items = isset($_POST['checkout_items']) ? (array) $_POST['checkout_items'] : [];
    $num_ordered = isset($_POST['num_ordered']) ? (array) $_POST['num_ordered'] : [];
    $price = isset($_POST['price']) ? (array) $_POST['price'] : [];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $conp->begin_transaction();
        
        try {
            $stmt_order = $conp->prepare("INSERT INTO orders (user_id, product_id, num_ordered, submitted_date) 
                                    VALUES (?, ?, ?, NOW())");
            
            $stmt_details = $conp->prepare("INSERT INTO order_details (order_id, total_price, pickup_location, mode_of_payment, status) 
                                    VALUES (?, ?, ?, ?, 'Pending')");

            $total_price = 0;
            $order_ids = [];

            foreach($checkout_items as $index => $product_id){
                $num = $num_ordered[$index];
                $item_price = $price[$index];
                $total_price += $num * $item_price;

                $stmt_order->bind_param('iii', $_SESSION['user_id'], $product_id, $num);
                $stmt_order->execute();

                $order_ids[] = $stmt_order->insert_id;
            }

            foreach ($order_ids as $order_id) {
                $stmt_details->bind_param("idss", $order_id, $total_price, $_POST['pl'], $_POST['payment_method']);
                $stmt_details->execute();
            }
            $conp->commit();
            echo "Orders added successfully!";
        } catch(Exception $e){
            $conp->rollback();
            echo "Error: " . $e->getMessage();
        }
            
        if (!empty($checkout_items)) { 
            $placeholders = implode(',', array_fill(0, count($checkout_items), '?'));
            $types = str_repeat('i', count($checkout_items) + 1);
            $params = array_merge([$_SESSION['user_id']], $checkout_items);
        
            $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ? AND product_id IN ($placeholders)");
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();

            $stmt_order->close();
            $stmt_details->close();

            $conp->close();
            $con->close();
            header('Location: index.php');
            exit();

        }
    }
?>
