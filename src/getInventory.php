<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_POST['getInventory']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  if( $_SESSION['user']['isStaff'] || $_SESSION['user']['isManager'] ) {
    $sql = "SELECT * FROM Item";
    
    $res = $_SESSION['db']->select($sql);
    
    for( $i=0; $i<count($res); $i=$i+1 ) {
      $res[$i]['price'] = $res[$i]['cost'] - $res[$i]['cost']*$res[$i]['discount'];
      $res[$i]['editing'] = False;
      $res[$i]['icon'] = 'create';
    }
    
    echo json_encode( Array(
        "result" => "success",
        "items" => $res
    ));
    exit;
  } else {
    echo json_encode( Array(
      "result" => ERR_NOT_ADMIN
    ));
    exit;
  }
} else {
  echo json_encode( Array(
    "result" => ERR_NOT_LOGGED_IN
  ));
  exit;
}

?>