<?php

include_once('../configuration/db.php');
include_once('../configuration/mcode.php');
include_once('../configuration/key.php');

// Ip address of the user
$ip = $_POST['ip'];
$datesystem = $_POST['datesystem'];
$email = $_POST['email'];
$username = $_POST['username'];
$roles = $_POST['roles'];
$tracking = $_POST['tracking'];
$event = "Deletion";

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);
$type = htmlentities($_POST['type']);
$storage = htmlentities($_POST['storage']);
$bankingid = htmlentities($_POST['bankingid']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));

if ($hasAdminRole) {
    $sql = "DELETE FROM `Biospecimens`
    WHERE
        `id` = UNHEX(?)
        AND `date` = ?
        AND `type` = ?
        AND `storage` = ?
        AND `bankingid` = ?";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $date);
    $stmt->bindParam(3, $type);
    $stmt->bindParam(4, $storage);
    $stmt->bindParam(5, $bankingid);
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

    $sql3 = "
        INSERT INTO `Biospecimens_tracking` (
            `id`,
            `date`,
            `type`,
            `cellularity`,
            `collection`,
            `storage`,
            `bankingid`,
            `paired`,
            `imaging`,
            `comment`,
            `tracking`,
            `event`
        )
        VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt3 = $clinical_data_pdo->prepare($sql3);
    $stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt3->bindParam(2, $date);
    $stmt3->bindParam(3, $type);
    $stmt3->bindParam(4, $cellularity);
    $stmt3->bindParam(5, $collection);
    $stmt3->bindParam(6, $storage);
    $stmt3->bindParam(7, $bankingid);
    $stmt3->bindParam(8, $paired);
    $stmt3->bindParam(9, $imaging);
    $stmt3->bindParam(10, $comment);
    $stmt3->bindParam(11, $tracking);
    $stmt3->bindParam(12, $event);

    $mainResult = $stmt->execute();
    $trackingResult = $stmt2->execute();
    $auditResult = $stmt3->execute();

    if ($mainResult && $trackingResult && $auditResult) {
        echo "Success";
    } else {
        $error = null;
        if (!$mainResult) {
            $error = $stmt->errorCode();
        } elseif (!$trackingResult) {
            $error = $stmt2->errorCode();
        } else {
            $error = $stmt3->errorCode();
        }
        echo "There was a problem while deleting the data. ";
        echo "Please contact the admin of the site - Nadia Znassi. Your reference: " . $tracking . ":" . $error;
    }
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
