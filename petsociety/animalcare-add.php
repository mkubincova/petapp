<?php
include 'partials/header.php';
include 'partials/img-upload.php';
?>

<?php if ($_SESSION && $_SESSION["userType"] == 'admin') { ?>
    <main class="animalcare-add-page">
        <div class="addanimal-container">
            <h2 class="addanimal-h1">Add Animal</h2>
            <form method="post" enctype="multipart/form-data">
                <label class="bold">Species* (required)</label><br>
                <textarea cols="60" rows="1" name="species" type="text" class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Facts* (required)</label><br>
                <textarea cols="60" rows="4" name="facts" type="text" class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Characteristics</label><br>
                <textarea cols="60" rows="4" name="characteristics" type="text" class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Average lifespan</label><br>
                <textarea cols="60" rows="1" name="averagelifespan" type="text" class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Forbidden food</label><br>
                <textarea cols="60" rows="4" name="forbiddenfood" type="text" class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Profile picture* (must be jpg/jpeg/png and under 5MB)</label><br>
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

        //Get form data
        $species = $_POST['species'];
        $facts = $_POST['facts'];
        $characteristics = $_POST['characteristics'];
        $averageLifespan = $_POST['averagelifespan'];
        $forbiddenFood = $_POST['forbiddenfood'];

        //Sanitize data
        $species = htmlspecialchars($species, ENT_QUOTES, 'UTF-8');
        $facts = htmlspecialchars($facts, ENT_QUOTES, 'UTF-8');
        $characteristics = htmlspecialchars($characteristics, ENT_QUOTES, 'UTF-8');
        $averageLifespan = htmlspecialchars($averageLifespan, ENT_QUOTES, 'UTF-8');
        $forbiddenFood = htmlspecialchars($forbiddenFood, ENT_QUOTES, 'UTF-8');
        $tempImgUrl = htmlspecialchars($_POST['imgUrl']);

        //get image path from root
        $tempImgUrl2 = str_replace("../", "", $tempImgUrl);

        //move uploaded image from temp folder to pet-profiles
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
        echo "The animal couldn't be added. Please fill in species, facts and add an image.";
    }
}

?>


<?php include 'partials/cropping-box.php'; ?>
<?php include 'partials/footer.php'; ?>