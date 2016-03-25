<?php

header('Content-Type: application/json');

require 'defs.php';

session_unset();
session_destroy();

echo json_encode( Array(
     "result" => LOGOUT_SUCCESS,
     "loggedIn" => False
));

exit;
?>