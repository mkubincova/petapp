<?php include "partials/header.php"; ?>

<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;

//Add owners of the pet in the $owners array
$query = "SELECT user_pet.userID, pet.*
            FROM user_pet 
            LEFT JOIN pet ON user_pet.petID = pet.petID
            WHERE pet.petID = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$owners = array();
$petId = "";


while ($row = $result->fetch_assoc()) {
    //save all owners of the pet in array (each row has ['userID'])
    $owners[] = $row["userID"];
}
$stmt->close();

?>

<?php

if ($_SESSION) { ?>
    <main class="petprofiles-edit-page">
        <div class="petprofile-edit-container">
            <h2>Edit Pet Profile</h2>

            <?php

            //This query is used to display pet info
            $query = "SELECT * FROM pet WHERE pet.petID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();

            //check if user is one of the owners
            //if the user is not one of the owners, they will be redirected to petprofiles.php
            if (in_array($_SESSION["userId"], $owners)) { ?>

                <form method="post" enctype="multipart/form-data">

                    <?php while ($row = $result->fetch_assoc()) {

                        $petId = $row["petID"];
                        $imgUrl =  $row["imgUrl"]; ?>

                        <label class="bold">Name (required):</label>
                        <input name="name" type="text" value="<?= $row["name"] ?>" class="emoji" style="display: none;"><br>

                        <label class="bold">Species (required):</label><br>
                        <input name="species" type="text" value="<?= $row["species"] ?>"><br>

                        <label class="bold">Breed:</label><br>
                        <input name="breed" type="text" value="<?= $row["breed"] ?>"><br>

                        <label class="bold">Birthday:</label><br>
                        <input name="birthday" type="date" value="<?= $row["birthday"] ?>"><br>

                        <label class="bold">Likes:</label><br>
                        <textarea cols="30" rows="3" name="likes" type="text" class="emoji" style="display: none;"><?= $row["likes"] ?></textarea><br>

                        <label class="bold">Dislikes:</label> <br>
                        <textarea cols="30" rows="3" name="dislikes" type="text" class="emoji" style="display: none;"><?= $row["dislikes"] ?></textarea><br>

                        <label class="bold">Other information:</label><br>
                        <textarea cols="30" rows="10" name="other" type="text" class="emoji" style="display: none;"><?= $row["otherInformation"] ?></textarea><br>

                        <img src="img/<?= $imgUrl ?>"></img><br>
                        <input type="hidden" value="<?= $imgUrl ?>" name="oldimg" />
                        <input type="hidden" value="<?= $petId ?>" name="petId" />
                    <?php }
                    $stmt->close(); ?>

                    <p class="edit-img-upload"><span class="bold">Upload new profile picture</span></p>
                    <p class="text-small">*must be jpg/jpeg/png and under 5MB</p>
                    <input name="newimg" type="file" id="newimg" class="image"><br>
                    <input type="hidden" id="imgUrl" name="imgUrl">
                    <input type="submit" class="save" name="editbtn" value="Save changes">

                </form>
            <?php } else {
                header("Location: petprofiles.php");
            } ?>

        </div>

    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php

/* EDIT PETPROFILE */
if (isset($_POST["editbtn"])) {

    if (!empty($_POST["name"]) && !empty($_POST["species"])) {

        //get & sanitize the form data
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
        $species = htmlspecialchars($_POST["species"], ENT_QUOTES, 'UTF-8');
        $breed = htmlspecialchars($_POST["breed"], ENT_QUOTES, 'UTF-8');
        $birthday = htmlspecialchars($_POST["birthday"], ENT_QUOTES, 'UTF-8');
        $likes = htmlspecialchars($_POST["likes"], ENT_QUOTES, 'UTF-8');
        $dislikes = htmlspecialchars($_POST["dislikes"], ENT_QUOTES, 'UTF-8');
        $other = htmlspecialchars($_POST["other"], ENT_QUOTES, 'UTF-8');
        $oldimg = $_POST["oldimg"];
        $id = $_POST["petId"];
        $tempImgUrl = htmlspecialchars($_POST["imgUrl"], ENT_QUOTES, 'UTF-8');

        //check if user uploaded a new profile picture
        if (!empty($tempImgUrl)) {

            //get image path from root
            $tempImgUrl2 = str_replace("../", "", $tempImgUrl);

            //move uploaded image from temp folder to pet-profiles
            $imgUrl = str_replace("../img/temp", "pet-profiles", $tempImgUrl);
            rename($tempImgUrl2, "img/" . $imgUrl);

            //delete old profile picture from img folder
            unlink("img/" . $oldimg);

            //save new infor in pet table
            $query = "UPDATE pet SET name = ?, species = ?, breed = ?, birthday = ?, imgUrl = ?, likes = ?, dislikes = ?, otherInformation = ? 
            WHERE petID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("ssssssssi", $name, $species, $breed, $birthday, $imgUrl, $likes, $dislikes, $other, $id);

            if ($stmt->execute()) {

                $stmt->close(); //close statement
                header("Location: mypets.php");
            } else {
                echo '<p class="error">There was an error processing your request, please try again.</p>';
            }
        } else {

            //save new info in pet table
            $query = "UPDATE pet SET name = ?, species = ?, breed = ?, birthday = ?, likes = ?, dislikes = ?, otherInformation = ? 
            WHERE petID = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param("sssssssi", $name, $species, $breed, $birthday, $likes, $dislikes, $other, $id);

            if ($stmt->execute()) {

                $stmt->close(); //close statement
                header("Location: mypets.php");
            } else {
                echo '<p class="error">There was an error processing your request, please try again.</p>';
            }
        }
    }
}
?>

<?php include "partials/cropping-box.php"; ?>
<?php include "partials/footer.php"; ?>