<?php include 'partials/header.php'; ?>
<main>

    <div>

        <?php
        $result = mysqli_query($db, "SELECT * FROM `animal`");

        while ($row = mysqli_fetch_array($result)) {
            echo "<a href='animalcare-single.php?id=" . $row['animalID'] . "'><img src='img/animal-care" . $row['imgUrl'] . "'></a>";
        }

        ?>

    </div>
</main>

<?php include 'partials/footer.php'; ?>