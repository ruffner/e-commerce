<?php

header('Content-Type: application/json');

require 'defs.php';
require 'connect.php';

if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True ) {
    echo json_encode( Array(
         "result" => STATUS,
    	 "loggedIn" => True,
     	 "user" => $_SESSION['user']
    ));
} else {
    echo json_encode( Array(
     	 "result" => LOGOUT_SUCCESS,
     	 "loggedIn" => False
    ));
}

?>