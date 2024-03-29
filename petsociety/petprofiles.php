<?php include "partials/header.php"; ?>

<?php if ($_SESSION) { ?>
    <main>
        <h2>Browse Pets</h2>
        <form method="post">
            <input type="text" name="search" class="search-field" placeholder="Type a name here..." />
            <button type="submit" name="searchbutton">Search <img class='icon' src='img/icons/search-yellow.png'></button>
            <button type="submit" name="showall" class="all-btn">Show all pets <img class='icon' src='img/icons/pets.png'></button>
        </form>
        <div class="pet-container">

            <?php
            //Search function 
            if (isset($_POST["searchbutton"])) {

                $search = "%" . $_POST["search"] . "%";
                $search = htmlspecialchars($search);

                /* Checks if the name column has any keyword like the one 
                the user typed in and if so it selects it */
                $query = strtolower("SELECT * FROM `pet` WHERE name LIKE ?");
                $stmt = $db->prepare($query);
                $stmt->bind_param("s", $search);
                $stmt->execute();
                $result = $stmt->get_result();
                $num_rows = mysqli_num_rows($result);

                while ($row = $result->fetch_assoc()) { ?>

                    <div class="single-pet">
                        <a href="petprofiles-single.php?id=<?=$row["petID"]?>">
                            <img src="img/<?=$row["imgUrl"]?>">
                        </a><br>
                        <p>
                            <a href="petprofiles-single.php?id=<?=$row["petID"]?>"><?=$row["name"]?></a>
                        </p>
                    </div>

                <?php }

                if ($num_rows <= 0) {
                    echo '<p class="error">There are no pets with this name.</p>';
                }

                //Show all pets
            } else {
                $query = "SELECT * FROM pet";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) { ?>

                    <div class="single-pet">
                        <a href="petprofiles-single.php?id=<?=$row["petID"]?>">
                            <img src="img/<?=$row["imgUrl"]?>">
                        </a><br>
                        <p>
                            <a href="petprofiles-single.php?id=<?=$row["petID"]?>"><?=$row["name"]?></a>
                        </p>
                    </div>

                <?php };
            } ?>

        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>


<?php include "partials/footer.php"; ?>