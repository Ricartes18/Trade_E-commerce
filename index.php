<?php 
    include 'admin/connection.php';
    session_start();
    
    $recently_viewed = isset($_COOKIE['recently_viewed']) ? json_decode($_COOKIE['recently_viewed'], true) : [];

    if (!empty($recently_viewed)) {
        $placeholders = implode(',', array_fill(0, count($recently_viewed), '?'));

        $stmt = $conp->prepare("SELECT * FROM products WHERE Product_ID IN ($placeholders) ORDER BY FIELD(Product_ID, " . implode(',', $recently_viewed) . ")");
        $stmt->bind_param(str_repeat('i', count($recently_viewed)), ...$recently_viewed);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Recently Viewed Products</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<a href='product.php?id={$row['Product_ID']}'>";
            echo "<img src='products/{$row['Product_Image']}' width='100'>";
            echo "<p>{$row['Photocard_Title']}</p>";
            echo "</a>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- <h1>HELLO <?= $_SESSION['role'] . " " . $_SESSION['username'];?></h1> -->
    <form action="index.php" method="post">
        <button type="submit" name="logout">Log out</button>
    </form>
</body>
</html>

<!-- LOGOUT -->
<?php
    if(isset($_POST["logout"])){
        session_destroy();
        header('Location: login.php');
    }
?>

<!-- PALAGAY TO SA MAY USERNAME ETO KAPALIT -->
<?php 
    if($_SESSION['role'] === "admin"){
        echo "<a href='admin/dashboard.php'>Dashboard</a>";
    } elseif(isset($_SESSION['role']) === "user"){
        echo (isset($_SESSION)) ? $_SESSION['username'] : 'Hello';
    }
    else {
        echo "<a href='login.php'>Login</a> | <a href='sign_up.php'>Sign Up</a>";
    }
?>