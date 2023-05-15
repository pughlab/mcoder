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
$event = "Addition";

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);
$type = htmlentities($_POST['type']);
$cellularity = htmlentities($_POST['cellularity']);
$collection = htmlentities($_POST['collection']);
$storage = htmlentities($_POST['storage']);
$bankingid = htmlentities($_POST['bankingid']);
$paired = htmlentities($_POST['paired']);
$imaging = htmlentities($_POST['imaging']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

$stmt = $clinical_data_pdo->prepare("SELECT COUNT(*) FROM Patient WHERE id = UNHEX(?)");
$stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
$stmt->execute();
$patientExists = $stmt->fetchColumn() != 0;

if ($patientExists) {
    $sql = "
        INSERT INTO `Biospecimens` (
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
            `tracking`
        )
        VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $date);
    $stmt->bindParam(3, $type);
    $stmt->bindParam(4, $cellularity);
    $stmt->bindParam(5, $collection);
    $stmt->bindParam(6, $storage);
    $stmt->bindParam(7, $bankingid);
    $stmt->bindParam(8, $paired);
    $stmt->bindParam(9, $imaging);
    $stmt->bindParam(10, $comment);
    $stmt->bindParam(11, $tracking);

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

    $clinical_data_pdo->beginTransaction();
    $mainResult = null;
    $trackingResult = null;
    $auditResult = null;

    try {
        $mainResult = $stmt->execute();
        $trackingResult = $stmt2->execute();
        $auditResult = $stmt3->execute();
        $clinical_data_pdo->commit();
        echo "Success";
    } catch (PDOException $e) {
        $clinical_data_pdo->rollBack();
        $error = null;
        if (!$mainResult) {
            $error = $stmt->errorCode();
        } elseif (!$trackingResult) {
            $error = $stmt2->errorCode();
        } else {
            $error = $stmt3->errorCode();
        }
        echo "There was a problem while saving the data. ";
        echo "Please contact the admins at mcoder@uhn.ca. ";
        // echo "Your reference: " . $tracking . ":" . $error;
    }
} else {
    echo "This patient does not exist!";
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
