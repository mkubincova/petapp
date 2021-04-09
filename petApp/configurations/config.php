<?php

//Menu highlight
$currentPage = basename($_SERVER["PHP_SELF"]);

//Session
session_set_cookie_params(1800, "/", "localhost", false, true);
session_start();

if ($_SESSION && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
}

?>