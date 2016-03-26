<?php

header('Content-Type: application/json');

require 'defs.php';

/*if( !isset($_POST['filter']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}*/

require 'connect.php';

$filter = json_decode($_POST['filter']);

if(count($filter) == 0) {
    $sql = "SELECT pname,ptype,cost,discount,quantity FROM Item";
} else {
    $sql = "SELECT pname,ptype,cost,discount,quantity FROM Item WHERE ptype IN ('" . implode("','", $filter) . "')";
}
$res = $_SESSION['db']->select($sql);

echo json_encode( Array(
    "result" => "success",
    "products" => $res
));
exit;
?>