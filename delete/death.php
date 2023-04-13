<?php

include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

// Ip address of the user
$ip = $_POST['ip'];
$datesystem = $_POST['datesystem'];
$email = $_POST['email'];
$username = $_POST['username'];
$roles = rtrim(trim($_POST["roles"]), ",");
$tracking = $_POST['tracking'];

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);

$encryption_key = hex2bin($key);

$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));

if ($hasAdminRole) {
    $sql = "DELETE FROM `Death`
        WHERE `id` = UNHEX(?)
        AND `date` = ?";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $date);
    $sql2 = "
        INSERT INTO `tracking`(
            `trackingid`,
            `username`,
            `email`,
            `roles`,
            `ip`,
            `date`
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    $stmt2 = $clinical_data_pdo->prepare($sql2);
    $stmt2->bindParam(1, $tracking);
    $stmt2->bindParam(2, $username);
    $stmt2->bindParam(3, $email);
    $stmt2->bindParam(4, $roles);
    $stmt2->bindParam(5, $ip);
    $stmt2->bindParam(6, $datesystem);

    $mainResult = $stmt->execute();
    $trackingResult = $stmt2->execute();

    if ($mainResult && $trackingResult) {
        echo "Success";
    } else {
        $error = !$mainResult ? $stmt->errorCode() : $stmt2->errorCode();
        echo "There was a problem while deleting the data. ";
        echo "Please contact the admin of the site - Nadia Znassi. Your reference: " . $tracking . ":" . $error;
    }
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
