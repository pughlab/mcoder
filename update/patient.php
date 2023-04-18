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
//$encryption_key = base64_decode($key);
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
