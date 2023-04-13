<?php

include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

// Ip address of the user
$ip = $_POST['ip'];
$datesystem = $_POST['datesystem'];
$email = $_POST['email'];
$username = $_POST['username'];
$roles = $_POST['roles'];
$tracking = $_POST['tracking'];

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);
$type = htmlentities($_POST['type']);
$count = htmlentities($_POST['count']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$sql = "
    INSERT INTO `CBC`(
        `id`,
        `date`,
        `type`,
        `count`,
        `comment`,
        `tracking`
    )
    VALUES (UNHEX(?), ?, ?, ?, ?, ?)
";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
$stmt->bindParam(2, $date);
$stmt->bindParam(3, $type);
$stmt->bindParam(4, $count);
$stmt->bindParam(5, $comment);
$stmt->bindParam(6, $tracking);

$sql2 = "
    INSERT INTO `tracking`(
        `trackingid`,
        `username`,
        `email`,
        `roles`,
        `ip`,
        `date`
    )
    VALUES (?,?,?,?,?,?)
";
$stmt2 = $clinical_data_pdo->prepare($sql2);
$stmt2->bindParam(1, $tracking, PDO::PARAM_STR);
$stmt2->bindParam(2, $username);
$stmt2->bindParam(3, $email);
$stmt2->bindParam(4, $roles);
$stmt2->bindParam(5, $ip);
$stmt2->bindParam(6, $datesystem);

$mainResult = $stmt->execute();
$trackingResult = $stmt2->execute();

//if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
if ($mainResult && $trackingResult) {
    echo "Success";
} else {
    $error = !$mainResult ? $stmt->errorCode() : $stmt2->errorCode();
    echo "There was a problem while saving the data. ";
    echo "Please contact the admin of the site - Nadia Znassi. Your reference: " . $tracking . ":" . $error;
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
