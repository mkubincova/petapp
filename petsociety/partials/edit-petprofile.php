<?php
include 'img-upload.php'; 
include '../configurations/config.php';
include '../configurations/db-connection.php';


if (!empty($_POST['name']) && !empty($_POST['species'])) {

    //get & sanitize the form data
    $name = htmlspecialchars($_POST['name']);
    $species = htmlspecialchars($_POST['species']);
    $breed = htmlspecialchars($_POST['breed']);
    $birthday = htmlspecialchars($_POST['birthday']);
    $likes = htmlspecialchars($_POST['likes']);
    $dislikes = htmlspecialchars($_POST['dislikes']);
    $other = htmlspecialchars($_POST['other']);
    $oldimg = $_POST['oldimg'];
    $id = $_POST['petId'];
    $tempImgUrl = htmlspecialchars($_POST['imgUrl']);
   
    //check if user uploaded a new profile picture
    if (!empty($tempImgUrl)) {

        //move uploaded image from temp folder to pet-profiles
        $imgUrl = str_replace("../img/temp", "pet-profiles", $tempImgUrl);
        rename($tempImgUrl, "../img/" . $imgUrl);

            //delete old profile picture from img folder
            unlink('../img/' . $oldimg);

            //save new infor in pet table
            $query = "UPDATE pet SET name = ?, species = ?, breed = ?, birthday = ?, imgUrl = ?, likes = ?, dislikes = ?, otherInformation = ? 
            WHERE petID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("ssssssssi", $name, $species, $breed, $birthday, $imgUrl, $likes, $dislikes, $other, $id);

            if ($stmt->execute()) {

                $stmt->close(); //close statement
                header("Location: ../mypets.php");

            } else {
                echo "<p>There was an error processing your request, please try again.</p>";
            }

    } else {
        
        //save new info in pet table
        $query = "UPDATE pet SET name = ?, species = ?, breed = ?, birthday = ?, likes = ?, dislikes = ?, otherInformation = ? 
            WHERE petID = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssssi", $name, $species, $breed, $birthday, $likes, $dislikes, $other, $id);

        if ($stmt->execute()) {

            $stmt->close(); //close statement
            header("Location: ../petprofiles-single.php?id=$id");
            
        } else {
            echo "<p>There was an error processing your request, please try again.</p>";
        }
    }
    

    
    
 
}
