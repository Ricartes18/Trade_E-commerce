<?php 
    session_start();
    include 'connection.php';
    if($_SESSION['role'] != 'admin'){
        header("Location: ../index.php");
        exit();
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
    <h1>DASHBOARD</h1>    
</body>
</html>