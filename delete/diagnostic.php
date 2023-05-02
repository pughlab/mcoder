<?php

include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

const DATETIME_FORMAT = 'Y-m-d H:i:s';

$ip = $_POST['ip'];
$datesystem = $_POST['datesystem'];
$email = $_POST['email'];
$username = $_POST['username'];
$roles = $_POST['roles'];
$tracking = $_POST['tracking'];
$event = "Deletion";

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);
$diagnosis = htmlentities($_POST['diagnosis']);
$mode = htmlentities($_POST['mode']);
$criteria = htmlentities($_POST['criteria']);
$severity = htmlentities($_POST['severity']);
$visibility = htmlentities($_POST['visibility']);
$age = htmlentities($_POST['age']);
$head = htmlentities($_POST['head']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));

$encryption_key = hex2bin($key);

$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));

$canDelete = false;
$sql = "SELECT * FROM `deletion_tracking` WHERE username = ?";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $username);
$checkCanDelete = $stmt->execute();

if ($checkCanDelete && $stmt->rowCount() > 0) {
    $now = date(DATETIME_FORMAT);
    $row = $stmt->fetch();
    $lastDeletion = strtotime($row['last_deletion']);

    $difference = abs(strtotime($now) - $lastDeletion);
    $canDelete = $difference > 86400; // we can delete only if the difference is greater than 24 hours in seconds
} elseif ($stmt->rowCount() == 0) {
    $canDelete = true;
}

if ($hasAdminRole && $canDelete) {
    $sql = "DELETE FROM `DiagnosisNF1`
        WHERE
            `id` = UNHEX(?)
            AND `date` = ?
            AND `diagnosis` = ?
            AND `mode` = ?
            AND `criteria` = ?
            AND `severity` = ?
            AND `visibility` = ?
            AND `age` = ?
            AND `circumference` = ?
            AND `comment` = ?";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $date);
    $stmt->bindParam(3, $diagnosis);
    $stmt->bindParam(4, $mode);
    $stmt->bindParam(5, $criteria);
    $stmt->bindParam(6, $severity);
    $stmt->bindParam(7, $visibility);
    $stmt->bindParam(8, $age);
    $stmt->bindParam(9, $head);
    $stmt->bindParam(10, $comment);
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
        INSERT INTO `DiagnosisNF1_tracking`(
            `id`,
            `date`,
            `diagnosis`,
            `mode`,
            `criteria`,
            `severity`,
            `visibility`,
            `age`,
            `circumference`,
            `comment`,
            `tracking`,
            `event`
        )
        VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt3 = $clinical_data_pdo->prepare($sql3);
    $stmt3->bindParam(1, $enc_id);
    $stmt3->bindParam(2, $date);
    $stmt3->bindParam(3, $diagnosis);
    $stmt3->bindParam(4, $mode);
    $stmt3->bindParam(5, $criteria);
    $stmt3->bindParam(6, $severity);
    $stmt3->bindParam(7, $visibility);
    $stmt3->bindParam(8, $age);
    $stmt3->bindParam(9, $head);
    $stmt3->bindParam(10, $comment);
    $stmt3->bindParam(11, $tracking);
    $stmt3->bindParam(12, $event);

    $mainResult = $stmt->execute();
    $trackingResult = $stmt2->execute();
    $auditResult = $stmt3->execute();

    if ($mainResult && $trackingResult && $auditResult) {
        $now = date(DATETIME_FORMAT);
        $sql = "
            INSERT INTO `deletion_tracking` (
                `username`,
                `last_deletion`
            )
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE `last_deletion`=?
        ";
        $stmt = $clinical_data_pdo->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $now);
        $stmt->bindParam(3, $now);
        $stmt->execute();
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
} elseif (!$canDelete) {
    echo "You cannot delete data until 24 hours have passed since the last time you deleted data!";
} else {
    echo "You are not authorized to do this operation!";
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
