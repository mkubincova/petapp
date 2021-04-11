<?php

include 'configurations/config.php';

//if statement to check if the user is logged in
($_SESSION) ? header("Location: /awa/petsociety/mypets.php") :
header("Location: /awa/petsociety/home.php");

?>
