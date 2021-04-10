<?php 

//DB info
$host = "localhost";
$user = "root";
$password = "";
$database = "api_db";

//Error handling
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//Connect to DB
$db = mysqli_connect($host, $user, $password, $database);
if ($db->connect_errno) {
    echo "Failed to connect to database: (" . $db->connect_errno . ") " . $db->connect_error;
    exit();
}

?>