<?php
// database connection
    try{
        $con = new mysqli("localhost", "root", "", "User");
        $conp = new mysqli("localhost", "root", "", "merch_exchange");
    } catch(mysqli_sql_exception) {
        echo "Could not connect to database";
    }
?>
