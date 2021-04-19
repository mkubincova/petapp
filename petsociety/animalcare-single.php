<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>

        <div>

            <?php
            $query = "SELECT * FROM animal WHERE animalId = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<p>Species: " . $row['species'] . "</p>";
                echo "<p>Facts: " . $row['facts'] . "</p>";
                echo "<p>Characteristics: " . $row['characteristics'] . "</p>";
                echo "<p>Average Lifespan: " . $row['averageLifespan'] . "</p>";
                echo "<p>Forbidden Food: " . $row['forbiddenFood'] . "</p>";
            };

            ?>


        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>