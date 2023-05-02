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

$id = $_POST['id'];
$date = $_POST['date'];
$test = $_POST['test'];
$result = $_POST['result'];
$comment = str_replace("'", "\'", $_POST['comment']);
$oldDate = $oldData['date'];
$oldTest = $oldData['test'];
$oldResult = $oldData['result'];
$oldComment = $oldData['comment'];

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
    $sql = "UPDATE `Tumor`
            SET
                `date` = ?,
                `test` = ?,
                `result` = ?,
                `comment` = ?
            WHERE `id` = UNHEX(?)
            AND `date` = ?
            AND `test` = ?
            AND `result` = ?
            AND `comment` = ?";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $date);
    $stmt->bindParam(2, $test);
    $stmt->bindParam(3, $result);
    $stmt->bindParam(4, $comment);
    $stmt->bindParam(5, $enc_id, PDO::PARAM_STR);
    $stmt->bindParam(6, $oldDate);
    $stmt->bindParam(7, $oldTest);
    $stmt->bindParam(8, $oldResult);
    $stmt->bindParam(9, $oldComment);
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
        INSERT INTO `Tumor_tracking`(
            `id`,
            `date`,
            `test`,
            `result`,
            `comment`,
            `tracking`,
            `event`
        )
        VALUES (UNHEX(?),?,?,?,?,?,?)
    ";
    $stmt3 = $clinical_data_pdo->prepare($sql3);
    $stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt3->bindParam(2, $date);
    $stmt3->bindParam(3, $test);
    $stmt3->bindParam(4, $result);
    $stmt3->bindParam(5, $comment);
    $stmt3->bindParam(6, $tracking);
    $stmt3->bindParam(7, $event);

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
}else {
    echo "You exceeded the amount of updates you can do in 24 hours!";
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
