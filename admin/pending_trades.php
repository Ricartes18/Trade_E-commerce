<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_trade'])) {
    $tradeID = $_POST['Trade_ID'];
    $newStatus = $_POST['Trade_Status'];

    $stmt = $conp->prepare("UPDATE trade SET Trade_Status = ? WHERE Trade_ID = ?");
    $stmt->bind_param("si", $newStatus, $tradeID);

    if ($stmt->execute()) {
        echo "<script>alert('Trade status updated!'); window.location='pending_trades.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$result = $conp->query("SELECT Trade_ID, Trade_Name, Trade_Description, Trade_Offer, username, Trade_Status FROM trade WHERE Trade_Status = 'Pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/trades_pending.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Climate+Crisis&display=swap" rel="stylesheet">
    <title>Trade Request</title>
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
    <table border="1">
        <tr>
            <th>Product ID</th> 
            <th>Trade Name</th>
            <th>Description</th>
            <th>Trade Offer</th>
            <th>Username</th>
            <th>Action</th>
            <th>Status</th>
        </tr>
        <?php while ($trade = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($trade['Trade_ID']) ?></td> 
            <td><?= htmlspecialchars($trade['Trade_Name']) ?></td>
            <td><?= htmlspecialchars($trade['Trade_Description']) ?></td>
            <td><img src="../trades/<?= htmlspecialchars($trade['Trade_Offer']) ?>" alt="Trade Offer" width="100"></td>
            <td><?= htmlspecialchars($trade['username']) ?></td>
            <td>
                <form action="pending_trades.php" method="post">
                    <input type="hidden" name="Trade_ID" value="<?= $trade['Trade_ID'] ?>">
                    <select name="Trade_Status">
                        <option value="Approved">Approve</option>
                        <option value="Rejected">Reject</option>
                    </select>
                    <button type="submit" name="update_trade">Update</button>
                </form>
            </td>
            <td><?= htmlspecialchars($trade['Trade_Status']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
