<?php include "partials/header.php"; ?>

<?php if ($_SESSION) { ?>
    <main class="newpet-page">
        <div class="addpet-container">

            <h2>Fill the form to create a profile for your pet :)</h2>

            <form action="partials/create-petprofile.php" method="post" enctype="multipart/form-data">
                <label class="bold">Name (required):</label>
                <input name="name" type="text" placeholder="Roxy" class="emoji" style="display: none;"><br>
                
                <label class="bold">Species (required):</label><br>
                <input name="species" type="text" placeholder="Dog"><br>
                
                <label class="bold">Breed:</label><br>
                <input name="breed" type="text" placeholder="Corgi"><br>
                
                <label class="bold">Birthday:</label><br>
                <input name="birthday" type="date"><br>
                
                <label class="bold">Likes:</label><br>
                <textarea cols="30" rows="3" name="likes" type="text" placeholder="Long walks on the beach." class="emoji" style="display: none;"></textarea><br>
                
                <label class="bold">Dislikes:</label> <br>
                <textarea cols="30" rows="3" name="dislikes" type="text" placeholder="Postmen and thunderstorms." class="emoji" style="display: none;"></textarea><br>
                
                <label class="bold">Other information:</label><br>
                <textarea name="other" cols="30" rows="10" placeholder="Here you can type some interesting things about your pet..." class="emoji" style="display: none;"></textarea><br>
                
                <label class="bold">Profile picture (required) </label><br>
                <p class="text-small">*must be jpg/jpeg/png and under 5MB</p>
                <input name="image" type="file" class="image"><br>
                <input type="hidden" id="imgUrl" name="imgUrl">

                <input type="submit" value="Create profile" name="submit">
            </form>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php
if (isset($_POST["submit"])) {
    if (!empty($_POST["name"]) && !empty($_POST["species"]) && !empty($_POST["imgUrl"])) {

        //get & sanitize rest of the form data
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
        $species = htmlspecialchars($_POST["species"], ENT_QUOTES, "UTF-8");
        $breed = htmlspecialchars($_POST["breed"], ENT_QUOTES, "UTF-8");
        $birthday = htmlspecialchars($_POST["birthday"], ENT_QUOTES, "UTF-8");
        $likes = htmlspecialchars($_POST["likes"], ENT_QUOTES, "UTF-8");
        $dislikes = htmlspecialchars($_POST["dislikes"], ENT_QUOTES, "UTF-8");
        $other = htmlspecialchars($_POST["other"], ENT_QUOTES, "UTF-8");
        $tempImgUrl = htmlspecialchars($_POST["imgUrl"], ENT_QUOTES, "UTF-8");

        //get image path from root
        $tempImgUrl2 = str_replace("../", "", $tempImgUrl); 

        //move uploaded image from temp folder to pet-profiles
        $imgUrl = str_replace("../img/temp", "pet-profiles", $tempImgUrl);
        rename($tempImgUrl2, "img/" . $imgUrl);

        //save into pet table
        $query = "INSERT INTO pet (name, species, breed, birthday, imgUrl, likes, dislikes, otherInformation) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ssssssss", $name, $species, $breed, $birthday, $imgUrl, $likes, $dislikes, $other);

        //if the statement was executed, save into user_pet table
        if ($stmt->execute()) {

            $petId = $stmt->insert_id; //get id of the new petprofile
            $userId = $_SESSION["userId"]; //get id of logged user
            $stmt->close(); //close statement so we can have a new query

            //new query
            $query2 = "INSERT INTO user_pet (userID, petID) 
            VALUES (?, ?)";

            $stmt2 = $db->prepare($query2);
            $stmt2->bind_param("ii", $userId, $petId);

            if ($stmt2->execute()) {
                header("Location: ../mypets.php");
            } else {
                echo '<p class="error">There was an error processing your request, please try again.</p>';
            }
        } else {
            echo '<p class="error">There was an error processing your request, please try again.</p>';
        }
    } else {
        echo '<p class="error">You need to submit at least name, species and profile picture!</p>';
    }
}
?>

<?php include "partials/cropping-box.php"; ?>
<?php include "partials/footer.php"; ?>