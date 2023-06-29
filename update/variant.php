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
