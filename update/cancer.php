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
$oldData =  $_POST['olddata'];

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

$mainResult = $stmt->execute();
$trackingResult = $stmt2->execute();

if ($mainResult && $trackingResult) {
    echo "Success";
} else {
    $error = !$mainResult ? $stmt->errorCode() : $stmt2->errorCode();
    echo "There was a problem while saving the data. ";
    echo "Please contact the admin of the site - Nadia Znassi. Your reference: " . $tracking . ":" . $error;
}


mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
