<?php
include('../../configuration/db.php');
include('../../configuration/mcode.php');
include('../../configuration/key.php');

$id = $_GET['id'];
//echo $id;
//echo "<br>";
//echo "<br>";

//Encryption
$encryption_key = base64_decode($key);
// initialization vector
$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv=$iv_query['riv'];

// ID encrypted
$enc_id=openssl_encrypt($id, $cipher, $encryption_key, 0, $iv);

mysqli_close($connect);

$result=mysqli_query($conn, "select * from pedigree where id = '$enc_id'");

// if(mysqli_fetch_row($result) == 0) {
//   echo "No family pedigree exists for this patient!";
// }

//else {
//  print_r($result);
if(mysqli_num_rows($result) > 0)
{
while($data = mysqli_fetch_row($result))
{
  //  echo $data[0];
  //  echo "<br>";
    echo $data[1];
  //  echo "<br>";
  //  echo "<br>";
  //  echo "<br>";
}
//}
}
else {
  echo "None";
}

	mysqli_close($conn);

?>
