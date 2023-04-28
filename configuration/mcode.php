<?php
//parameters: host, username, password, db
$mysqli = new mysqli("", "", "", "");
//parameters: host, username, password, db
$connect = mysqli_connect("", "", "", "");

try {
    $mcode_db_pdo = new PDO("mysql:host=;dbname=", "", "");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
