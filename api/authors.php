<?php

include "config.php";
$method = $_SERVER["REQUEST_METHOD"];

if ($db) {
    if ($method == "GET") {

        $query = "SELECT * FROM author";

        $result = mysqli_query($db, $query);
        $response = array();

        if ($result) {
            header("Content-type: JSON"); 
            while ($row = mysqli_fetch_assoc($result)) {
                $response[] = $row;
            };
            $result->close();
        }
        echo json_encode($response);

    } elseif ($method == "POST"){
        echo json_encode("This is a post request");
    }
}


?>