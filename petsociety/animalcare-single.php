<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php include 'partials/header.php'; ?>

<main>

    <div>

    <?php
        $query = "SELECT * FROM animal WHERE animalId = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<p>Species: ";
            echo $row['species'] . "</p>";
            echo "<p>Facts: ";
            echo $row['facts'] . "</p>";
            echo "<p>Characteristics: ";
            echo $row['characteristics'] . "</p>";
            echo "<p>Average Lifespan: ";
            echo $row['averageLifespan'] . "</p>";
            echo "<p>Forbidden Food: ";
            echo $row['forbiddenFood'] . "</p>";
        };

    ?>


    </div>
</main>

<?php include 'partials/footer.php'; ?>