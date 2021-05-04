<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main class="newpet-page">
        <div class="addpet-container">

            <h2>Fill the form to create a profile for your pet :)</h2>

            <form action="partials/create-petprofile.php" method="post" enctype="multipart/form-data">
                <span class="bold">Name*:</span> <input name="name" type="text" placeholder="Roxy"><br>
                <span class="bold">Species*:</span> <input name="species" type="text" placeholder="Dog"><br>
                <span class="bold">Breed:</span> <input name="breed" type="text" placeholder="Corgi"><br>
                <span class="bold">Birthday:</span> <input name="birthday" type="date"><br>
                <span class="bold">Likes:</span><br>
                <textarea cols="30" rows="3" name="likes" type="text" placeholder="Long walks on the beach."></textarea><br>
                <span class="bold">Dislikes:</span> <br>
                <textarea cols="30" rows="3" name="dislikes" type="text" placeholder="Postmen and thunderstorms."></textarea><br>
                <span class="bold">Other information:</span> <br>
                <textarea name="other" cols="30" rows="10" placeholder="Here you can type some interesting things about your pet..."></textarea><br>
                <span class="bold">Profile picture* </span><br>(must be jpg/jpeg/png and under 5MB)</span><br>
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