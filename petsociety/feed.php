<?php include 'partials/header.php'; ?>
<?php if ($_SESSION) { ?>
    <main>
        <form method="post">
            <!-- Image upload is missing -->
            <input name="text" type="text"><br>
            <input type="submit" name="postbtn" value="Post">
        </form>
        
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php
    if (isset($_POST["postbtn"])) {

        if (!empty($_POST["text"])) {

            //Get form data
            $text = $_POST['text'];

            //Sanitize data
            $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

            //Add post in db
            $query = "INSERT INTO post (postID, userID, text, timestamp) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iiss", NULL, $_SESSION['userId'], $text, current_timestamp());
            $stmt->execute();

            $stmt->close();
        } else {
            echo "Please write something to create the post.";
        }
    }

?>

<?php include 'partials/footer.php'; ?>