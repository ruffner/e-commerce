<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_POST['placeOrder']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

// cannot place order unless registered and logged in
if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  
  // possible place of any users order
  if( $_SESSION['user']['isStaff'] || $_SESSION['user']['isManager'] && $_POST['placeOrder'] == "ship") {
   
    $db = $_SESSION['db'];

    $cid = $db->quote($_POST['cid']);
    $date = $db->quote($_POST['odate']);

    $sql = "SELECT * FROM (SELECT Orders.cid AS cid, Orders.pid AS pid, Orders.odate, Orders.quantity AS request, Item.quantity AS stock FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) AS t1 WHERE t1.cid=".$cid." AND t1.odate=".$date;
    
    $res = $db->select($sql);
    
    $updateStatements = Array();
    
    $now = date("Y-m-d H:i:s"); 
    
    $haveInventory = 1;
    if( count($res) ) {
      for( $i=0; $i<count($res); $i=$i+1 ){
        $row = $res[$i];
        if( $row['stock'] < $row['request']) {
          $haveInventory = 0;
          break;
        } else {
          $updateStatements []= "UPDATE Item SET quantity='".($row['stock'] - $row['request'])."' WHERE pid='".($row['pid'])."'";
          $updateStatements []= "UPDATE Orders SET status='shipped', odate='".$now."' WHERE cid='".($row['cid'])."' AND pid='".($row['pid'])."' AND odate=".$date;
        }
      }
    } else {
      echo json_encode( Array(
        "result" => "empty order"
      ));
    }
    
    // update stock
    if( $haveInventory ){
      $dberr = 0;
      
      foreach( $updateStatements as $line ){
        $db->query($line);
        if( $db->error() !== "" ){
          $dberr = $db->error();
          break;
        }
      }

      echo json_encode( Array(
        "result" => "success",
        "message" => "Successfully shipped the order!"
      ));
    } 
    // order can't be placed now
    else {
      echo json_encode( Array(
        "result" => ERR_NO_STOCK
      ));
    }
    exit;
  } 
  // handle a normal user order place
  else {
    $db = $_SESSION['db'];
    // take cid from login session
    
    $cid = $_SESSION['cid'];
    
    $sql = "SELECT * FROM (SELECT Orders.cid AS cid, Orders.pid AS pid, Orders.quantity AS request, Item.quantity AS stock, Orders.status FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) AS t1 WHERE t1.cid=".$cid." AND t1.status='cart'";
    
    $res = $db->select($sql);
    
    $updateStatements = Array();
    
    $now = date("Y-m-d H:i:s"); 
    
    $haveInventory = 1;
    if( count($res) ) {
      for( $i=0; $i<count($res); $i=$i+1 ){
        $row = $res[$i];
        if( $row['stock'] < $row['request']) {
          $haveInventory = 0;
          break;
        } else {
          //$updateStatements []= "UPDATE Item SET quantity='".($row['stock'] - $row['request'])."' WHERE pid='".($row['pid'])."'";
          $updateStatements []= "UPDATE Orders SET status='pending', odate='".$now."' WHERE cid='".($row['cid'])."' AND pid='".($row['pid'])."' AND status='cart'";
        }
      }
    } else {
      echo json_encode( Array(
        "result" => "wut"
      ));
    }
    
    // update stock
    if( $haveInventory ){
      $dberr = 0;
      
      foreach( $updateStatements as $line ){
        $db->query($line);
        if( $db->error() !== "" ){
          $dberr = $line;
        }
        $dberr = $cid;
      }
      
      $cart = getCartInfo();
      $_SESSION['user']['cart'] = $cart['cart'];
      $_SESSION['user']['cartSize'] = $cart['cartSize'];
      
      echo json_encode( Array(
        "result" => "success",
        "cart" => $_SESSION['user']['cart'],
        "cartSize" => $_SESSION['user']['cartSize'],
        "db" => $dberr
      ));
    } 
    // order can't be placed now
    else {
      echo json_encode( Array(
        "result" => ERR_NO_STOCK
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