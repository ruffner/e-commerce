<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_POST['getInventory']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

$sql = "SELECT * FROM Item";

$res = $_SESSION['db']->select($sql);

for( $i=0; $i<count($res); $i=$i+1 ) {
  $res[$i]['price'] = $res[$i]['cost'] - $res[$i]['cost']*$res[$i]['discount'];
  $res[$i]['discount'] = $res[$i]['discount'] * 100;
  $res[$i]['editing'] = False;
  $res[$i]['icon'] = 'create';
}

echo json_encode( Array(
    "result" => "success",
    "items" => $res
));
exit;

?>