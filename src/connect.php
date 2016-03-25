<?php

require 'neatdb.php';

if( session_id() == "" ) {
    session_start();
    $_SESSION['loggedIn'] = False; 
}

$_SESSION['db'] = new NeatDB();
?>
