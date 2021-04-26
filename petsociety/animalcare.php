<?php include 'partials/header.php'; ?>
<?php if ($_SESSION) { ?>
    <main>
        <div>
            <?php
            if ($_SESSION["userType"] == 'admin') {
                echo '<form method="post"><button type="submit" name="addbtn">Add Animal</button></form>';
            }

            if (isset($_POST['addbtn'])) {
                header("Location: animalcare-add.php");
            }

            $query = "SELECT * FROM animal";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<a href='animalcare-single.php?id=" . $row['animalID'] . "'><img src='img/" . $row['imgUrl'] . "'></a>";
            };
            ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>