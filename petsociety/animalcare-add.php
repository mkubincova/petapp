<?php
include "partials/header.php";
?>

<?php if ($_SESSION && $_SESSION["userType"] == "admin") { ?>
    <main class="animalcare-add-page">
        <div class="addanimal-container">
            <h2 class="addanimal-h1">Add Animal</h2>
            <form method="post" enctype="multipart/form-data">
                <label class="bold">Species (required)</label><br>
                <textarea cols="60" rows="1" name="species" type="text" class="emoji required-emoji-1" style="display: none;"></textarea><br>

                <label class="bold">Facts (required)</label><br>
                <textarea cols="60" rows="4" name="facts" type="text" class="emoji required-emoji-2" style="display: none;"></textarea><br>

                <label class="bold">Characteristics</label><br>
                <textarea cols="60" rows="4" name="characteristics" type="text" class="emoji" style="display: none;"></textarea><br>

                <label class="bold">Average lifespan</label><br>
                <textarea cols="60" rows="1" name="averagelifespan" type="text" class="emoji" style="display: none;"></textarea><br>

                <label class="bold">Forbidden food</label><br>
                <textarea cols="60" rows="4" name="forbiddenfood" type="text" class="emoji" style="display: none;"></textarea><br>

                <label class="bold">Profile picture (required)</label><br>
                <p class="text-small">*must be jpg/jpeg/png and under 5MB</p>
                <input name="img" type="file" class="image"><br>

                <input type="hidden" id="imgUrl" name="imgUrl">
                <input type="submit" name="addbtn" value="Add Animal">
            </form>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
}  ?>


<?php

if (isset($_POST["addbtn"])) {
    // Image is also required
    if (!empty($_POST["species"]) && !empty($_POST["facts"]) && !empty($_POST["imgUrl"])) {

        //Get & sanitize form data
        $species = htmlspecialchars($_POST["species"], ENT_QUOTES, 'UTF-8');
        $facts = htmlspecialchars($_POST["facts"], ENT_QUOTES, 'UTF-8');
        $characteristics = htmlspecialchars($_POST["characteristics"], ENT_QUOTES, 'UTF-8');
        $averageLifespan = htmlspecialchars($_POST["averagelifespan"], ENT_QUOTES, 'UTF-8');
        $forbiddenFood = htmlspecialchars($_POST["forbiddenfood"], ENT_QUOTES, 'UTF-8');
        $tempImgUrl = htmlspecialchars($_POST['imgUrl']);

        //get image path from root
        $tempImgUrl2 = str_replace("../", "", $tempImgUrl);

        //move uploaded image from temp folder to animal-care
        $imgUrl = str_replace("../img/temp", "animal-care", $tempImgUrl);
        rename($tempImgUrl2, "img/" . $imgUrl);

        //Add animal in db
        $query = "INSERT INTO animal (species, facts, characteristics, averageLifespan, forbiddenFood, imgUrl) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssssss", $species, $facts, $characteristics, $averageLifespan, $forbiddenFood, $imgUrl);
        if ($stmt->execute()) {
            header("Location: animalcare.php");
        }

        $stmt->close();
    } else {
        echo '<p class="error">The animal could not be added. Please fill in species, facts and add an image.</p>';
    }
}

?>


<?php include "partials/cropping-box.php"; ?>
<?php include "partials/footer.php"; ?>