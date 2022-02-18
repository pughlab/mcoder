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
	$birth=$_POST['birth'];
	$gender=$_POST['gender'];
	$race=$_POST['race'];
	$zip=$_POST['zip'];
	$institution=$_POST['institution'];
	$study=$_POST['study'];
	$family=$_POST['family'];

	//Encryption
	//$encryption_key = base64_decode($key);
  $encryption_key = hex2bin($key);

	// initialization vector
	$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
  $iv=$iv_query['riv'];

	// ID encrypted
	//$enc_id=openssl_encrypt($id, $cipher, $encryption_key, 0, $iv);
  //$enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));
  $enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));
  //echo $enc_id;
  //echo "\n";
  //echo bin2hex($enc_id);
  //0x

	mysqli_close($connect);


	$checkID=mysqli_query($conn, "select * from Patient where id = $enc_id");

	if(mysqli_fetch_row($checkID) > 1) {
		echo "This patient identifier already exists in the database! Please use another one!";
	}

  else {

		$enc_birth="0x".bin2hex(openssl_encrypt($birth, $cipher, $encryption_key, 0, $iv));
		$enc_gender="0x".bin2hex(openssl_encrypt($gender, $cipher, $encryption_key, 0, $iv));
		$enc_race="0x".bin2hex(openssl_encrypt($race, $cipher, $encryption_key, 0, $iv));
		$enc_zip="0x".bin2hex(openssl_encrypt($zip, $cipher, $encryption_key, 0, $iv));
		$enc_institution="0x".bin2hex(openssl_encrypt($institution, $cipher, $encryption_key, 0, $iv));
		$enc_family="0x".bin2hex(openssl_encrypt($family, $cipher, $encryption_key, 0, $iv));

		$sql = "INSERT INTO `Patient`(`id`, `birth`, `gender`, `race`, `zip`, `institution`, `study`, `family`, `tracking`)
		VALUES ($enc_id, $enc_birth, $enc_gender, $enc_race, $enc_zip, $enc_institution, '$study', $enc_family, '$tracking')";

    //echo $sql;
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
