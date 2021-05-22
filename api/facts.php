<?php

include "config.php";
include "request.php";

//check if we sucessfully connected to database
if ($db) {

////////////////////////////////////////////////////////////////////////////////////////////////    
    if ($method == "GET") {

        //get fact by id (...facts.php?id=3)
        if ($id) {
            $query = "SELECT * FROM petfacts WHERE factId = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);

        //get all facts   
        } else {
            $query = "SELECT * FROM petfacts";
            $stmt = $db->prepare($query);   
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        
        //save each row of db as an object in the $response array
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        };

        //send the array of objects as JSON
        if ($response) {
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($response);
        }
              
//////////////////////////////////////////////////////////////////////////////////////////////// 
    } elseif ($method == "POST"){

        if ($text) {
            //save new fact into db
            $query = "INSERT INTO petfacts (text) VALUES (?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $text);

            if ($stmt->execute()) {
                http_response_code(201);
            }
        } else {
            echo "Send text in request body";
        }
        

///////////////////////////////////////////////////////////////////////////////////////////////         
    } elseif ($method == "PUT") {
        if ($id && $text) {

            //update fact by id
            $query = "UPDATE petfacts SET text = ? WHERE factId = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("si", $text, $id);

            if ($stmt->execute()) {
                http_response_code(204);
            }

        } else {
            echo "Specify id in query parameter and send text in request body";
        }
      
////////////////////////////////////////////////////////////////////////////////////////////////         
    } elseif ($method == "DELETE") {

        if ($id) {

            //delete fact by id
            $query = "DELETE FROM petfacts WHERE factId = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                http_response_code(204);
            }
              
        } else {
            echo "Specify id in query parameter";
        }
    }

    //close the query and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $db->close();

} else {
    echo "No database connection";
}
