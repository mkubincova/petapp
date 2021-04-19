<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>
        <form method="post">
            <input type="text" name="search" />
            <button type="submit" name="searchbutton">Search</button>
            <button type="submit" name="showall">All pets</button>
        </form>
        <div>

            <?php

            /* Search function */
            if (isset($_POST['searchbutton'])) {
                $search = $_POST['search'];
                $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
                /* Checks if the name column has any keyword like the one 
        the user typed in and if so it selects it */
                $query = strtolower("SELECT * FROM `pet` WHERE name LIKE '%$search%'");
                $stmt = $db->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $num_rows = mysqli_num_rows($result);

                while ($row = $result->fetch_assoc()) {
                    echo '<div>';
                    echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'><img src='img/pet-profiles/" . $row['imgUrl'] . "'></a>";
                    echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'>" . $row['name'] . "</a>";
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
                    echo '<div>';
                    echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'><img src='img/pet-profiles/" . $row['imgUrl'] . "'></a>";
                    echo "<a href='petprofiles-single.php?id=" . $row['petID'] . "'>" . $row['name'] . "</a>";
                    echo '</div>';
                };
            }

            ?>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>