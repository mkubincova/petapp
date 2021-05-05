<?php include 'partials/header.php'; ?>
<?php if ($_SESSION) { ?>
    <main>
        <div>
            <h2 class="animalcare-h1">Animal Care</h2>
            <?php
            if ($_SESSION["userType"] == 'admin') {
                echo '<form method="post"><button type="submit" name="addbtn">Add Animal <img class="icon" src="img/icons/add-yellow.png"></button></form>';
            }

            if (isset($_POST['addbtn'])) {
                header("Location: animalcare-add.php");
            }

            $query = "SELECT * FROM animal";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            echo "<div class='pet-container'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='single-animal'>";
                echo "<a href='animalcare-single.php?id=" . $row['animalID'] . "'><img src='img/" . $row['imgUrl'] . "'></a>";
                echo "<p><a href='animalcare-single.php?id=" . $row['animalID'] . "'>" . $row['species'] . "</a></p>";
                echo "</div>";
            };
            ?>
            </div>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>