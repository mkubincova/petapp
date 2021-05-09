<?php

include "configurations/config.php";

//if statement to check if the user is logged in
($_SESSION) ? header("Location: mypets.php") :
header("Location: home.php");

?>
