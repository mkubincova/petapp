<?php include 'partials/header.php'; ?>

<main>

    <div>

        <?php 
        $result = mysqli_query($db, "SELECT * FROM `animal` WHERE species = 'Dog'");

        while($row = mysqli_fetch_array($result)){
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
        }

        
        ?>

    
    </div>
</main>





<?php include 'partials/footer.php'; ?>