<?php
//check query parameters
$id = (isset($_GET["id"])) ? htmlspecialchars($_GET["id"]) : null;
?>

<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>

        <div>

        <?php
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
                    header("Location: animalcare.php");
                }
            }
            
    
            $query = "SELECT * FROM animal WHERE animalID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<img src='img/" . $row['imgUrl'] . "'>";
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