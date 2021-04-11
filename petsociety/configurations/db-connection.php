<?php

$db = mysqli_connect('localhost', 'root', '', 'petsociety');
if ($db->connect_errno) {
    echo "Failed to connect to database: (" . $db->connect_errno . ") " . $db->connect_error;
    exit();
}

?>