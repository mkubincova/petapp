<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php include 'partials/header.php'; ?>

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
        }
    ?>
    <main>
        <!-- Is missing file upload -->
        <form method="post">
            <label>Species</label><br>
            <input name="species" type="text" value="<?php echo $species?>"><br>
            <label>Facts</label><br>
            <input name="facts" type="text" value="<?php echo $facts?>"><br>
            <label>Characteristics</label><br>
            <input name="characteristics" type="text" value="<?php echo $characteristics?>"><br>
            <label>Average lifespan</label><br>
            <input name="averagelifespan" type="text" value="<?php echo $averageLifespan?>"><br>
            <label>Forbidden food</label><br>
            <input name="forbiddenfood" type="text" value="<?php echo $forbiddenFood?>"><br>
            <input type="submit" name="savebtn" value="Save Changes">
        </form>

    </main>
<?php } else {
    header("Location: login.php");
}  ?>


<?php include 'partials/footer.php'; ?>


<?php

    if (isset($_POST["savebtn"])) {

        //Get form data
        $speciesInput = $_POST['species'];
        $factsInput = $_POST['facts'];
        $characteristicsInput = $_POST['characteristics'];
        $averageLifespanInput = $_POST['averagelifespan'];
        $forbiddenFoodInput = $_POST['forbiddenfood'];

        //Sanitize data
        $speciesInput = htmlspecialchars($speciesInput, ENT_QUOTES, 'UTF-8');
        $factsInput = htmlspecialchars($factsInput, ENT_QUOTES, 'UTF-8');
        $characteristicsInput = htmlspecialchars($characteristicsInput, ENT_QUOTES, 'UTF-8');
        $averageLifespanInput = htmlspecialchars($averageLifespanInput, ENT_QUOTES, 'UTF-8');
        $forbiddenFoodInput = htmlspecialchars($forbiddenFoodInput, ENT_QUOTES, 'UTF-8');

        //Update animal in db
        $query = "UPDATE animal 
        SET species = ?, facts = ?, characteristics = ?, averageLifespan = ?, forbiddenFood = ?
        WHERE animalID = ?";
    
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssi", $speciesInput, $factsInput, $characteristicsInput, $averageLifespanInput, $forbiddenFoodInput, $id);
        
        if ($stmt->execute()) {
            header("Location: animalcare.php");
        } else {
            echo "<p>Editing failed, please try again.</p>";
        }
               
        $stmt->close();
    }
    
?>