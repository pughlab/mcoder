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
	$oldData=$_POST['olddata'];

	$id=$_POST['id'];
	$medication=$_POST['medication'];
	$start=$_POST['start'];
	$stop=$_POST['stop'];
	$reason=$_POST['reason'];
	$intent=$_POST['intent'];
	$comment=str_replace("'","\'",$_POST['comment']);
	$oldMedication=$oldData['medication'];
	$oldStart=$oldData['start'];
	$oldStop=$oldData['stop'];
	$oldReason=$oldData['reason'];
	$oldIntent=$oldData['intent'];
	$oldComment=$oldData['comment'];
	$oldTracking=$oldData['tracking'];

	//Encryption
	$encryption_key = hex2bin($key);

	// initialization vector
	$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
	$iv=$iv_query['riv'];

	// ID encrypted
	//$enc_id=openssl_encrypt($id, $cipher, $encryption_key, 0, $iv);
	$enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

	mysqli_close($connect);

	$sql = "UPDATE `Medication`
		SET
			`medication` = '$medication',
			`start` = '$start',
			`stop` = '$stop',
			`reason` = '$reason',
			`intent` = '$intent',
			`comment` = '$comment',
			`tracking` = '$tracking'
		WHERE `id` = $enc_id
		AND `medication` = '$oldMedication'
		AND `start` = '$oldStart'
		AND `stop` = '$oldStop'
		AND `reason` = '$oldReason'
		AND `intent` = '$oldIntent'
		AND `comment` = '$oldComment'
		AND `tracking` = '$oldTracking'";

	$sql2 = "INSERT INTO `tracking`(`trackingid`, `username`, `email`, `roles`, `ip`, `date`)
	VALUES ('$tracking','$username','$email','$roles','$ip','$datesystem')";

	if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
		echo "Success";
	}
	else {
		$error = mysqli_error($conn);
		echo "There was a problem while saving the data. Please contact the admin of the site - Nadia Znassi. Your reference: ". $tracking .":". $error;
	}


	mysqli_close($conn);

?>
