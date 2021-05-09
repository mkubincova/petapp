<?php
ob_start();
//check query parameters for animal id
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
$imgUrl = "";
?>

<?php
include "partials/header.php";
?>

<?php if ($_SESSION) { ?>
    <main>

        <div class="animalcare-single-container">

            <?php
            $query = "SELECT * FROM animal WHERE animalID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $imgUrl = $row["imgUrl"]; ?>

                <div class="animalinfo-container">
                    <img src="img/<?= $imgUrl ?>">
                    <h2><?= $row["species"] ?></h2>

                    <div class="animalinfo-text">
                        <p class="facts">
                            <span class="bold">Facts <img class="icon" src="img/icons/fact.png"></span><br>
                            <?= $row['facts'] ?>
                        </p>

                        <?php if (!empty($row["characteristics"])) { ?>
                            <p class="characteristics">
                                <span class="bold">Characteristics<img class="icon characteristics" src="img/icons/characteristics.png"></span><br>
                                <?= $row["characteristics"] ?>
                            </p>
                        <?php }

                        if (!empty($row["averageLifespan"])) { ?>
                            <p class="average-lifespan">
                                <span class="bold">Average Lifespan <img class="icon" src="img/icons/lifespan.png"></span><br>
                                <?= $row["averageLifespan"] ?>
                            </p>
                        <?php }

                        if (!empty($row["forbiddenFood"])) { ?>
                            <p class="forbidden-food">
                                <span class="bold">Forbidden Food <img class="icon" src="img/icons/forbidden-food.png"></span><br>
                                <?= $row["forbiddenFood"] ?>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            <?php };

            if ($_SESSION["userType"] == "admin") { ?>
                <div class="animalsingle-btn-container">
                    <form action="animalcare-edit.php?id=<?=$id?>" method="post">
                        <button class="edit" type="submit" name="editbtn">Edit Animal <img class="icon" src="img/icons/edit.png"></button>
                    </form>
                    <form method="post">
                        <button class="delete" type="submit" name="deletebtn">Delete Animal <img class="icon" src="img/icons/delete.png"></button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include "partials/footer.php"; ?>


<?php

//DELETE & EDIT BUTTON
if (isset($_POST["deletebtn"])) {

    $query = "DELETE FROM animal WHERE animalID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        unlink("img/" . $imgUrl);
        header("Location: animalcare.php");
    }
} 
ob_end_flush();

?>