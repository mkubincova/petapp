<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main class="newpet-page">
        <div class="addpet-container">

            <h2>Fill the form to create a profile for your pet :)</h2>

            <form action="partials/create-petprofile.php" method="post" enctype="multipart/form-data">
                <label class="bold">Name*:</label> <input name="name" type="text" placeholder="Roxy" class="emoji" style="display: none;"><br>
                <label class="bold">Species*:</label><br> <input name="species" type="text" placeholder="Dog"><br>
                <label class="bold">Breed:</label><br> <input name="breed" type="text" placeholder="Corgi"><br>
                <label class="bold">Birthday:</label><br> <input name="birthday" type="date"><br>
                <label class="bold">Likes:</label><br>
                <textarea cols="30" rows="3" name="likes" type="text" placeholder="Long walks on the beach." class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Dislikes:</label> <br>
                <textarea cols="30" rows="3" name="dislikes" type="text" placeholder="Postmen and thunderstorms." class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Other information:</label> <br>
                <textarea name="other" cols="30" rows="10" placeholder="Here you can type some interesting things about your pet..." class="emoji" style="display: none;"></textarea><br>
                <label class="bold">Profile picture* </label><br>(must be jpg/jpeg/png and under 5MB)</span><br>
                <input name="image" type="file" class="image"><br>
                <input type="hidden" id="imgUrl" name="imgUrl">

                <input type="submit" value="Create profile">
            </form>
        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/cropping-box.php'; ?>
<?php include 'partials/footer.php'; ?>