<?php include 'partials/header.php'; ?>
<?php if ($_SESSION) { ?>
    <main>
        <div>
            <?php
            $query = "SELECT * FROM animal";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<a href='animalcare-single.php?id=" . $row['animalID'] . "'><img src='img/animal-care/" . $row['imgUrl'] . "'></a>";
            };
            ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>