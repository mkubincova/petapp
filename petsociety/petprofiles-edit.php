<?php include 'partials/header.php'; ?>

<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;

?>

<?php 

    if ($_SESSION) { ?>
    <main>
        <div>

            <?php

            //This query is used to add owners of the pet in the $owners array
            $query = "SELECT user_pet.userID, pet.*
            FROM user_pet 
            LEFT JOIN pet ON user_pet.petID = pet.petID
            WHERE pet.petID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();
            $owners = array();
            $owner = '';
            $petId = '';

            //display petprofile as a form
            echo "<form action='partials/edit-petprofile.php' method='post' enctype='multipart/form-data'>";

            while ($row = $result->fetch_assoc()) {
                //save all owners of the pet in array (each row has ['userID'])
                $owners[] = $row['userID'];
            }
            
            //This query is used to display 
            $query = "SELECT * FROM pet WHERE pet.petID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();

            //check if user is one of the owners
            //if the user is not one of the owners, they will be redirected to petprofiles.php
            if (in_array($_SESSION['userId'], $owners)) {
                $owner = $_SESSION['userId'];

                while ($row = $result->fetch_assoc()) {
                    //check if we already printed profile info (if not print it)
                    //since we are getting pets by id, the data will be the same for every row - we only need it once
                    //only owner will be diffrent because there can be multiple ones for the same pet (user_pet table)

                        $printedProfile = true;
                        $petId = $row['petID'];
                        $imgUrl =  $row['imgUrl'];

                        echo 'Name: <input name="name" type="text" value="' . $row['name'] . '"><br>';
                        echo 'Species: <input name="species" type="text" value="' . $row['species'] . '"><br>';
                        echo 'Breed: <input name="breed" type="text" value="' . $row['breed'] . '"><br>';
                        echo 'Birthday: <input name="birthday" type="date" value="' . $row['birthday'] . '"><br>';
                        echo 'Likes: <br> <textarea cols="30" rows="3" name="likes" type="text">' . $row['likes'] . '</textarea><br>';
                        echo 'Dislikes: <br> <textarea cols="30" rows="3" name="dislikes" type="text">' . $row['dislikes'] . '</textarea><br>';
                        echo 'Other information: <br> <textarea cols="30" rows="10" name="other" type="text">' . $row['otherInformation'] . '</textarea><br>';
                        echo "<img src='img/" . $imgUrl . "'></img><br>";
                        echo "<input type='hidden' value='" . $imgUrl . "' name='oldimg' />";
                        echo "<input type='hidden' value='" . $petId . "' name='petId' />";

                }

                echo "Upload new profile picture: <input name='newimg' type='file' id='newimg'><br>";
                echo "<form method='post' action='artials/create-petprofile.php'><button class='save' name='editbtn'>Save changes</button></form><br>";
                echo '</form>';

            } else {
                header("Location: petprofiles.php");
            }


            ?>
        </div>

    </main>
<?php } else {
    header("Location: login.php");
} ?>