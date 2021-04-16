<?php include 'partials/header.php'; ?>


<main>
    <!-- search field -->
    <div>

    <?php

        $query = "SELECT * FROM pet";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<div>';
            echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'><img src='img/pet-profiles/" . $row['imgUrl'] . "'></a>";
            echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'>" . $row['name'] . "</a>";
            echo '</div>';
        };

    ?>
    </div>
</main>