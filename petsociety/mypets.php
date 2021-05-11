<?php 
include "partials/header.php";
?>

<?php
//API IMPLEMENTATION
$facts_url = "http://localhost/awa/api/facts.php"; //url of api

$client = curl_init($facts_url);

//return transfer as a string of the curl_exec instead of outputting it directly
curl_setopt($client, CURLOPT_RETURNTRANSFER, true); 

$response = curl_exec($client);
$facts_array = json_decode($response, true);

//check how many facts are in array (-1 because arrays start from 0)
if ($facts_array) {
    $max_index = count($facts_array) - 1;
}


//GET PETS THAT BELONG TO LOGGED USER
$query = "SELECT user_pet.petID, pet.name, pet.imgUrl
        FROM user_pet 
        LEFT JOIN pet ON user_pet.petID = pet.petID
        WHERE user_pet.userID = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $_SESSION["userId"]);
$stmt->bind_result($id, $name, $imgUrl);
$stmt->execute();

?>


<?php if ($_SESSION) { ?>
    <main>
        <div>
            <?php
            //if there is a facts_array, display random fact from it
            if ($facts_array && !empty($facts_array)) {

                echo '<h5 class="fact-heading">Did you know?</h5>';

                $random_index = rand(0, $max_index);
                $show_fact = $facts_array[$random_index]["text"];
                echo '<h3 class="fact">' . $show_fact . '</h3>';
            }
            ?>

            <a href="petprofiles-add.php">
                <button class="add-pet">
                    Add new pet <img class="icon" src="img/icons/add-yellow.png">
                </button>
            </a><br>

            <h2 class="mypets-heading">My pets</h2>
            <div class="pet-container">

            <?php while ($stmt->fetch()) { ?>

                <div class="single-pet">
                    <a href="petprofiles-single.php?id=<?=$id?>">
                        <img src="img/<?=$imgUrl?>"> 
                    </a>
                    <p>
                        <a href="petprofiles-single.php?id=<?=$id?>"><?=$name?></a>
                    </p>
                </div>

            <?php } ?>

            </div>
        </div>
    </main> 

<?php } else {
    header("Location: login.php");
} ?>

<?php include "partials/footer.php"; ?>