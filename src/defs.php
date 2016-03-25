<?php

define("WRONG_PARAMS", "Wrong parameters set.");

define("REGISTER_SUCCESS", "success");
define("REGISTER_FAILED", "Unable to create user record.");
define("REGISTER_REPEAT", "A user with that email address is already registered.");

define("LOGIN_SUCCESS", "success");
define("LOGIN_ERROR", "An error occured when querying the user database.");
define("LOGIN_FAILED", "Bad username or password.");

$BLANK_USER = Array(
	"uname" => "",
	"email" => "",
	"isStaff" => False,
	"isManager" => False
);

?>