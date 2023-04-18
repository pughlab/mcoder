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
$oldData = $_POST['olddata'];
$event = "Update";

$id = htmlentities($_POST['id']);
$date = htmlentities($_POST['date']);
$test = htmlentities($_POST['test']);
$gene = htmlentities($_POST['gene']);
$cdna = htmlentities($_POST['cdna']);
$protein = htmlentities($_POST['protein']);
$mutationid = htmlentities($_POST['mutationid']);
$mutationhgvs = htmlentities($_POST['mutationhgvs']);
$interpretation = htmlentities($_POST['interpretation']);
$source = htmlentities($_POST['source']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));
$oldDate = htmlentities($oldData['date']);
$oldTest = htmlentities($oldData['test']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$sql = "UPDATE `Variant`
    SET
        `date` = ?,
        `test` = ?,
        `gene` = ?,
        `cdna` = ?,
        `protein` = ?,
        `variantid` = ?,
        `varianthgvs` = ?,
        `interpretation` = ?,
        `source` = ?,
        `comment` = ?
    WHERE `id` = UNHEX(?)
    AND `date` = ?
    AND `test` = ?";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $date);
$stmt->bindParam(2, $test);
$stmt->bindParam(3, $gene);
$stmt->bindParam(4, $cdna);
$stmt->bindParam(5, $protein);
$stmt->bindParam(6, $mutationid);
$stmt->bindParam(7, $mutationhgvs);
$stmt->bindParam(8, $interpretation);
$stmt->bindParam(9, $source);
$stmt->bindParam(10, $comment);
$stmt->bindParam(11, $enc_id, PDO::PARAM_STR);
$stmt->bindParam(12, $oldDate);
$stmt->bindParam(13, $oldTest);
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
    INSERT INTO `Variant_tracking`(
        `id`,
        `date`,
        `test`,
        `gene`,
        `cdna`,
        `protein`,
        `variantid`,
        `varianthgvs`,
        `interpretation`,
        `source`,
        `comment`,
        `tracking`,
        `event`
    )
    VALUES (UNHEX(?),?,?,?,?,?,?,?,?,?,?,?,?)
";
$stmt3 = $clinical_data_pdo->prepare($sql3);
$stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
$stmt3->bindParam(2, $date);
$stmt3->bindParam(3, $test);
$stmt3->bindParam(4, $gene);
$stmt3->bindParam(5, $cdna);
$stmt3->bindParam(6, $protein);
$stmt3->bindParam(7, $mutationid);
$stmt3->bindParam(8, $mutationhgvs);
$stmt3->bindParam(9, $interpretation);
$stmt3->bindParam(10, $source);
$stmt3->bindParam(11, $comment);
$stmt3->bindParam(12, $tracking);
$stmt3->bindParam(13, $event);

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
    echo "There was a problem while saving the data. ";
    echo "Please contact the admin of the site - Nadia Znassi. Your reference: " . $tracking . ":" . $error;
}


mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
