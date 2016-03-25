<?php

require 'neatdb.php';

session_start();

$_SESSION['db'] = new NeatDB();

?>
