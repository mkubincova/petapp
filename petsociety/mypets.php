<?php include 'partials/header.php';
include 'partials/img-upload.php';?>

<?php
//API implementation
$facts_url = "http://localhost/petapp/api/facts.php";

$client = curl_init($facts_url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true); //return transfer as a string

$response = curl_exec($client);
$facts_array = json_decode($response, true);

//check how many facts are in array (-1 because arrays start from 0)
$max_index = count($facts_array) - 1;


//GET pets that belong to logged user
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
        <h3>Did you know?</h3>

        <?php
        //if there is a fact array, display random fact from it
        if ($facts_array) {
            $random_index = rand(0, $max_index);
            $show_fact = $facts_array[$random_index]["text"];
            echo "<h2>$show_fact</h2>";
        }

        echo "<h1>My pets</h1>";
        echo "<a href='newpet.php'><button>Add pet</button></a>";
        while ($stmt->fetch()) {
            echo "<a href='petprofiles-single.php?id=$id'><img src='img/$imgUrl'></a>";
        }
        ?>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>