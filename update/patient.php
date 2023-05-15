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
$event = "Update";


$id = htmlentities($_POST['id']);
$birth = htmlentities($_POST['birth']);
$gender = htmlentities($_POST['gender']);
$race = htmlentities($_POST['race']);
$zip = htmlentities($_POST['zip']);
$institution = htmlentities($_POST['institution']);
$study = htmlentities($_POST['study']);
$family = htmlentities($_POST['family']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// ID encrypted
$enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

$enc_birth = bin2hex(openssl_encrypt($birth, $cipher, $encryption_key, 0, $iv));
$enc_gender = bin2hex(openssl_encrypt($gender, $cipher, $encryption_key, 0, $iv));
$enc_race = bin2hex(openssl_encrypt($race, $cipher, $encryption_key, 0, $iv));
$enc_zip = bin2hex(openssl_encrypt($zip, $cipher, $encryption_key, 0, $iv));
$enc_institution = bin2hex(openssl_encrypt($institution, $cipher, $encryption_key, 0, $iv));
$enc_family = bin2hex(openssl_encrypt($family, $cipher, $encryption_key, 0, $iv));

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
    $sql = "UPDATE `Patient`
        SET
            `birth` = UNHEX(?),
            `gender` = UNHEX(?),
            `race` = UNHEX(?),
            `zip` = UNHEX(?),
            `institution` = UNHEX(?),
            `study` = ?,
            `family` = UNHEX(?)
        WHERE `id` = UNHEX(?)";
    $stmt = $clinical_data_pdo->prepare($sql);
    $stmt->bindParam(1, $enc_birth, PDO::PARAM_STR);
    $stmt->bindParam(2, $enc_gender, PDO::PARAM_STR);
    $stmt->bindParam(3, $enc_race, PDO::PARAM_STR);
    $stmt->bindParam(4, $enc_zip, PDO::PARAM_STR);
    $stmt->bindParam(5, $enc_institution, PDO::PARAM_STR);
    $stmt->bindParam(6, $study);
    $stmt->bindParam(7, $enc_family, PDO::PARAM_STR);
    $stmt->bindParam(8, $enc_id, PDO::PARAM_STR);
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
            INSERT INTO `Patient_tracking`(
            `id`,
            `birth`,
            `gender`,
            `race`,
            `zip`,
            `institution`,
            `study`,
            `family`,
            `tracking`,
            `event`
        )
        VALUES (UNHEX(?),UNHEX(?),UNHEX(?),UNHEX(?),UNHEX(?),UNHEX(?),?,UNHEX(?),?, ?)
    ";
    $stmt3 = $clinical_data_pdo->prepare($sql3);
    $stmt3->bindParam(1, $enc_id, PDO::PARAM_STR);
    $stmt3->bindParam(2, $enc_birth, PDO::PARAM_STR);
    $stmt3->bindParam(3, $enc_gender, PDO::PARAM_STR);
    $stmt3->bindParam(4, $enc_race, PDO::PARAM_STR);
    $stmt3->bindParam(5, $enc_zip, PDO::PARAM_STR);
    $stmt3->bindParam(6, $enc_institution, PDO::PARAM_STR);
    $stmt3->bindParam(7, $study);
    $stmt3->bindParam(8, $enc_family, PDO::PARAM_STR);
    $stmt3->bindParam(9, $tracking);
    $stmt3->bindParam(10, $event);

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
        echo "There was a problem while saving the data. ";
        echo "Please contact the admins at mcoder@uhn.ca. ";
        // echo "Your reference: " . $tracking . ":" . $error;
    }
} else {
    echo "You exceeded the amount of updates you can do in 24 hours!";
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
