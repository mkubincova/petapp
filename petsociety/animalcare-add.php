<?php include 'partials/header.php'; ?>

<?php if ($_SESSION && $_SESSION["userType"] == 'admin') { ?>
    <main>
        <!-- Is missing file upload -->
        <form method="post">
            <label>Species</label><br>
            <input name="species" type="text"><br>
            <label>Facts</label><br>
            <input name="facts" type="text"><br>
            <label>Characteristics</label><br>
            <input name="characteristics" type="text"><br>
            <label>Average lifespan</label><br>
            <input name="averagelifespan" type="text"><br>
            <label>Forbidden food</label><br>
            <input name="forbiddenfood" type="text"><br>
            <input type="submit" name="addbtn" value="Add Animal">
        </form>

    </main>
<?php } else {
    header("Location: login.php");
}  ?>


<?php include 'partials/footer.php'; ?>


<?php

    if (isset($_POST["addbtn"])) {
        // Image must also be required here
        if (!empty($_POST["species"]) && !empty($_POST["facts"])) {

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
            $query = "INSERT INTO animal (species, facts, characteristics, averageLifespan, forbiddenFood) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sssss", $species, $facts, $characteristics, $averageLifespan, $forbiddenFood);
            if($stmt->execute()){
                echo "The animal has been added.";
            }

            $stmt->close();
        } else {
            echo "The animal couldn't be added. Please fill in species and facts.";
        }
    }
    
?>