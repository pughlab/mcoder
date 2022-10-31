<?php

include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

// Ip address of the user
$ip=$_POST['ip'];
$datesystem=$_POST['datesystem'];
$email=$_POST['email'];
$username=$_POST['username'];
$roles=$_POST['roles'];
$tracking=$_POST['tracking'];

$id=$_POST['id'];
$date=$_POST['date'];
$type=$_POST['type'];
$evaluation=$_POST['evaluation'];
$number=$_POST['number'];
$location=$_POST['location'];
$comment=str_replace("'", "\'", $_POST['comment']);

//Encryption
$encryption_key = hex2bin($key);

// initialization vector
$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv=$iv_query['riv'];

// ID encrypted
$enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));

mysqli_close($connect);

if ($hasAdminRole) {
    $sql = "DELETE FROM `LesionsNF1`
        WHERE
            `id` = $enc_id
            AND `date` = '$date'
            AND `type` = '$type'
            AND `evaluation` = '$evaluation'
            AND `number` = '$number'
            AND `location` = '$location'
            AND `comment` = '$comment'";

    $sql2 = "INSERT INTO `tracking`(`trackingid`, `username`, `email`, `roles`, `ip`, `date`)
    VALUES ('$tracking','$username','$email','$roles','$ip','$datesystem')";

    if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
        echo "Success";
    } else {
        $error = mysqli_error($conn);
        echo "There was a problem while deleting the data. ";
        echo "Please contact the admin of the site - Nadia Znassi. Your reference: ". $tracking .":". $error;
    }
}
mysqli_close($conn);
