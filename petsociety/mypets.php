<?php include 'partials/header.php'; ?>

<?php
$facts_url = "http://localhost/awa/api/facts.php";

$client = curl_init($facts_url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true); //return transfer as a string

$response = curl_exec($client);
$facts_array = json_decode($response, true);

//check how many facts are in array (-1 because arrays start from 0)
$max_index = count($facts_array) - 1; 
?>

<main>
    <h3>Did you know?</h3>
    
    <?php
    //if there is a fact array, display random fact from it
    if ($facts_array) {
        $random_index = rand(0, $max_index);
        $show_fact = $facts_array[$random_index]["text"];
        echo "<h1>$show_fact</h1>";
    }
    ?>
</main>

<?php include 'partials/footer.php'; ?>