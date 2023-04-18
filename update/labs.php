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
$location = htmlentities($_POST['location']);
$height = htmlentities($_POST['height']);
$weight = htmlentities($_POST['weight']);
$diastolic = htmlentities($_POST['diastolic']);
$systolic = htmlentities($_POST['systolic']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));
$oldDate = htmlentities($oldData['date']);
$oldLocation = htmlentities($oldData['location']);
$oldHeight = htmlentities($oldData['height']);
$oldWeight = htmlentities($oldData['weight']);
$oldDiastolic = htmlentities($oldData['diastolic']);
$oldSystolic = htmlentities($oldData['systolic']);
$oldComment = htmlentities($oldData['comment']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$sql = "UPDATE `Lab`
        SET
            `date` = ?,
            `location` = ?,
            `height` = ?,
            `weight` = ?,
            `diastolic` = ?,
            `systolic` = ?,
            `comment` = ?
        WHERE `id` = UNHEX(?)
        AND `date` = ?
        AND `location` = ?
        AND `height` = ?
        AND `weight` = ?
        AND `diastolic` = ?
        AND `systolic` = ?
        AND `comment` = ?";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $date);
$stmt->bindParam(2, $location);
$stmt->bindParam(3, $height);
$stmt->bindParam(4, $weight);
$stmt->bindParam(5, $diastolic);
$stmt->bindParam(6, $systolic);
$stmt->bindParam(7, $comment);
$stmt->bindParam(8, $enc_id, PDO::PARAM_STR);
$stmt->bindParam(9, $oldDate);
$stmt->bindParam(10, $oldLocation);
$stmt->bindParam(11, $oldHeight);
$stmt->bindParam(12, $oldWeight);
$stmt->bindParam(13, $oldDiastolic);
$stmt->bindParam(14, $oldSystolic);
$stmt->bindParam(15, $oldComment);
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
    INSERT INTO `Lab_tracking`(
        `id`,
        `date`,
        `location`,
        `height`,
        `weight`,
        `diastolic`,
        `systolic`,
        `comment`,
        `tracking`,
        `event`
    )
    VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?)
";
$stmt3 = $clinical_data_pdo->prepare($sql3);
$stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
$stmt3->bindParam(2, $date);
$stmt3->bindParam(3, $location);
$stmt3->bindParam(4, $height);
$stmt3->bindParam(5, $weight);
$stmt3->bindParam(6, $diastolic);
$stmt3->bindParam(7, $systolic);
$stmt3->bindParam(8, $comment);
$stmt3->bindParam(9, $tracking);
$stmt3->bindParam(10, $event);

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
