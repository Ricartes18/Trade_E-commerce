<?php
require 'admin/connection.php';

$ordersQuery = "SELECT od.orderdetails_id, od.order_Id, od.total_price, od.pickup_location, od.mode_of_payment, 
                       od.submitted_date, od.status, p.Product_Image 
                FROM order_details od 
                JOIN products p ON od.order_Id = p.Product_ID
                ORDER BY od.submitted_date DESC";
$ordersResult = mysqli_query($conp, $ordersQuery);

$tradesQuery = "SELECT t.trade_id, t.username, t.product_id, t.trade_name, t.trade_description, 
                       t.trade_offer, t.trade_status, p.Product_Image 
                FROM trade t 
                JOIN products p ON t.product_id = p.Product_ID
                ORDER BY t.trade_id DESC";
$tradesResult = mysqli_query($conp, $tradesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, intial-scale=1.0">
    <title>Order & Trade Tracker</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 20px;
        background-color: #f4f4f4;
        text-align: center;
    }
    h2 {
        color: #333;
    }
    button {
        background-color: #007BFF;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 20px;
        transition: background 0.3s ease;
    }
    button:hover {
        background-color: #0056b3;
    }
    table {
        width: 90%;
        margin: auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background: #007BFF;
        color: white;
    }
    tr:nth-child(even) {
        background: #f2f2f2;
    }
    tr:hover {
        background: #ddd;
    }
    td img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
    }
    td:first-child {
        text-align: left;
        padding-left: 10px;
    }
    </style>
</head>
<body>

<h2>Order & Trade Tracker</h2>
<button onclick="toggleTracker()">Show Trades</button>

<!-- Orders Table -->
<table id="ordersTable">
    <tr>
        <th>Product Image</th>
        <th>Order ID</th>
        <th>Total Price</th>
        <th>Pickup Location</th>
        <th>Mode of Payment</th>
        <th>Submitted Date</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($ordersResult)) : ?>
        <tr>
            <td><img src="products/<?php echo htmlspecialchars($row['Product_Image']); ?>" alt="Product Image" width="50"></td>
            <td><?php echo htmlspecialchars($row['order_Id']); ?></td>
            <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
            <td><?php echo htmlspecialchars($row['pickup_location']); ?></td>
            <td><?php echo htmlspecialchars($row['mode_of_payment']); ?></td>
            <td><?php echo htmlspecialchars($row['submitted_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Trades Table -->
<table id="tradesTable" style="display: none;">
    <tr>
        <th>Product Image</th>
        <th>Trade ID</th>
        <th>Username</th>
        <th>Trade Name</th>
        <th>Description</th>
        <th>Trade Offer</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($tradesResult)) : ?>
        <tr>
            <td><img src="uploads/<?php echo htmlspecialchars($row['Product_Image']); ?>" alt="Product Image" width="50"></td>
            <td><?php echo htmlspecialchars($row['trade_id']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['trade_name']); ?></td>
            <td><?php echo htmlspecialchars($row['trade_description']); ?></td>
            <td><?php echo htmlspecialchars($row['trade_offer']); ?></td>
            <td><?php echo htmlspecialchars($row['trade_status']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
function toggleTracker() {
    let ordersTable = document.getElementById("ordersTable");
    let tradesTable = document.getElementById("tradesTable");
    let button = document.querySelector("button");

    if (ordersTable.style.display === "none") {
        ordersTable.style.display = "table";
        tradesTable.style.display = "none";
        button.innerText = "Show Trades";
    } else {
        ordersTable.style.display = "none";
        tradesTable.style.display = "table";
        button.innerText = "Show Orders";
    }
}
</script>

</body>
</html>

