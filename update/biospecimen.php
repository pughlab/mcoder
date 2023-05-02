<?php
include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

const DATETIME_FORMAT = 'Y-m-d H:i:s';
$max_update_rows = 20;

// Ip address of the user
$ip = $_POST['ip'];
$datesystem = $_POST['datesystem'];
$email = $_POST['email'];
$username = $_POST['username'];
$roles = $_POST['roles'];
$tracking = $_POST['tracking'];
$oldData = $_POST['olddata'];
$event = "Update";

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

$oldDate = htmlentities($oldData["collectionDate"]);
$oldType = htmlentities($oldData["specimenType"]);
$oldStorage = htmlentities($oldData["storage"]);
$oldBankingID = htmlentities($oldData["bankingID"]);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

$now = date(DATETIME_FORMAT);
$twentyFourHoursAgo = date(DATETIME_FORMAT, strtotime('-24 hours'));
$sql = "
    SELECT *
    FROM `update_tracking`
    WHERE username = ?
    AND update_time BETWEEN ? AND ?
    LIMIT ?
";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $username);
$stmt->bindParam(2, $twentyFourHoursAgo);
$stmt->bindParam(3, $now);
$stmt->bindParam(4, $max_update_rows, PDO::PARAM_INT);
$canUpdate = $stmt->execute() && $stmt->rowCount() < $max_update_rows;

if ($canUpdate) {
    $sql = "UPDATE `Biospecimens`
        SET
            `date` = ?,
            `type` = ?,
            `cellularity` = ?,
            `collection` = ?,
            `storage` = ?,
            `bankingid` = ?,
            `paired` = ?,
            `imaging` = ?,
            `comment` = ?
        WHERE
            `id` = UNHEX(?)
            AND `date` = ?
            AND `type` = ?
            AND `storage` = ?
            AND `bankingid` = ?
    ";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $date);
    $stmt->bindParam(2, $type);
    $stmt->bindParam(3, $cellularity);
    $stmt->bindParam(4, $collection);
    $stmt->bindParam(5, $storage);
    $stmt->bindParam(6, $bankingid);
    $stmt->bindParam(7, $paired);
    $stmt->bindParam(8, $imaging);
    $stmt->bindParam(9, $comment);
    $stmt->bindParam(10, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(11, $oldDate);
    $stmt->bindParam(12, $oldType);
    $stmt->bindParam(13, $oldStorage);
    $stmt->bindParam(14, $oldBankingID);

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
        $sql = "
            INSERT INTO `update_tracking` (
                `username`,
                `update_time`
            )
            VALUES (?, ?)
        ";
        $stmt = $clinical_data_pdo->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $now);
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
        echo "There was a problem while saving the data. ";
        echo "Please contact the admin of the site - Nadia Znassi. Your reference: " . $tracking . ":" . $error;
    }
} else {
    echo "You exceeded the amount of updates you can do in 24 hours!";
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
