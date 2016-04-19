<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_POST['getOrders']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  $db = $_SESSION['db'];
  
  // pulling everyones orders sorted by time decending
  if( $_SESSION['user']['isStaff'] || $_SESSION['user']['isManager'] ) {
    
    $sql = "SELECT * FROM (SELECT Orders.cid, Orders.pid, Orders.status, Orders.quantity, Orders.odate, Item.pname, Item.ptype, Item.psubtype, Item.cost, Item.discount, Customer.uname, Customer.email FROM Orders INNER JOIN Item ON Orders.pid=Item.pid INNER JOIN Customer ON Orders.cid=Customer.cid) AS t1 WHERE t1.status <> 'cart' ORDER BY t1.odate DESC";
    
    $res = $db->select($sql);

    for( $i=0; $i<count($res); $i=$i+1 ) {
      $res[$i]['price'] = $res[$i]['cost'] - $res[$i]['cost']*$res[$i]['discount'];
    }

    // successfully got orders
    if( $db->error() == "" ){
      echo json_encode( Array(
          "result" => "success",
          "orders" => $res,
          "isAdmin" => 1
      ));
    }
    // query failed
    else {
      echo json_encode( Array(
          "result" => "query failed",
          "orders" => ''
      ));
    }
    exit;
  }
  // pulling one users orders based on login session
  else {
    $sql = "SELECT * FROM (SELECT Orders.cid, Orders.pid, Orders.status, Orders.quantity, Orders.odate, Item.pname, Item.ptype, Item.psubtype, Item.cost, Item.discount FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) AS t1 WHERE t1.cid=".($_SESSION['cid'])." AND t1.status <> 'cart' ORDER BY t1.odate DESC";
    
    $res = $db->select($sql);

    for( $i=0; $i<count($res); $i=$i+1 ) {
      $res[$i]['price'] = $res[$i]['cost'] - $res[$i]['cost']*$res[$i]['discount'];
    }

    // successfully got orders
    if( $db->error() == "" ){
      echo json_encode( Array(
          "result" => "success",
          "orders" => $res,
          "isAdmin" => 0
      ));
    }
    // query failed
    else {
      echo json_encode( Array(
          "result" => "query failed",
          "orders" => ''
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