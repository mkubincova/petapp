<?php

//allow cross-origin requests
header("Access-Control-Allow-Origin: *");

//check the method (get/post/put/delete)
$method = $_SERVER["REQUEST_METHOD"];

//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;

//check body
$body = (null != file_get_contents("php://input")) ? json_decode(file_get_contents("php://input"), true) : null;
if ($body) {
    $text = (isset($body["text"])) ? htmlspecialchars($body["text"]) : null;
}



