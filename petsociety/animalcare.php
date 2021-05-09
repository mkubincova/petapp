<?php include "partials/header.php"; ?>

<?php if ($_SESSION) { ?>
    <main>
        <div>
            <h2 class="animalcare-h1">Animal Care</h2>

            <?php if ($_SESSION["userType"] == "admin") { ?>
                <form method="post">
                    <button type="submit" name="addbtn">Add Animal <img class="icon" src="img/icons/add-yellow.png"></button>
                </form>
            <?php }

            if (isset($_POST["addbtn"])) {
                header("Location: animalcare-add.php");
            }

            $query = "SELECT * FROM animal";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            
            ?>

            <div class="pet-container">

            <?php  while ($row = $result->fetch_assoc()) { ?>
                <div class="single-animal">
                    <a href="animalcare-single.php?id=<?=$row["animalID"]?>">
                        <img src="img/<?=$row["imgUrl"]?>">
                    </a>
                    <p>
                        <a href="animalcare-single.php?id=<?=$row["animalID"]?>"><?=$row["species"]?></a>
                    </p>
                </div>
            <?php }; ?>

            </div>
        </div>
    </main>

<?php } else {
    header("Location: login.php");
} ?>

<?php include "partials/footer.php"; ?>