<?php 
    session_start();
    include 'connection.php';
    if($_SESSION['role'] != 'admin'){
        header("Location: ../index.php");
        exit();
    }

    $status = ['Pending', 'Ongoing', 'Delivered', 'Cancelled'];

    foreach ($status as $st){
        $stmt = $conp->prepare("SELECT *, i.username, od.status, od.orderdetails_id 
                                FROM order_details od
                                JOIN orders o ON od.order_id = o.order_id
                                JOIN user.info i ON o.user_id = i.user_id
                                WHERE status = ?;");
    $stmt->bind_param('s', $st);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_data[$st] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    }
    
    $orderDetails = null;
    if (isset($_GET['orderdetails_id'])) {
        $orderdetails_id = $_GET['orderdetails_id'];
        $stmt = $conp->prepare("SELECT o.order_ID, od.pickup_location, od.mode_of_payment, od.submitted_date, p.photocard_title, o.num_ordered
                                FROM order_details od
                                JOIN orders o ON o.order_id = od.order_id
                                JOIN products p ON p.product_id = o.product_id
                                WHERE orderdetails_id = ?");
        $stmt->bind_param('i', $orderdetails_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $orderDetails = $result->fetch_assoc();
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode($orderDetails);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $conp->prepare("UPDATE order_details 
                                        SET status = ? 
                                        WHERE orderdetails_id = ?");
        $stmt->bind_param('si', $_POST['update'], $_POST['orderdetails_id']);
        $stmt->execute();
        $stmt->close();
        $conp->close();
        header('location:' . $_SERVER['PHP_SELF']);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Climate+Crisis&display=swap" rel="stylesheet">
    <title>Pending Orders</title>
    <link rel="stylesheet" href="../css/orders.css">
</head>
<body>
    <div class="header">
        <div class="logo">
        <a href="dashboard.php">
            <img src="../images/PoCaSwap Logo.png" alt="Logo">
        </a>
            <div class="title">
                <span>PoCaSwap</span>
            </div>
        </div>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Pending Orders</a>
            <a href="pending_trades.php">Pending Trades</a>
        </div>
        </div>
        <h2>Pending Trades</h2>
        <div class="status">
            <?php foreach ($status as $st) : ?>
                <?php $id = strtolower(str_replace(' ', '-', $st)); ?> 
                <button id="<?= $id ?>-btn" onclick="showTab('<?= htmlspecialchars($st, ENT_QUOTES) ?>')">
                    <?= htmlspecialchars($st) ?> (<?= count($order_data[$st] ?? []) ?>)
                </button>
            <?php endforeach; ?>
        </div>


        
        <?php foreach($status as $st) : ?>
            <div id="<?= $st ?>" class="tab-content">
                <?php if(empty($order_data[$st])) :
                    echo '<p> No Orders Yet</p>'; ?>
                <?php else: ?>
                <table>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>

                
                <?php foreach($order_data[$st] as $detail) : ?>
                <tr>
                    <td><form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
                    <button name='orderID' type="button" data-order-id="<?= $detail['OrderDetails_ID'] ?>" value=<?= $detail['OrderDetails_ID'] ?>><?= $detail['Order_ID'] ?></button>
                    </form></td>
                    <td><?= $detail['username'] ?></td>
                    <td><?= $detail['total_price']?></td>
                    <td><?= $detail['status'] ?></td>
                    <td>
                        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                            <select name="update" id="update">
                                <?php foreach($status as $opt) :?>    
                                    <option value="<?= $opt ?>" <?= $detail['status'] == $opt ? 'selected' : "" ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                            </select>
                            
                            <input type="hidden" name="orderdetails_id" value=<?= $detail['orderdetails_id'] ?>>
                            <button type="submit">&#10003;</button>
                        </form>
                    
                    </td>
                </tr>
                <?php endforeach; ?>

                </table>
            <?php endif; ?>
            </div>
        <?php endforeach;?>
                                    

        <div id="modale" class="modal">
            
            <div class="check_details">
                <span class="close">&times;</span>
                <p></p>
            </div>

        </div>

    <script>

        function showTab(status) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active-tab');
            });
            document.getElementById(status).classList.add('active-tab');
        }
        // Show the "Pending" tab by default
        showTab('Pending');


        var modal = document.getElementById("modale");

        // Get the button that opens the modal
        var buttons = document.querySelectorAll('[data-order-id]');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
            var orderId = this.getAttribute('data-order-id');

            fetch('orders.php?orderdetails_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                // Dynamically update the content with the fetched data
                var modalContent = modal.querySelector('.check_details');
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <p>Order ID: ${data.order_ID}<br>
                    Pickup Location: ${data.pickup_location}<br>
                    Mode of Payment: ${data.mode_of_payment}<br>
                    Submitted Date: ${data.submitted_date}<br>
                    Photocard Title: ${data.photocard_title}<br>
                    Quantity Ordered: ${data.num_ordered}</p>
                `;

                modal.style.display = "block";

                var close = modal.querySelector(".close");
                close.onclick = function() {
                modal.style.display = "none";
                }
            })
            .catch(error => console.error('Error:', error));

            });
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
