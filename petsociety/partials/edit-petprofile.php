<?php
include 'img-upload.php'; 
include '../configurations/config.php';
include '../configurations/db-connection.php';


if (isset($_POST['name']) && isset($_POST['species'])) {

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
   
    //print_r($_FILES['newimg']['name']);
    //check if user uploaded a new profile picture
    if ($_FILES['newimg']['name'] !== '') {

        //validate and save the image in img folder
        $imgUrl = uploadImg($_FILES['newimg'], 'pet-profiles');

        //if the img was saved continue saving data to db otherwise display error
        if (substr($imgUrl, 0, 5) == 'Error') {

            echo $imgUrl; //Error: Your image is too big!

        } else {

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
        }

    } else {
        
        //save new info in pet table
        $query = "UPDATE pet SET name = ?, species = ?, breed = ?, birthday = ?, likes = ?, dislikes = ?, otherInformation = ? 
            WHERE petID = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssssi", $name, $species, $breed, $birthday, $likes, $dislikes, $other, $id);

        if ($stmt->execute()) {

            $stmt->close(); //close statement
            header("Location: ../mypets.php");
            
        } else {
            echo "<p>There was an error processing your request, please try again.</p>";
        }
    }
    

    
    
 
}
