<?php

//Menu highlight
$currentPage = basename($_SERVER["PHP_SELF"]);

//Session $lifetime, $path, $domain, $secure (SSL), $httpOnly (not accessible via js)
session_set_cookie_params(3600, "/", "localhost", false, true);
session_start();

if ($_SESSION && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
}

?>