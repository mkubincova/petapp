<?php include 'partials/header.php'; ?>
<main>

    <div>

        <?php
        $result = mysqli_query($db, "SELECT * FROM `animal`");

        while ($row = mysqli_fetch_array($result)) {
            echo "<a href='" . $row['pageUrl'] . "'><img src='img/" . $row['imgUrl'] . "'></a>";
        }

        ?>

    </div>
</main>



<?php include 'partials/footer.php'; ?>