<?php

if( !isset($_POST['pid']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'defs.php';
require 'connect.php';

if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {

  $db = $_SESSION['db'];
  
  $pid = $_POST['pid'];
  
  $cid = $_SESSION['cid'];
  
 
  $sql = "DELETE FROM Orders WHERE status='cart' AND cid=".$cid." AND pid=".$pid;
  
  $db->query($sql);
  
  if( $db->error() == "" ){
    $cart = getCartInfo();
    $_SESSION['user']['cart'] = $cart['cart'];
    $_SESSION['user']['cartSize'] = $cart['cartSize'];
    
    echo json_encode( Array(
        "result" => "success",
        "cartSize" => $_SESSION['user']['cartSize'],
        "cart" => $_SESSION['user']['cart']
    ));
  } else {
    echo json_encode( Array(
        "result" => $db->error()
    ));
  }
} else {
  echo json_encode( Array(
    "result" => ERR_NOT_LOGGED_IN
  ));
  exit;
}

?>