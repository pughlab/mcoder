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
$oldDate = htmlentities($oldData['date']);
$oldDiagnosis = htmlentities($oldData['diagnosis']);
$oldMode = htmlentities($oldData['mode']);
$oldCriteria = htmlentities($oldData['criteria']);
$oldSeverity = htmlentities($oldData['severity']);
$oldVisibility = htmlentities($oldData['visibility']);
$oldAge = htmlentities($oldData['age']);
$oldHead = htmlentities($oldData['head']);
$oldComment = htmlentities($oldData['comment']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$sql = "UPDATE `DiagnosisNF1`
        SET
            `date` = ?,
            `diagnosis` = ?,
            `mode` = ?,
            `criteria` = ?,
            `severity` = ?,
            `visibility` = ?,
            `age` = ?,
            `circumference` = ?,
            `comment` = ?
    WHERE `id` = UNHEX(?)
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
$stmt->bindParam(1, $date);
$stmt->bindParam(2, $diagnosis);
$stmt->bindParam(3, $mode);
$stmt->bindParam(4, $criteria);
$stmt->bindParam(5, $severity);
$stmt->bindParam(6, $visibility);
$stmt->bindParam(7, $age);
$stmt->bindParam(8, $head);
$stmt->bindParam(9, $comment);
$stmt->bindParam(10, $enc_id, PDO::PARAM_STR);
$stmt->bindParam(11, $oldDate);
$stmt->bindParam(12, $oldDiagnosis);
$stmt->bindParam(13, $oldMode);
$stmt->bindParam(14, $oldCriteria);
$stmt->bindParam(15, $oldSeverity);
$stmt->bindParam(16, $oldVisibility);
$stmt->bindParam(17, $oldAge);
$stmt->bindParam(18, $oldHead);
$stmt->bindParam(19, $oldComment);
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
