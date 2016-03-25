<?php

header('Content-Type: application/json');

require 'connect.php';
require 'defs.php';

if( !(isset($_POST['email']) && isset($_POST['uname']) && isset($_POST['pass'])) ) {
	echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

$email = $_SESSION['db']->quote($_POST['email']);

$cur = $_SESSION['db']->select("SELECT * FROM Customer WHERE email=".$email."");

if( count($cur) == 0 ) {
	$uname = $_SESSION['db']->quote($_POST['uname']);
	$pass = $_SESSION['db']->quote($_POST['pass']);
	
	$sql = "INSERT INTO Customer (email, uname, password) VALUES (".$email.",".$uname.",".$pass.")";
	
	$_SESSION['db']->query($sql);
	if( $_SESSION['db']->error() == "" ) {
		echo json_encode( Array("result" => REGISTER_SUCCESS) );
		// no error
	} else {
		echo json_encode( Array("result" => REGISTER_FAILED) );
		// error
	}
} else {
	echo json_encode( Array("result" => REGISTER_REPEAT) );
}

exit;
?>
