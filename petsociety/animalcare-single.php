<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php include 'partials/header.php';
        include 'partials/img-upload.php';

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
                echo "<div class='animalinfo-container'>";
                $imgUrl = $row['imgUrl'];
                echo "<img src='img/" . $row['imgUrl'] . "'>";
                echo "<h2>" . $row['species'] . "</h2>";
                echo "<div class='animalinfo-text'>";
                echo "<p class='facts'><span class='bold'>Facts</span><br>" . $row['facts'] . "</p>";
                if (!empty($row['characteristics'])) {
                    echo "<p class='characteristics'><span class='bold'>Characteristics<br></span>" . $row['characteristics'] . "</p>";
                }
                if (!empty($row['averageLifespan'])) {
                echo "<p class='average-lifespan'><span class='bold'>Average Lifespan<br></span>" . $row['averageLifespan'] . "</p>"; 
                }
                if (!empty($row['forbiddenFood'])) {
                echo "<p class='forbidden-food'><span class='bold'>Forbidden Food<br></span>" . $row['forbiddenFood'] . "</p>";
                }
                echo "</div>";
                echo "</div>";
            };


            if ($_SESSION["userType"] == 'admin') {
                echo '<div class="animalsingle-btn-container">';
                echo '<form method="post"><button class="edit" type="submit" name="editbtn">Edit Animal</button></form>';
                echo '<form method="post"><button class="delete" type="submit" name="deletebtn">Delete Animal</button></form>';
                echo '</div>';

            }

            if (isset($_POST['editbtn'])) {
                header("Location: animalcare-edit.php?id=" . $id);
            } else if (isset($_POST['deletebtn'])) {
                $query = "DELETE FROM animal WHERE animalID = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $stmt->close();
                    unlink('img/' . $imgUrl);
                    header("Location: animalcare.php");
                }
            }


        ?>


        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>