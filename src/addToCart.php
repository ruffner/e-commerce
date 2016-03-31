<?php

if( !isset($_GET['item']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  //$now = date("Y-m-d H:i:s");
  
  $db = $_SESSION['db'];
  
  $item = $_GET['item'];
  
  $pid = $db->quote($item);
  $cid = $_SESSION['cid'];
  
  $sql = "SELECT SUM(quantity) AS s FROM Orders WHERE status='cart' AND cid=".$cid;
  $cartsize = $db->select($sql);
  $cartsize = $cartsize[0]['s'];
  
  $sql = "INSERT INTO Orders (cid,pid,status,quantity) VALUES (".$cid.",".$pid.",'cart',1)";
  $res = $db->query($sql);
  
  if( $db->error() == "" ) {
    $cartsize = $cartsize + 1;
    echo json_encode( Array(
        "result" => "success",
        "cartSize" => $cartsize
    ));
  } else {
    echo json_encode( Array(
        "result" => $db->error()
    ));
  }
  exit;
} else {
  echo json_encode( Array(
    "result" => ERR_NOT_LOGGED_IN
  ));
  exit;
}

?>