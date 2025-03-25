<?php 
    /*
    session_start();
    include 'connection.php';
    if($_SESSION['role'] != 'admin'){
        header("Location: ../index.php");
        exit();
    }
    */
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
    <title>Pending Trades</title>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="../img/PoCaSwap Logo.png" alt="Logo">
                <div class="title">
                    <span>PoCaSwap</span>
                </div>
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
            <div class="trade-card">
                <img src="../img/yunjinpc.jpg" alt="Huh Yunjin UNFORGIVEN Flower PC" class="trade-img">
                <div class="trade-details">
                    <p>Huh Yunjin - UNFORGIVEN Day Version Flowers</p>
                    <span class="trade-to">to</span>
                    <div class="trade-placeholder"></div>
                </div>
                <div class="trade-actions">
                    <button class="accept">ACCEPT</button>
                    <button class="reject">REJECT</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
