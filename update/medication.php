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
$medication = htmlentities($_POST['medication']);
$start = htmlentities($_POST['start']);
$stop = htmlentities($_POST['stop']);
$reason = htmlentities($_POST['reason']);
$intent = htmlentities($_POST['intent']);
$comment = str_replace("'", "\'", htmlentities($_POST['comment']));
$oldMedication = htmlentities($oldData['medication']);
$oldStart = htmlentities($oldData['start']);
$oldStop = htmlentities($oldData['stop']);
$oldReason = htmlentities($oldData['reason']);
$oldIntent = htmlentities($oldData['intent']);
$oldComment = htmlentities($oldData['comment']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));


$sql = "UPDATE `Medication`
        SET
            `medication` = ?,
            `start` = ?,
            `stop` = ?,
            `reason` = ?,
            `intent` = ?,
            `comment` = ?
        WHERE `id` = UNHEX(?)
        AND `medication` = ?
        AND `start` = ?
        AND `stop` = ?
        AND `reason` = ?
        AND `intent` = ?
        AND `comment` = ?";
$stmt = $clinical_data_pdo->prepare($sql);
$stmt->bindParam(1, $medication);
$stmt->bindParam(2, $start);
$stmt->bindParam(3, $stop);
$stmt->bindParam(4, $reason);
$stmt->bindParam(5, $intent);
$stmt->bindParam(6, $comment);
$stmt->bindParam(7, $enc_id, PDO::PARAM_STR);
$stmt->bindParam(8, $oldMedication);
$stmt->bindParam(9, $oldStart);
$stmt->bindParam(10, $oldStop);
$stmt->bindParam(11, $oldReason);
$stmt->bindParam(12, $oldIntent);
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
    VALUES (UNHEX(?),?,?,?,?,?,?,?)
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
