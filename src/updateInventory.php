<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_GET['itemToUpdate']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';


echo json_encode( Array(
    "result" => json_decode($_GET['itemToUpdate'])
));
?>