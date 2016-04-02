<?php

if( !isset($_GET['item']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'defs.php';
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
  
  $sql = "SELECT * FROM Orders WHERE status='cart' AND cid=".$cid." AND pid=".$pid;
  $ep = $db->select($sql);
  
  if( count($ep) > 0 ) {
    $sql = "UPDATE Orders SET quantity='".($ep[0]['quantity']+1)."' WHERE cid=".$cid." AND pid=".$pid;
    $db->query($sql);
    
    if( $db->error() == "" ) {
      $cart = getCartInfo();
      $_SESSION['user']['cart'] = $cart['cart'];
      $_SESSION['user']['cartSize'] = $cart['cartSize'];
      
      $cartsize = $cartsize + 1;
      echo json_encode( Array(
          "result" => "success",
          "cartSize" => $cartsize,
          "cart" => $_SESSION['user']['cart']
      ));
    } else {
      echo json_encode( Array(
          "result" => $db->error()
      ));
    }
    exit;
  } else {
    $sql = "INSERT INTO Orders (cid,pid,status,quantity) VALUES (".$cid.",".$pid.",'cart',1)";
    $res = $db->query($sql);
    
    if( $db->error() == "" ) {
      $cart = getCartInfo();
      $_SESSION['user']['cart'] = $cart['cart'];
      $_SESSION['user']['cartSize'] = $cart['cartSize'];
      
      $cartsize = $cartsize + 1;
      echo json_encode( Array(
          "result" => "success",
          "cartSize" => $cartsize,
          "cart" => $_SESSION['user']['cart']
      ));
    } else {
      echo json_encode( Array(
          "result" => $db->error()
      ));
    }
    exit;
  }
} else {
  echo json_encode( Array(
    "result" => ERR_NOT_LOGGED_IN
  ));
  exit;
}

?>