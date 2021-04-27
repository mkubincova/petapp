<?php 
    include 'partials/header.php'; 
    include 'partials/img-upload.php';
?>

<?php if ($_SESSION && $_SESSION["userType"] == 'admin') { ?>
    <main>
        <!-- Add file upload -->
        <form method="post" enctype="multipart/form-data">
            <label>Species*</label><br>
            <textarea cols="30" rows="2" name="species" type="text"></textarea><br>
            <label>Facts*</label><br>
            <textarea cols="30" rows="3" name="facts" type="text"></textarea><br>
            <label>Characteristics</label><br>
            <textarea cols="30" rows="3" name="characteristics" type="text"></textarea><br>
            <label>Average lifespan</label><br>
            <textarea cols="30" rows="2" name="averagelifespan" type="text"></textarea><br>
            <label>Forbidden food</label><br>
            <textarea cols="30" rows="3" name="forbiddenfood" type="text"></textarea><br>
            <label>Profile picture* (must be jpg/jpeg/png and under 5MB)</label><br>
            <input name="img" type="file"><br>
            <input type="submit" name="addbtn" value="Add Animal">
        </form>

    </main>
<?php } else {
    header("Location: login.php");
}  ?>


<?php

    if (isset($_POST["addbtn"])) {
        // Image is also required
        if (!empty($_POST["species"]) && !empty($_POST["facts"]) && $_FILES['img']['name'] !== '') {

            //save img to img folder & send back location or error
            $imgUrl = uploadImg($_FILES['img'], 'animal-care', false);

            //check if the img was saved, if yes continue saving data to db
            if (substr($imgUrl, 0, 5) == 'Error') {

                echo $imgUrl; //Error: Your image is too big!

            } else {

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

                //Add animal in db
                $query = "INSERT INTO animal (species, facts, characteristics, averageLifespan, forbiddenFood, imgUrl) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("ssssss", $species, $facts, $characteristics, $averageLifespan, $forbiddenFood, $imgUrl);
                if($stmt->execute()){
                    header("Location: animalcare.php");

                }
                
                $stmt->close();
            }
        } else {
            echo "The animal couldn't be added. Please fill in species, facts and add an image.";
        }
    }
    
?>


<?php include 'partials/footer.php'; ?>