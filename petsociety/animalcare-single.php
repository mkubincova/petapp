<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php include 'partials/header.php';
        include 'partials/img-upload.php';

?>

<?php if ($_SESSION) { ?>
    <main>

        <div>

        <?php

            $query = "SELECT * FROM animal WHERE animalID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $imgUrl = $row['imgUrl'];
                echo "<img src='img/" . $row['imgUrl'] . "'>";
                echo "<p><span class='bold'>Species: </span>" . $row['species'] . "</p>";
                echo "<p><span class='bold'>Facts: </span>" . $row['facts'] . "</p>";
                echo "<p><span class='bold'>Characteristics: </span>" . $row['characteristics'] . "</p>";
                echo "<p><span class='bold'>Average Lifespan: </span>" . $row['averageLifespan'] . "</p>";
                echo "<p><span class='bold'>Forbidden Food: </span>" . $row['forbiddenFood'] . "</p>";
            };


            if ($_SESSION["userType"] == 'admin') {
                echo '<form method="post"><button class="edit" type="submit" name="editbtn">Edit Animal</button></form>';
                echo '<form method="post"><button class="delete" type="submit" name="deletebtn">Delete Animal</button></form>';

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