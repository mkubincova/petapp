<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;

include "partials/header.php";
?>

<?php if ($_SESSION && $_SESSION["userType"] == "admin") {

    //Get all data from db to save and put as value text in form
    $query = "SELECT * FROM animal WHERE animalID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $species = $row['species'];
        $facts = $row['facts'];
        $characteristics = $row['characteristics'];
        $averageLifespan = $row['averageLifespan'];
        $forbiddenFood = $row['forbiddenFood'];
        $imgUrl = $row['imgUrl'];
    }
?>
    <main class="animalcare-edit-page">
        <div class="editanimal-container">
            <h2 class="editanimal-h1">Edit Animal</h2>
            <form method="post" enctype="multipart/form-data">
                <label class="bold">Species* (required)</label><br>
                <textarea cols="60" rows="1" name="species" type="text" class="emoji" style="display: none;"><?= $species ?></textarea><br>

                <label class="bold">Facts* (required)</label><br>
                <textarea cols="60" rows="4" name="facts" type="text" class="emoji" style="display: none;"><?= $facts ?></textarea><br>

                <label class="bold">Characteristics</label><br>
                <textarea cols="60" rows="4" name="characteristics" type="text" class="emoji" style="display: none;"><?= $characteristics ?></textarea><br>

                <label class="bold">Average lifespan</label><br>
                <textarea cols="60" rows="1" name="averagelifespan" type="text" class="emoji" style="display: none;"><?= $averageLifespan ?></textarea><br>

                <label class="bold">Forbidden food</label><br>
                <textarea cols="60" rows="4" name="forbiddenfood" type="text" class="emoji" style="display: none;"><?= $forbiddenFood ?></textarea><br>
                <img src="img/<?= $imgUrl ?>"><br>

                <label class="bold">Upload new profile picture</label><br>
                <p class="text-small">*must be jpg/jpeg/png and under 5MB</p>
                <input name="img" type="file" class="image"><br>

                <!-- url of the cropped image -->
                <input type="hidden" id="imgUrl" name="imgUrl">

                <input type="hidden" value="<?= $imgUrl ?>" name="oldimg" />
                <input type="submit" name="savebtn" value="Save Changes">
            </form>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
}  ?>


<?php

if (isset($_POST["savebtn"])) {

    //Get and sanitize form data data
    $speciesInput = htmlspecialchars($_POST["species"], ENT_QUOTES, 'UTF-8');
    $factsInput = htmlspecialchars($_POST["facts"], ENT_QUOTES, 'UTF-8');
    $characteristicsInput = htmlspecialchars($_POST["characteristics"], ENT_QUOTES, 'UTF-8');
    $averageLifespanInput = htmlspecialchars($_POST["averagelifespan"], ENT_QUOTES, 'UTF-8');
    $forbiddenFoodInput = htmlspecialchars($_POST["forbiddenfood"], ENT_QUOTES, 'UTF-8');
    $tempImgUrl = htmlspecialchars($_POST["imgUrl"]);
    $oldImg = $_POST["oldimg"];

    //If there was an image uploaded
    if (!empty($tempImgUrl)) {

        //get image path from root
        $tempImgUrl2 = str_replace("../", "", $tempImgUrl);

        //move uploaded image from temp folder to animal-care
        $imgUrl = str_replace("../img/temp", "animal-care", $tempImgUrl);
        rename($tempImgUrl2, "img/" . $imgUrl);

        //delete old profile picture from img folder
        unlink("img/" . $oldImg);

        //Update animal in db
        $query = "UPDATE animal 
                SET species = ?, facts = ?, characteristics = ?, averageLifespan = ?, forbiddenFood = ?, imgUrl = ?
                WHERE animalID = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ssssssi", $speciesInput, $factsInput, $characteristicsInput, $averageLifespanInput, $forbiddenFoodInput, $imgUrl, $id);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: animalcare.php");
        } else {
            echo '<p class="error">Editing failed, please try again.</p>';
        }

        //If there was no image uploaded
    } else {

        $query = "UPDATE animal 
            SET species = ?, facts = ?, characteristics = ?, averageLifespan = ?, forbiddenFood = ?
            WHERE animalID = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssi", $speciesInput, $factsInput, $characteristicsInput, $averageLifespanInput, $forbiddenFoodInput, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: animalcare.php");
        } else {
            echo '<p class="error">Editing failed, please try again.</p>';
        }
    }
}

?>
<?php include "partials/cropping-box.php"; ?>
<?php include "partials/footer.php"; ?>