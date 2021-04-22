<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>

        <h1>Fill the form to create a profile for your pet :)</h1>

        <form action="partials/create-petprofile.php" method="post" enctype="multipart/form-data">
            Name*: <input name="name" type="text" placeholder="Roxy"><br>
            Species*: <input name="species" type="text" placeholder="Dog"><br>
            Breed: <input name="breed" type="text" placeholder="Corgi"><br>
            Birthday: <input name="birthday" type="date"><br>
            Likes:<br>
            <textarea cols="30" rows="3" name="likes" type="text" placeholder="Long walks on the beach."></textarea><br>
            Dislikes: <br>
            <textarea cols="30" rows="3" name="dislikes" type="text" placeholder="Postmen and thunderstorms."></textarea><br>
            Profile picture* (must be jpg/jpeg/png and under 5MB): <input name="img" type="file" id="img"><br>
            Other information: <br>
            <textarea name="other" cols="30" rows="10" placeholder="Here you can type some interesting things about your pet..."></textarea><br>

            <input type="submit" value="Create profile">
        </form>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php include 'partials/footer.php'; ?>