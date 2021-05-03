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

                    echo "<div class='all-info'><div class='img-upperinfo'><img src='img/" . $imgUrl . "'></img><br>";

                    echo '<div class="upperinfo"><h1>' . $row['name'] . '</h1>';
                    echo '<p><span class="bold">Species:</span> ' . $row['species'] . '</p>';

                    if (!empty($row['breed'])) {
                        echo '<p><span class="bold">Breed:</span> ' . $row['breed'] . '</p>';
                    };
                    if (!empty($row['birthday'])) {
                        echo '<p><span class="bold">Birthday:</span> ' . $row['birthday'] . '</p></div></div>';
                    };
                    if (!empty($row['likes'])) {
                        echo '<div class="lower-info"><div class="likes-dislikes"><p class="likes"><span class="bold">Likes<br></span> ' . $row['likes'] . '</p>';
                    };
                    if (!empty($row['dislikes'])) {
                        echo '<p class="dislikes"><span class="bold">Dislikes<br></span> ' . $row['dislikes'] . '</p></div>';
                    };
                    if (!empty($row['otherInformation'])) {
                        echo '<p class="other-info"><span class="bold">About me<br></span> ' . $row['otherInformation'] . '</p></div></div>';
                    }
                }
            }


            //check if user is one of the owners
            if (in_array($_SESSION['userId'], $owners)) {
                $owner = $_SESSION['userId'];
                //show options to manipulate the 
                echo "<form method='post'><button class='edit' name='editbtn'>Edit Pet Profile</button></form><br>";
                echo "<a href='partials/delete-petprofile.php?id=$petId&img=$imgUrl'><button class='delete'>Delete this profile</button></a>";
            }

            if (isset($_POST['editbtn'])) {
                header("Location: petprofiles-edit.php?id=" . $id);
            }

            ?>
        </div>

        <?php if ($owner == $_SESSION['userId']) { ?>
            <div>
                <h3>Does this pet have more owners?</h3>
                <p>Add them here using their email addresses:</p>
                <form action='partials/add-owner.php' method="POST">
                    <input type="hidden" name="petId" value="<?php echo $petId ?>">
                    <input type="email" name="new-owner" placeholder="another@owner.com">
                    <input type="submit" value="Add owner">
                </form>
            </div>

            <div>
                <h3>This is not my pet!</h3>
                <form action='partials/delete-owner.php' method="POST">
                    <input type="hidden" name="petId" value="<?php echo $petId ?>">
                    <input type="hidden" name="userId" value="<?php echo $owner ?>">
                    <input type="submit" value="Delete from My pets" class="delete">
                </form>
            </div>
        <?php } ?>
    </main>
<?php } else {
    header("Location: login.php");
} ?>