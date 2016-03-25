<?php

header('Content-Type: application/json');

require 'connect.php';
require 'defs.php';

if( !(isset($_POST['uname']) && isset($_POST['pass'])) ) {
    echo json_encode( Array("result" => WRONG_PARAMS) );
    exit;
}

$uname = $_SESSION['db']->quote($_POST['uname']);
$pass = $_SESSION['db']->quote($_POST['pass']);

$sql = "SELECT * FROM Customer WHERE uname=".$uname." AND password=".$pass;

$res = $_SESSION['db']->select($sql);

if( $_SESSION['db']->error() != "" ) {
    // db error
    echo json_encode( Array("result" => LOGIN_ERROR) );
    exit;
}

if( count($res) == 0 ) {
    // bad uname/pass
    echo json_encode( Array("result" => LOGIN_FAILED) );
    exit;
}

// user is valid if we make it here
$_SESSION['loggedIn'] = True;
$user = $BLANK_USER;
$user['uname'] = $_POST['uname'];
$user['email'] = $res[0]['email'];


// decide staff status

$sql = "SELECT * FROM Staff WHERE uname=".$uname;
$res = $_SESSION['db']->select($sql);

if( count($res) > 0 ) {
    $user['isStaff'] =  True;
}


// decide manager status

$sql = "SELECT * FROM Manager WHERE uname=".$uname;
$res = $_SESSION['db']->select($sql);

if( count($res) > 0 ) {
    $user['isManager'] = True;
}

$_SESSION['user'] = $user;

echo json_encode( Array(
     "result" => LOGIN_SUCCESS,
     "loggedIn" => True,
     "user" => $user
));

exit;
?>