<?php

$servername = "";
$username = "";
$password = "";
$db="";
$conn = mysqli_connect($servername, $username, $password, $db);

try {
    $clinical_data_pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
