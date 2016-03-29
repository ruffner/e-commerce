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
define("UPDATE_FAIL", "Could not update product!");
define("UPDATE_ADD_FAIL", "Could not insert product!");

$BLANK_USER = Array(
	"uname" => "",
	"email" => "",
	"isStaff" => False,
	"isManager" => False
);

?>