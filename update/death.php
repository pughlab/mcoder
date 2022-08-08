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
	$date=$_POST['date'];
	$comment=str_replace("'","\'",$_POST['comment']);
	$oldDate=$oldData['date'];

	//Encryption
	$encryption_key = hex2bin($key);

	// initialization vector
	$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
	$iv=$iv_query['riv'];

	// ID encrypted
	//$enc_id=openssl_encrypt($id, $cipher, $encryption_key, 0, $iv);
	$enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

	mysqli_close($connect);

	$checkID=mysqli_query($conn, "select * from Death where id = $enc_id");

	if(mysqli_fetch_row($checkID) > 1) {
		echo "The date of death has already been registered for this patient!";
	} else {

		$sql = "UPDATE `Death`
			SET
				`date` = '$date',
				`comment` = '$comment',
				`tracking` = '$tracking'
			WHERE `id` = $enc_id
			AND `date` = '$oldDate'";

		$sql2 = "INSERT INTO `tracking`(`trackingid`, `username`, `email`, `roles`, `ip`, `date`)
		VALUES ('$tracking','$username','$email','$roles','$ip','$datesystem')";

		if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
			echo "Success";
		}
		else {
			$error = mysqli_error($conn);
			echo "There was a problem while saving the data. Please contact the admin of the site - Nadia Znassi. Your reference: ". $tracking .":". $error;
		}

	}
	mysqli_close($conn);

?>
