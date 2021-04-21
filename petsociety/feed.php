<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>
        <form method="post">
            <!-- Image upload is missing -->
            <textarea name="text" type="text"></textarea><br>
            <input type="submit" name="postbtn" value="Post">
        </form>
        
    </main>
    <?php } else {
        header("Location: login.php");
    } ?>

    <?php

        //Add new post
        if (isset($_POST["postbtn"])) {
            if (!empty($_POST["text"])) {

                $text = $_POST['text'];

                $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

                $query = "INSERT INTO post (text, userID) VALUES (?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("si", $text, $_SESSION['userId']);
                
                if ($stmt->execute()) {
                    header("Location: feed.php");
                }
                
                $stmt->close();
                
            } else {
                echo "Please write something to publish the post.";
            }
        }

        //Fetch all posts & comments and display
        $query = "SELECT post.postID AS post_postid, post.text AS post_text, post.timestamp AS post_timestamp, user.userID AS user_userid, user.username AS user_username, comment.postID AS comment_postid, comment.userID AS comment_userid, comment.text AS comment_text, comment.timestamp AS comment_timestamp
        FROM post
        INNER JOIN user ON post.userID = user.userID
        LEFT JOIN comment ON post.postID = comment.postID
        ORDER BY post.postID DESC";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['post_text'] . "</p>";
            echo "<p>Posted by " . $row['user_username'] . "</p>";
            echo "<p>" . $row['post_timestamp'] . "</p>";
            echo "<form method='post'><textarea name='text' type='text'></textarea><br>";
            echo "<button name='commentbtn" . $row['post_postID'] . "'>Comment</button></form>";
            echo "<p>" . $row['comment_text'] . "</p>";
        }

        $query = "SELECT comment.commentID, user.username
        FROM comment
        INNER JOIN user ON comment.userID=user.userID";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo $row['username'];
                }

    ?>

<?php include 'partials/footer.php'; ?>