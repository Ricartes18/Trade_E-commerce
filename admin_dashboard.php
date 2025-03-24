<?php
// Start session (if needed)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <title>Admin Dashboard</title>
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
        <div class="welcome">
            <h1>Welcome Admin!</h1>
            <p>What do you want to do today?</p>
        </div>
        <div class="options">
            <a href="#" class="button view-modify">View and Modify Products</a>
            <a href="#" class="button pending-orders">Pending Orders</a>
            <a href="#" class="button pending-trade">Pending Trade</a>
        </div>
    </div>
</body>
</html>
