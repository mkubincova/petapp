<?php include 'partials/header.php'; ?>

<?php
    //check query parameters
    $id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<main>
    <div>

    <?php
        $query = "SELECT * FROM pet WHERE petID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<h1>" . $row['name'] . "</h2>";
            echo "<p>Species: " . $row['species'] . "</p>";
            if ($row['breed'] !== NULL) {echo "<p>Breed: " . $row['breed'] . "</p>";};
            if ($row['birthday'] !== NULL) {echo "<p>Birthday: " . $row['birthday'] . "</p>";};           
            if ($row['likes'] !== NULL) {echo "<p>Likes: " . $row['likes'] . "</p>";};
            if ($row['dislikes'] !== NULL) {echo "<p>Disikes: " . $row['dislikes'] . "</p>";};
            if ($row['otherInformation'] !== NULL) {echo "<p>Other Information: " . $row['otherInformation'] . "</p>";};
            echo "<img src='img/pet-profiles/" . $row['imgUrl'] . "'></img>";
        };

    ?>

    </div>
</main>