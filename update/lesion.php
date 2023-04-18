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
$type = htmlentities($_POST['type']);
$evaluation = htmlentities($_POST['evaluation']);
$number = htmlentities($_POST['number']);
$location = htmlentities($_POST['location']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));
$oldDate = htmlentities($oldData['date']);
$oldType = htmlentities($oldData['type']);
$oldEvaluation = htmlentities($oldData['evaluation']);
$oldNumber = htmlentities($oldData['number']);
$oldLocation = htmlentities($oldData['location']);
$oldComment = htmlentities($oldData['comment']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$sql = "UPDATE `LesionsNF1`
        SET
            `date` = ?,
            `type` = ?,
            `evaluation` = ?,
            `number` = ?,
            `location` = ?,
            `comment` = ?
        WHERE `id` = UNHEX(?)
        AND `date` = ?
        AND `type` = ?
        AND `evaluation` = ?
        AND `number` = ?
        AND `location` = ?
        AND `comment` = ?";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $date);
$stmt->bindParam(2, $type);
$stmt->bindParam(3, $evaluation);
$stmt->bindParam(4, $number);
$stmt->bindParam(5, $location);
$stmt->bindParam(6, $comment);
$stmt->bindParam(7, $enc_id, PDO::PARAM_STR);
$stmt->bindParam(8, $oldDate);
$stmt->bindParam(9, $oldType);
$stmt->bindParam(10, $oldEvaluation);
$stmt->bindParam(11, $oldNumber);
$stmt->bindParam(12, $oldLocation);
$stmt->bindParam(13, $oldComment);
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
    INSERT INTO `LesionsNF1_tracking`(
        `id`,
        `date`,
        `type`,
        `evaluation`,
        `number`,
        `location`,
        `comment`,
        `tracking`,
        `event`
    )
    VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?)
";
$stmt3 = $clinical_data_pdo->prepare($sql3);
$stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
$stmt3->bindParam(2, $date);
$stmt3->bindParam(3, $type);
$stmt3->bindParam(4, $evaluation);
$stmt3->bindParam(5, $number);
$stmt3->bindParam(6, $location);
$stmt3->bindParam(7, $comment);
$stmt3->bindParam(8, $tracking);
$stmt3->bindParam(9, $event);

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
