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
	$oldData =  $_POST['olddata'];

	$id=$_POST['id'];
	$date=$_POST['date'];
	$type=$_POST['type'];
	$histology=$_POST['histology'];
	$status=$_POST['status'];
	$location=$_POST['location'];
	$side=$_POST['side'];
	$oncotree=$_POST['oncotree'];
	$clinicalsg=$_POST['clinicalsg'];
	$clinicalss=$_POST['clinicalss'];
	$pathologicsg=$_POST['pathologicsg'];
	$pathologicss=$_POST['pathologicss'];
	$comment=str_replace("'","\'",$_POST['comment']);

	//Encryption
	$encryption_key = hex2bin($key);

	// initialization vector
	$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
	$iv=$iv_query['riv'];

	// ID encrypted
	//$enc_id=openssl_encrypt($id, $cipher, $encryption_key, 0, $iv);
	$enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

	mysqli_close($connect);

	$olddate = $oldData['date'];
	$oldtype = $oldData['type'];
	$oldhistology = $oldData['histology'];
	$oldstatus = $oldData['status'];
	$oldcode = $oldData['location'];
	$oldside = $oldData['side'];
	$oldoncotree = $oldData['oncotree'];
	$oldclinicalsg = $oldData['clinicalsg'];
	$oldclinicalss = $oldData['clinicalss'];
	$oldpathologicsg = $oldData['pathologicsg'];
	$oldpathologicss = $oldData['pathologicss'];
	$oldcomment = $oldData['comment'];

	$sql = "UPDATE `Diseases`
		SET 
			`date` = '$date', 
			`type` = '$type', 
			`histology` = '$histology',
			`status` = '$status',
			`code` = '$location',
			`side` = '$side',
			`oncotree` = '$oncotree',
			`clinicalsg` = '$clinicalsg',
			`clinicalss` = '$clinicalss',
			`pathologicsg` = '$pathologicsg',
			`pathologicss` = '$pathologicss',
			`comments` = '$comment',
			`tracking` = '$tracking'
	WHERE `id` = $enc_id
	AND `date` = '$olddate'
	AND `type` = '$oldtype'
	AND `histology` = '$oldhistology'
	AND `status` = '$oldstatus'
	AND `code` = '$oldcode'
	AND `side` = '$oldside'
	AND `oncotree` = '$oldoncotree'
	AND `clinicalsg` = '$oldclinicalsg'
	AND `clinicalss` = '$oldclinicalss'
	AND `pathologicsg` = '$oldpathologicsg'
	AND `pathologicss` = '$oldpathologicss'
	AND `comments` = '$oldcomment'";

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
