<?php

define("WRONG_PARAMS", "Wrong parameters set.");

define("REGISTER_SUCCESS", "success");
define("REGISTER_FAILED", "Unable to create user record.");
define("REGISTER_REPEAT", "A user with that email address is already registered.");

define("LOGIN_SUCCESS", "success");
define("LOGIN_ERROR", "An error occured when querying the user database.");
define("LOGIN_FAILED", "Bad username or password.");

define("LOGOUT_SUCCESS", "logout");

define("STATUS", "status");

define("ERR_NOT_LOGGED_IN", "Could not complete request; not logged in.");
define("ERR_NOT_ADMIN", "Could not preform action; insufficient privelidges.");

define("UPDATE_SUCCESS", "success");
define("UPDATE_SUCCESS_MESSAGE", "Updated product info!");
define("UPDATE_ADD_SUCCESS_MESSAGE", "Inserted new product!");
define("UPDATE_FAIL", "error");
define("UPDATE_FAIL_MESSAGE", "Could not update product!");
define("UPDATE_ADD_FAIL_MESSAGE", "Could not insert product!");

$BLANK_USER = Array(
	"uname" => "",
	"email" => "",
	"isStaff" => False,
	"isManager" => False
);

function getCartInfo() {
	$db = $_SESSION['db'];
	
	$cartSql = "SELECT Item.pname,Item.cost,Item.discount,Orders.pid,Orders.quantity FROM Orders INNER JOIN Item ON Orders.pid = Item.pid WHERE Orders.status='cart' AND cid=".$_SESSION['cid'];
	
	$cart = $db->select($cartSql);
	
	if( $db->error() != "" ) {
		return Array("cart" => $db->error(), "cartSize" => NULL);
		exit;
	}
	
	$tqSql = "SELECT SUM(quantity) AS s FROM Orders WHERE status='cart' AND cid=".$_SESSION['cid'];
	$cartSize = $db->select($tqSql);
	
	for( $i=0; $i<count($cart); $i=$i+1 ) {
		$cart[$i]['icon'] = 'clear';
		$cart[$i]['totalCost'] = $cart[$i]['quantity'] * ($cart[$i]['cost'] - $cart[$i]['cost'] * $cart[$i]['discount']);
	}
	
	if( $db->error() == "" ) {
		return Array("cart" => $cart, "cartSize" => $cartSize[0]['s']);
	} else {
		return Array("cart" => $db->error(), "cartSize" => NULL);
	}
	
	exit;
}

?>