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

    $query = "SELECT post.text, post.timestamp, user.firstname
    FROM post
    INNER JOIN user ON post.userID=user.userID";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<p>" . $row['text'] . "</p>";
        echo "<p>Posted by " . $row['firstname'] . "</p>";
        echo "<p>" . $row['timestamp'] . "</p>";
    }
    
    if (isset($_POST["postbtn"])) {

        if (!empty($_POST["text"])) {

            //Get form data
            $text = $_POST['text'];

            //Sanitize data
            $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

            //Add post in db
            $query = "INSERT INTO post (text, userID) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("si", $text, $_SESSION['userId']);
            if ($stmt->execute()) {
                header("Location: feed.php");
            }

            $stmt->close();
        } else {
            echo "Please write something to create the post.";
        }
    }

?>

<?php include 'partials/footer.php'; ?>