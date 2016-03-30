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
      $res = addItem($item);
      if( $res == "") {
        echo json_encode( Array(
          "result" => UPDATE_SUCCESS,
          "message" => UPDATE_ADD_SUCCESS_MESSAGE
        ));
      } else {
        echo json_encode( Array(
          "result" => $res,
          "message" => UPDATE_ADD_FAIL_MESSAGE
        ));
      }
      exit;
    
    } else {
      $res = updateItem($item);
      
      if( $res == "") {
        echo json_encode( Array(
          "result" => UPDATE_SUCCESS,
          "message" => UPDATE_SUCCESS_MESSAGE
        ));
      } else {
        echo json_encode( Array(
          "result" => $res,
          "message" => UPDATE_FAIL_MESSAGE
        ));
      }
      
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

function addItem($item) {
  $db = $_SESSION['db'];
  
  $pname = $db->quote($item->pname);
  $ptype = $db->quote($item->ptype);
  $psubtype = ($item->psubtype ? $db->quote($item->psubtype) : "''");
  $cost = $db->quote($item->cost);
  $discount = $db->quote($item->discount);
  $quantity = $db->quote($item->quantity);
  $image = ($item->image ? $db->quote($item->image) : "''");
  $info = ($item->info ? $db->quote($item->info) : "''");
  
  $sql =  "INSERT INTO Item (pname, ptype, psubtype, cost, discount, quantity, image, info) ";
  $sql .= "VALUES (".$pname.",".$ptype.",".$psubtype.",".$cost.",".$discount.",".$quantity.",".$image.",".$info.")";

  $db->query($sql);
  
  return $db->error();
}

function updateItem($item) {
  $db = $_SESSION['db'];
  
  $pname = $db->quote($item->pname);
  $ptype = $db->quote($item->ptype);
  $psubtype = ($item->psubtype ? $db->quote($item->psubtype) : "''");
  $cost = $item->cost;
  $discount = $item->discount;
  $quantity = $item->quantity;
  $image = ($item->image ? $db->quote($item->image) : "''");
  $info = ($item->info ? $db->quote($item->info) : "''");
  
  $sql =  "UPDATE Item SET pname=".$pname;
  $sql .= ", ptype=".$ptype.",psubtype=".$psubtype;
  $sql .= ", cost=".$cost.", discount=".$discount;
  $sql .= ", quantity=".$quantity.", image=".$image;
  $sql .= ", info=".$info." WHERE pid=".$item->pid;
  

  $db->query($sql);
  
  return $db->error();
}

?>