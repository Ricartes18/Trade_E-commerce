<?php 
    session_start();
    include 'connection.php';
    if($_SESSION['role'] != 'admin'){
        header("Location: ../index.php");
        exit();
    }

    $trade_statuses = ['Pending', 'Ongoing', 'Completed', 'Declined'];

    foreach ($trade_statuses as $status) {
        $stmt = $conp->prepare("SELECT * FROM trade WHERE Trade_Status = ? ORDER BY Trade_ID DESC;");
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $trade_data[$status] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $conp->prepare("UPDATE trade SET Trade_Status = ? WHERE Trade_ID = ?");
        $stmt->bind_param('si', $_POST['update'], $_POST['trade_id']);
        $stmt->execute();
        $stmt->close();
        $conp->close();

        header('location:' . $_SERVER['PHP_SELF'] . '?status_updated=1');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/trades.css">
    <title>Pending Trades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status button {
            margin: 5px;
            padding: 10px;
            cursor: pointer;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
        }
        .status button:hover {
            background-color: #0056b3;
        }
        .tab-content {
            display: none;
        }
        .active-tab {
            display: block;
        }
    </style>
</head>
<body>
    <h2>Pending Trades</h2>
    <div class="status">
        <?php foreach ($trade_statuses as $status) : ?>
            <button onclick="showTab('<?= htmlspecialchars($status, ENT_QUOTES) ?>')">
                <?= htmlspecialchars($status) ?> (<?= count($trade_data[$status] ?? []) ?>)
            </button>
        <?php endforeach; ?>
    </div>

    <?php foreach($trade_statuses as $status) : ?>
        <div id="<?= htmlspecialchars($status, ENT_QUOTES) ?>" class="tab-content">
            <?php if(empty($trade_data[$status])) : ?>
                <p>No Trades Yet</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>Trade ID</th>
                    <th>Username</th>
                    <th>Product ID</th>
                    <th>Trade Name</th>
                    <th>Description</th>
                    <th>Trade Offer</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
                <?php foreach($trade_data[$status] as $trade) : ?>
                <tr class="trade-row" data-status="<?= htmlspecialchars($trade['Trade_Status']) ?>">
                    <td><?= htmlspecialchars($trade['Trade_ID']) ?></td>
                    <td><?= htmlspecialchars($trade['username']) ?></td>
                    <td><?= htmlspecialchars($trade['Product_ID']) ?></td>
                    <td><?= htmlspecialchars($trade['Trade_Name']) ?></td>
                    <td><?= htmlspecialchars($trade['Trade_Description']) ?></td>
                    <td><?= htmlspecialchars($trade['Trade_Offer']) ?></td>
                    <td>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                            <select name="update">
                                <?php foreach($trade_statuses as $opt) :?>    
                                    <option value="<?= htmlspecialchars($opt) ?>" <?= $trade['Trade_Status'] == $opt ? 'selected' : "" ?>>
                                        <?= htmlspecialchars($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="trade_id" value="<?= htmlspecialchars($trade['Trade_ID']) ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($trade['Trade_Status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <script>
        function showTab(status) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active-tab');
            });
            document.getElementById(status).classList.add('active-tab');
        }
        showTab('Pending');
    </script>
</body>
</html>
