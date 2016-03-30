<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_POST['itemToDelete']) ){
  echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';


if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  if( $_SESSION['user']['isStaff'] || $_SESSION['user']['isManager'] ) {
    
    $db = $_SESSION['db'];
    
    $id = $db->quote($_POST['itemToDelete']);
    
    $sql = "DELETE FROM Item WHERE pid=".$id;
  
    $db->query($sql);
    
    echo json_encode( Array(
      "result" => $db->error(),
      "message" => "tried to remove item"
    ));
  }
}

?>