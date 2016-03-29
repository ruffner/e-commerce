<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_GET['itemToUpdate']) ){
  echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';


if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  if( $_SESSION['user']['isStaff'] || $_SESSION['user']['isManager'] ) {
    
    $item = json_decode($_GET['itemToUpdate']);

    
    
    if( $item->new == True ) {
      //addItem($item);
      
      echo json_encode( Array(
        "result" => UPDATE_SUCCESS,
        "message" => UPDATE_ADD_SUCCESS_MESSAGE
      ));
      exit;
    
    } else {
      //updateItem($item);
      
      echo json_encode( Array(
        "result" => UPDATE_SUCCESS,
        "message" => UPDATE_SUCCESS_MESSAGE
      ));
      exit;
    }
    
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

exit;
?>