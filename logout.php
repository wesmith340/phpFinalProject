<?php

session_destroy();
$_SESSION = array();
header('Location: login.php');     // go to login page
exit;