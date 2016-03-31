<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_GET['filter']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

$filter = json_decode($_GET['filter']);

if( count($filter) == 0 || $filter[0] == "" ) {
    $sql = "SELECT pid,pname,ptype,cost,discount,quantity FROM Item";
} else {
    $sql = "SELECT pid,pname,ptype,cost,discount,quantity,image FROM Item WHERE ptype IN ('" . implode("','", $filter) . "')";
}
$res = $_SESSION['db']->select($sql);

echo json_encode( Array(
    "result" => "success",
    "products" => $res
));
exit;
?>