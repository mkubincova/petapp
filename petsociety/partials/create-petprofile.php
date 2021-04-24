<?php
include 'img-upload.php'; 
include '../configurations/config.php';
include '../configurations/db-connection.php';

if (!empty($_POST['name']) && !empty($_POST['species']) && $_FILES['img']['name'] !== '') {
   
    //save img to img folder & send back location or error
    $imgUrl = uploadImg($_FILES['img'], 'pet-profiles');

    //check if the img was saved, if yes continue saving data to db
    if (substr($imgUrl, 0, 5) == 'Error') {

        echo $imgUrl; //Error: Your image is too big!

    } else {

        //get & sanitize rest of the form data
        $name = htmlspecialchars($_POST['name']);
        $species = htmlspecialchars($_POST['species']);
        $breed = htmlspecialchars($_POST['breed']);
        $birthday = htmlspecialchars($_POST['birthday']);
        $likes = htmlspecialchars($_POST['likes']);
        $dislikes = htmlspecialchars($_POST['dislikes']);
        $other = htmlspecialchars($_POST['other']);

        //save into pet table
        $query = "INSERT INTO pet (name, species, breed, birthday, imgUrl, likes, dislikes, otherInformation) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ssssssss", $name, $species, $breed, $birthday, $imgUrl, $likes, $dislikes, $other);

        //if the statement was executed, save into user_pet table
        if ($stmt->execute()) {

            $petId = $stmt->insert_id; //get id of the new petprofile
            $userId = $_SESSION['userId']; //get id of logged user
            $stmt->close(); //close statement so we can have a new query
            
            //new query
            $query2 = "INSERT INTO user_pet (userID, petID) 
            VALUES (?, ?)";

            $stmt2 = $db->prepare($query2);
            $stmt2->bind_param("ii", $userId, $petId);

            if ($stmt2->execute()) {
                header("Location: ../mypets.php");
            } else {
                echo "<p>There was an error processing your request, please try again.</p>";
            }
        } else {
            echo "<p>There was an error processing your request, please try again.</p>";
        }

        
    }
 
} else {
    echo "<p>You need to submit at least name, species and profile picture!</p>";
}
?>