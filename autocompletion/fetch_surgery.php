<?php
//fetch.php
include '../configuration/mcode.php';
$request = mysqli_real_escape_string($connect, $_POST["query"]);
$query = "
 SELECT * FROM surgery WHERE name LIKE '%".$request."%' LIMIT 15
";

$result = mysqli_query($connect, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["name"];
 }
 echo json_encode($data);
}

?>
