<?php
include '../configuration/mcode.php';

$text = $mysqli->real_escape_string($_GET['term']);

$query = "SELECT distinct name FROM test WHERE name LIKE '%$text%' ORDER BY name ASC LIMIT 15";
$result = $mysqli->query($query);
$json = '[';
$first = true;
while($row = $result->fetch_assoc())
{
    if (!$first) { $json .=  ','; } else { $first = false; }
    $json .= '{"value":"'.$row['name'].'"}';


}
$json .= ']';
echo $json;
?>
