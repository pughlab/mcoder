<?php
include '../configuration/mcode.php';

$text = $mysqli->real_escape_string($_GET['term']);

$query = "SELECT distinct hgvs FROM variant WHERE hgvs LIKE '%$text%' LIMIT 15";
$result = $mysqli->query($query);
$json = '[';
$first = true;
while($row = $result->fetch_assoc())
{
    if (!$first) { $json .=  ','; } else { $first = false; }
    $json .= '{"value":"'.$row['hgvs'].'"}';


}
$json .= ']';
echo $json;
?>
