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
            WHERE pet.petID = ? AND user_pet.userID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("ii", $id, $_SESSION['userId']);
            $stmt->execute();

            $result = $stmt->get_result();
            $owner;
            $petId;
            $imgUrl;

            //display petprofile as a form
            echo "<form action='partials/edit-petprofile.php' method='post' enctype='multipart/form-data'>";

            while ($row = $result->fetch_assoc()) {
                //variables i'll need outside of while loop
                $owner = $row['userID'];
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
                echo "<input type='hidden' value='" . $imgUrl ."' name='oldimg' />";
                echo "<input type='hidden' value='" . $petId . "' name='petId' />";

                //only visible when editing
                echo "Upload new profile picture: <input name='newimg' type='file' id='newimg'><br>";
            };
            

            //only visible when editing
            echo '<input type="submit" value="Save changes">';

            echo '</form>';

            //only visible if user is owner of the pet
            if ( $owner == $_SESSION['userId']) {
            echo "<button>Edit</button>";
            echo "<a href='partials/delete-petprofile.php?id=$petId&img=$imgUrl'><button>Delete this profile</button></a>";
            }

            ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>