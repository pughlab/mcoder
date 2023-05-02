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
$oldData =  $_POST['olddata'];
$event = "Update";

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);
$type = htmlentities($_POST['type']);
$histology = htmlentities($_POST['histology']);
$status = htmlentities($_POST['status']);
$location = htmlentities($_POST['location']);
$side = htmlentities($_POST['side']);
$oncotree = htmlentities($_POST['oncotree']);
$clinicalsg = htmlentities($_POST['clinicalsg']);
$clinicalss = htmlentities($_POST['clinicalss']);
$pathologicsg = htmlentities($_POST['pathologicsg']);
$pathologicss = htmlentities($_POST['pathologicss']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$olddate = htmlentities($oldData['date']);
$oldtype = htmlentities($oldData['type']);
$oldhistology = htmlentities($oldData['histology']);
$oldstatus = htmlentities($oldData['status']);
$oldcode = htmlentities($oldData['location']);
$oldside = htmlentities($oldData['side']);
$oldoncotree = htmlentities($oldData['oncotree']);
$oldclinicalsg = htmlentities($oldData['clinicalsg']);
$oldclinicalss = htmlentities($oldData['clinicalss']);
$oldpathologicsg = htmlentities($oldData['pathologicsg']);
$oldpathologicss = htmlentities($oldData['pathologicss']);
$oldcomment = htmlentities($oldData['comment']);

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
    $sql = "
        UPDATE `Diseases`
            SET
                `date` = ?,
                `type` = ?,
                `histology` = ?,
                `status` = ?,
                `code` = ?,
                `side` = ?,
                `oncotree` = ?,
                `clinicalsg` = ?,
                `clinicalss` = ?,
                `pathologicsg` = ?,
                `pathologicss` = ?,
                `comments` = ?
        WHERE `id` = UNHEX(?)
        AND `date` = ?
        AND `type` = ?
        AND `histology` = ?
        AND `status` = ?
        AND `code` = ?
        AND `side` = ?
        AND `oncotree` = ?
        AND `clinicalsg` = ?
        AND `clinicalss` = ?
        AND `pathologicsg` = ?
        AND `pathologicss` = ?
        AND `comments` = ?
    ";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $date);
    $stmt->bindParam(2, $type);
    $stmt->bindParam(3, $histology);
    $stmt->bindParam(4, $status);
    $stmt->bindParam(5, $location);
    $stmt->bindParam(6, $side);
    $stmt->bindParam(7, $oncotree);
    $stmt->bindParam(8, $clinicalsg);
    $stmt->bindParam(9, $clinicalss);
    $stmt->bindParam(10, $pathologicsg);
    $stmt->bindParam(11, $pathologicss);
    $stmt->bindParam(12, $comment);
    $stmt->bindParam(13, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(14, $olddate);
    $stmt->bindParam(15, $oldtype);
    $stmt->bindParam(16, $oldhistology);
    $stmt->bindParam(17, $oldstatus);
    $stmt->bindParam(18, $oldcode);
    $stmt->bindParam(19, $oldside);
    $stmt->bindParam(20, $oldoncotree);
    $stmt->bindParam(21, $oldclinicalsg);
    $stmt->bindParam(22, $oldclinicalss);
    $stmt->bindParam(23, $oldpathologicsg);
    $stmt->bindParam(24, $oldpathologicss);
    $stmt->bindParam(25, $oldcomment);

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
        INSERT INTO `Diseases_tracking`(
            `id`,
            `date`,
            `type`,
            `histology`,
            `status`,
            `code`,
            `side`,
            `oncotree`,
            `clinicalsg`,
            `clinicalss`,
            `pathologicsg`,
            `pathologicss`,
            `comments`,
            `tracking`
        )
        VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt3 = $clinical_data_pdo->prepare($sql3);
    $stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt3->bindParam(2, $date);
    $stmt3->bindParam(3, $type);
    $stmt3->bindParam(4, $histology);
    $stmt3->bindParam(5, $status);
    $stmt3->bindParam(6, $location);
    $stmt3->bindParam(7, $side);
    $stmt3->bindParam(8, $oncotree);
    $stmt3->bindParam(9, $clinicalsg);
    $stmt3->bindParam(10, $clinicalss);
    $stmt3->bindParam(11, $pathologicsg);
    $stmt3->bindParam(12, $pathologicss);
    $stmt3->bindParam(13, $comment);
    $stmt3->bindParam(14, $tracking);
    $stmt3->bindParam(15, $event);

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
