<?php include 'partials/header.php'; ?>

<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php if ($_SESSION) { ?>
    <main>
        <div>

            <?php

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
            $printedProfile = false;
            $petId = '';
            $imgUrl = '';

            //display petprofile as a form
            echo "<form action='partials/edit-petprofile.php' method='post' enctype='multipart/form-data'>";

            while ($row = $result->fetch_assoc()) {
                //save all owners of the pet in array (each row has ['userID'])
                $owners[] = $row['userID'];

                //check if we already printed profile info (if not print it)
                //since we are getting pets by id, the data will be the same for every row - we only need it once
                //only owner will be diffrent because there can be multiple ones for the same pet (user_pet table)
                if (!$printedProfile) {
                    $printedProfile = true;
                    $petId = $row['petID'];
                    $imgUrl =  $row['imgUrl'];

                    echo 'Name: <input name="name" type="text" value="' . $row['name'] . '"><br>';
                    echo 'Species: <input name="species" type="text" value="' . $row['species'] . '"><br>';

                    if (isset($row['breed'])) {
                        echo 'Breed: <input name="breed" type="text" value="' . $row['breed'] . '"><br>';
                    };
                    if (isset($row['birthday'])) {
                        echo 'Birthday: <input name="birthday" type="date" value="' . $row['birthday'] . '"><br>';
                    };
                    if (isset($row['likes'])) {
                        echo 'Likes: <br> <textarea cols="30" rows="3" name="likes" type="text">' . $row['likes'] . '</textarea><br>';
                    };
                    if (isset($row['dislikes'])) {
                        echo 'Dislikes: <br> <textarea cols="30" rows="3" name="dislikes" type="text">' . $row['dislikes'] . '</textarea><br>';
                    };
                    if (isset($row['otherInformation'])) {
                        echo 'Other information: <br> <textarea cols="30" rows="10" name="other" type="text">' . $row['otherInformation'] . '</textarea><br>';
                    };
                    echo "<img src='img/" . $imgUrl . "'></img><br>";
                    echo "<input type='hidden' value='" . $imgUrl . "' name='oldimg' />";
                    echo "<input type='hidden' value='" . $petId . "' name='petId' />";
                }
                
            };

            //only visible when editing
            echo "Upload new profile picture: <input name='newimg' type='file' id='newimg'><br>";

            //only visible when editing
            echo '<input type="submit" value="Save changes">';

            echo '</form>';

            //check if user is one of the owners
            if (in_array($_SESSION['userId'], $owners)) {
                $owner = $_SESSION['userId'];
                //show options to manipulate the 
                echo "<button>Edit</button>";
                echo "<a href='partials/delete-petprofile.php?id=$petId&img=$imgUrl'><button>Delete this profile</button></a>";
            }

            ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>