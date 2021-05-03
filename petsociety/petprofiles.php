<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>
        <h1>Browse Pets</h1>
        <form method="post">
            <input type="text" name="search" class="search-field" placeholder="Search for pets" />
            <button type="submit" name="searchbutton">Search</button>
            <button type="submit" name="showall" class="all-btn">Show all pets</button>
        </form>
        <div class="pet-container">

            <?php

            /* Search function */
            if (isset($_POST['searchbutton'])) {
                $search = $_POST['search'];
                $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
                /* Checks if the name column has any keyword like the one 
        the user typed in and if so it selects it */
                $query = strtolower("SELECT * FROM `pet` WHERE name LIKE ?");
                $stmt = $db->prepare($query);
                $stmt->bind_param("s", $search);
                $stmt->execute();
                $result = $stmt->get_result();
                $num_rows = mysqli_num_rows($result);

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="single-pet">';
                    echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'><img src='img/" . $row['imgUrl'] . "'></a><br>";
                    echo "<p><a href='petprofiles-single.php?id=" . $row['petID'] . "'>" . $row['name'] . "</a></p>";
                    echo '</div>';
                }

                if ($num_rows <= 0) {
                    echo '<p>There are no pets named ' . $search . '.</p>';
                }
            } else {
                $query = "SELECT * FROM pet";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="single-pet">';
                    echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'><img src='img/" . $row['imgUrl'] . "'></a><br>";
                    echo "<p><a href='petprofiles-single.php?id=" . $row['petID'] . "'>" . $row['name'] . "</a></p>";
                    echo '</div>';
                };
            }

            ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>


<?php include 'partials/footer.php'; ?>