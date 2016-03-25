<?php

require 'neatdb.php';

if( session_id() == "" )
    session_start();

$_SESSION['db'] = new NeatDB();
?>
