<?php
    session_start();
    include 'connection.php';

    // Redirect non-admin users
    /*if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    } */

    // Handle accept/reject actions
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['trade_id'], $_POST['action'])) {
        $trade_id = intval($_POST['trade_id']);
        $status = ($_POST['action'] === 'accept') ? 'Accepted' : 'Declined';
        
        $stmt = $conp->prepare("UPDATE trade SET Trade_Status = ? WHERE Trade_ID = ?");
        $stmt->bind_param("si", $status, $trade_id);
        $stmt->execute();
        $stmt->close();
    }

    // Fetch pending trades
    $result = $conp->query("SELECT t.Trade_ID, t.username, p.Photocard_Title, t.Trade_Offer FROM trade t JOIN products p ON t.Product_ID = p.Product_ID WHERE t.Trade_Status = 'Pending'");
    
    $trades = [];
    while ($row = $result->fetch_assoc()) {
        $trades[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/trades_pending.css">
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <title>Pending Trades</title>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../img/PoCaSwap Logo.png" alt="Logo">
            <div class="title">PoCaSwap</div>
        </div>
        <div class="nav">
            <a href="#">Home</a>
            <a href="#">Products</a>
            <a href="#">Pending Orders</a>
            <a href="#">Pending Trades</a>
        </div>
    </div>
    
    <div class="main-container">
        <h1>Pending Trades</h1>
        <div class="trade-container">
            <?php foreach ($trades as $trade): ?>
                <div class="trade-card">
                    <p><strong>User:</strong> <?= htmlspecialchars($trade['username']) ?></p>
                    <p><strong>Photocard:</strong> <?= htmlspecialchars($trade['Photocard_Title']) ?></p>
                    <p><strong>Offer:</strong> <?= htmlspecialchars($trade['Trade_Offer']) ?></p>
                    <form method="post">
                        <input type="hidden" name="trade_id" value="<?= $trade['Trade_ID'] ?>">
                        <button type="submit" name="action" value="accept" class="accept">ACCEPT</button>
                        <button type="submit" name="action" value="reject" class="reject">REJECT</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
