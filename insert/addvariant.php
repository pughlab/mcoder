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
	$test=$_POST['test'];
  $gene=$_POST['gene'];
  $cdna=$_POST['cdna'];
  $protein=$_POST['protein'];
  $mutationid=$_POST['mutationid'];
	$mutationhgvs=$_POST['mutationhgvs'];
	$interpretation=$_POST['interpretation'];
	$source=$_POST['source'];
	$comment=$_POST['comment'];

	//Encryption
	$encryption_key = hex2bin($key);

	// initialization vector
	$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
  $iv=$iv_query['riv'];

	// ID encrypted
	//$enc_id=openssl_encrypt($id, $cipher, $encryption_key, 0, $iv);
  //$enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));
  $enc_id="0x".bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));

	mysqli_close($connect);

		$sql = "INSERT INTO `Variant`(`id`, `date`, `test`, `gene`, `cdna`, `protein`, `variantid`, `varianthgvs`, `interpretation`, `source`, `comment`, `tracking`)
		VALUES ($enc_id,'$date','$test','$gene','$cdna','$protein','$mutationid','$mutationhgvs','$interpretation','$source','$comment', '$tracking')";

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