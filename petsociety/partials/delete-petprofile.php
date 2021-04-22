<?php
include '../configurations/config.php';
include '../configurations/db-connection.php';

$id = $_GET["id"];
$imgUrl = $_GET["img"];

//delete pet profile from user_pet table
$query = "DELETE FROM user_pet WHERE petID = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    //delete pet profile from pet table
    $query2 = "DELETE FROM pet WHERE petID = ?";
    $stmt2 = $db->prepare($query2);
    $stmt2->bind_param("i", $id);

    if ($stmt2->execute()) {
        $stmt2->close();

        //delete image from img folder
        unlink('../img/' . $imgUrl);
        
        header("Location: ../mypets.php");
    }
    
}



