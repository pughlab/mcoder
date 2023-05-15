<?php

include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

const DATETIME_FORMAT = 'Y-m-d H:i:s';

// Ip address of the user
$ip = $_POST['ip'];
$datesystem = $_POST['datesystem'];
$email = $_POST['email'];
$username = $_POST['username'];
$roles = $_POST['roles'];
$tracking = $_POST['tracking'];
$event = "Deletion";

$id = htmlentities($_POST['id']);
$medication = htmlentities($_POST['medication']);
$start = htmlentities($_POST['start']);
$stop = htmlentities($_POST['stop']);
$reason = htmlentities($_POST['reason']);
$intent = htmlentities($_POST['intent']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
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
    $sql = "DELETE FROM `Medication`
        WHERE
            `id` = UNHEX(?)
            AND `medication` = ?
            AND `start` = ?
            AND `stop` = ?
            AND `reason` = ?
            AND `intent` = ?
            AND `comment` = ?";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $medication);
    $stmt->bindParam(3, $start);
    $stmt->bindParam(4, $stop);
    $stmt->bindParam(5, $reason);
    $stmt->bindParam(6, $intent);
    $stmt->bindParam(7, $comment);
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
        INSERT INTO `Medication_tracking`(
            `id`,
            `medication`,
            `start`,
            `stop`,
            `reason`,
            `intent`,
            `comment`,
            `tracking`,
            `event`
        )
        VALUES (UNHEX(?),?,?,?,?,?,?,?,?)
    ";
    $stmt3 = $clinical_data_pdo->prepare($sql3);
    $stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt3->bindParam(2, $medication);
    $stmt3->bindParam(3, $start);
    $stmt3->bindParam(4, $stop);
    $stmt3->bindParam(5, $reason);
    $stmt3->bindParam(6, $intent);
    $stmt3->bindParam(7, $comment);
    $stmt3->bindParam(8, $tracking);
    $stmt3->bindParam(9, $event);

    $clinical_data_pdo->beginTransaction();
    $mainResult = null;
    $trackingResult = null;
    $auditResult = null;

    try {
        $mainResult = $stmt->execute();
        $trackingResult = $stmt2->execute();
        $auditResult = $stmt3->execute();
        $clinical_data_pdo->commit();

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
        }
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
        echo "There was a problem while deleting the data. ";
        echo "Please contact the admins at mcoder@uhn.ca. ";
        // echo "Your reference: " . $tracking . ":" . $error;
    }
} elseif (!$canDelete) {
    echo "You cannot delete data until 24 hours have passed since the last time you deleted data!";
} else {
    echo "You are not authorized to do this operation!";
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
