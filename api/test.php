<?php

include "config.php";
include "request.php";


//check if we sucessfully connected to database (inside config.php)
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

        //close the query and connection
        $stmt->close();
        $db->close();

        //send the array of objects as JSON
        if ($response) {
            echo json_encode($response);
        } else {
            echo "No data match your query";
        }
              
//////////////////////////////////////////////////////////////////////////////////////////////// 
    } elseif ($method == "POST"){
        //save new fact into db
        $query = "INSERT INTO petfacts VALUES (?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $fact);
        $stmt->execute();

        //close the query and connection
        $stmt->close();
        $db->close();

////////////////////////////////////////////////////////////////////////////////////////////////         
    } elseif ($method == "PUT") {
        echo $body;
        
////////////////////////////////////////////////////////////////////////////////////////////////         
    } elseif ($method == "DELETE") {

        if ($id) {
            //delete fact by id
            $query = "DELETE FROM petfacts WHERE factId = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
              
        } else {
            echo "To delete a fact, you need to specify its id";
        }
    }
} else {
    echo "No database connection";
}
