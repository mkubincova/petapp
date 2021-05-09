<?php 
ob_start();
include "partials/header.php"; 
?>

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
            $owner = "";
            $printedProfile = false;
            $petId = "";
            $imgUrl = "";


            while ($row = $result->fetch_assoc()) {
                //save all owners of the pet in array (each row has ['userID'])
                $owners[] = $row["userID"];

                //check if we already printed profile info (if not print it)
                //since we are getting pets by id, the data will be the same for every row - we only need it once
                //only owner will be diffrent because there can be multiple ones for the same pet (user_pet table)
                if (!$printedProfile) {
                    $printedProfile = true;
                    $petId = $row["petID"];
                    $imgUrl =  $row["imgUrl"];
            ?>
                    <div class="all-info">

                        <div class="img-upperinfo">
                            <img src="img/<?= $imgUrl ?>"></img><br>
                            <div class="upperinfo">
                                <h1><?= $row["name"] ?></h1>
                                <p><span class="bold">Species:</span> <?= $row["species"] ?></p>

                                <?php if (!empty($row["breed"])) { ?>
                                    <p><span class="bold">Breed:</span> <?= $row["breed"] ?></p>
                                <?php } ?>

                                <?php if (!empty($row["birthday"]) && $row["birthday"] != "0000-00-00") { ?>
                                    <p><span class="bold">Birthday:</span> <?= $row["birthday"] ?></p>
                                <?php } ?>

                            </div>
                        </div>

                        <div class="lower-info">
                            <div class="likes-dislikes">

                                <?php if (!empty($row["likes"])) { ?>
                                    <p class="likes"><span class="bold">Likes <img class="icon" src="img/icons/likes.png"><br></span><?= $row["likes"] ?></p>
                                <?php } ?>

                                <?php if (!empty($row['dislikes'])) { ?>
                                    <p class="dislikes"><span class="bold">Dislikes <img class="icon" src="img/icons/dislikes.png"><br></span><?= $row["dislikes"] ?></p>
                                <?php } ?>

                            </div>

                            <?php if (!empty($row['otherInformation'])) { ?>
                                <p class="other-info"><span class="bold">About me <img class="icon" src="img/icons/info.png"><br></span><?= $row["otherInformation"] ?></p>
                            <?php } ?>

                        </div>
                    </div>
                <?php }
            }

            //check if user is one of the owners
            if (in_array($_SESSION["userId"], $owners)) {
                $owner = $_SESSION["userId"];
                //show options to manipulate the pet 
                ?>
                <div class="single-edit-delete-btns">
                    <form action="petprofiles-edit.php?id=<?=$id?>" method="post">
                        <button class="edit" name="editbtn" type="submit">Edit Pet Profile <img class="icon" src="img/icons/edit.png"></button>
                    </form>
                    <form method="post">
                        <button class="delete" type="submit" name="delete">Delete this profile <img class="icon" src="img/icons/delete.png"></button>
                    </form>
                </div>
            <?php } ?>

            <?php if ($owner == $_SESSION['userId']) { ?>
                <div class="owners">
                    <h3>Does this pet have more owners?</h3>
                    <p>Add them here using their email address:</p>
                    <form action="partials/owner-add.php" method="POST">
                        <input type="hidden" name="petId" value="<?= $petId ?>">
                        <input type="email" name="new-owner" placeholder="another@owner.com">
                        <button class="add-pet">Add owner <img class="icon" src="img/icons/add-owner.png"></button>
                    </form>
                </div>

                <div class="owners">
                    <h3>This is not my pet!</h3>
                    <form action="partials/owner-delete.php" method="POST">
                        <input type="hidden" name="petId" value="<?= $petId ?>">
                        <input type="hidden" name="userId" value="<?= $owner ?>">
                        <button class="delete">Delete from my pets <img class="icon" src="img/icons/delete.png"></button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php
//DELETE PET PROFILE
if (isset($_POST["delete"])) {

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
            unlink("img/" . $imgUrl);
            header("Location: mypets.php");
        }
    }
}
ob_end_flush();
?>

<?php include "partials/footer.php" ?>