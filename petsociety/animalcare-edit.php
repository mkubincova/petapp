<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;

include 'partials/header.php';
include 'partials/img-upload.php';
?>

<?php if ($_SESSION && $_SESSION["userType"] == 'admin') {

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
        <form method="post" enctype="multipart/form-data">
            <label>Species* (required)</label><br>
            <textarea cols="60" rows="1" name="species" type="text" value=""><?php echo $species ?></textarea><br>
            <label>Facts* (required)</label><br>
            <textarea cols="60" rows="4" name="facts" type="text" value=""><?php echo $facts ?></textarea><br>
            <label>Characteristics</label><br>
            <textarea cols="60" rows="4" name="characteristics" type="text" value=""><?php echo $characteristics ?></textarea><br>
            <label>Average lifespan</label><br>
            <textarea cols="60" rows="1" name="averagelifespan" type="text" value=""><?php echo $averageLifespan ?></textarea><br>
            <label>Forbidden food</label><br>
            <textarea cols="60" rows="4" name="forbiddenfood" type="text" value=""><?php echo $forbiddenFood ?></textarea><br>
            <img src="img/<?php echo $imgUrl; ?>"><br>
            <label>Upload new profile picture:</label>
            <input name="img" type="file" class="image"><br>
            <input type="hidden" id="imgUrl" name="imgUrl">
            <input type="hidden" value="<?php echo $imgUrl; ?>" name="oldimg" />
            <input type="submit" name="savebtn" value="Save Changes">
        </form>

    </main>
<?php } else {
    header("Location: login.php");
}  ?>


<?php

if (isset($_POST["savebtn"])) {
    //Get form data
    $speciesInput = $_POST['species'];
    $factsInput = $_POST['facts'];
    $characteristicsInput = $_POST['characteristics'];
    $averageLifespanInput = $_POST['averagelifespan'];
    $forbiddenFoodInput = $_POST['forbiddenfood'];
    $oldImg = $_POST['oldimg'];

    //Sanitize data
    $speciesInput = htmlspecialchars($speciesInput, ENT_QUOTES, 'UTF-8');
    $factsInput = htmlspecialchars($factsInput, ENT_QUOTES, 'UTF-8');
    $characteristicsInput = htmlspecialchars($characteristicsInput, ENT_QUOTES, 'UTF-8');
    $averageLifespanInput = htmlspecialchars($averageLifespanInput, ENT_QUOTES, 'UTF-8');
    $forbiddenFoodInput = htmlspecialchars($forbiddenFoodInput, ENT_QUOTES, 'UTF-8');
    $tempImgUrl = htmlspecialchars($_POST['imgUrl']);

    //get image path from root
    $tempImgUrl2 = str_replace("../", "", $tempImgUrl);

    //move uploaded image from temp folder to pet-profiles
    $imgUrl = str_replace("../img/temp", "animal-care", $tempImgUrl);
    rename($tempImgUrl2, "img/" . $imgUrl);


            //delete old profile picture from img folder
            unlink('img/' . $oldImg);

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
                echo "<p>Editing failed, please try again.</p>";
            }
        
   

        $query = "UPDATE animal 
                SET species = ?, facts = ?, characteristics = ?, averageLifespan = ?, forbiddenFood = ?
                WHERE animalID = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssi", $speciesInput, $factsInput, $characteristicsInput, $averageLifespanInput, $forbiddenFoodInput, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: animalcare.php");
        } else {
            echo "<p>Editing failed, please try again.</p>";
        }
   
}

?>
<?php include 'partials/cropping-box.php'; ?>
<?php include 'partials/footer.php'; ?>