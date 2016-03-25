<?php

header('Content-Type: application/json');

require 'defs.php';
require 'connect.php';

unset($_SESSION['loggedIn']);

session_destroy();
session_unset();

echo json_encode( Array(
     "result" => LOGOUT_SUCCESS,
     "loggedIn" => False
));

exit;
?>