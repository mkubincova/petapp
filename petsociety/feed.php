<?php 
    include 'partials/header.php'; 
    include 'partials/img-upload.php'
?>

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


        //Get all data from comments and save in array

        $comments = array();

        $query = "SELECT comment.commentid AS commentID, comment.postID AS comment_postID, comment.userID AS comment_userID, comment.text AS text, comment.timestamp AS timestamp, user.userID AS user_userID, user.username AS username
        FROM comment
        INNER JOIN user ON comment.userID = user.userID";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }

        $stmt->close();



        //Fetch & display all data from post and comment

        $postIdArray = array();

        $query = "SELECT post.postID AS postID, post.text AS text, post.timestamp AS timestamp, user.userID AS userID, user.username AS username
        FROM post
        INNER JOIN user ON post.userID = user.userID
        ORDER BY post.postID DESC";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['text'] . "</p>";
            echo "<p>Posted by " . $row['username'] . "</p>";
            echo "<p>" . $row['timestamp'] . "</p>";

            foreach ($comments as $comment) {
                if ($comment['comment_postID'] == $row['postID']) {
                    echo "<p>" . $comment['text'] . "</p>";
                    echo "<p>Posted by " . $comment['username'] . "</p>";
                    echo "<p>" . $comment['timestamp'] . "</p>";
                    
                }
            }

            echo "<form method='post'><textarea name='commenttext' type='text'></textarea><br>";
            echo "<button name='commentbtn" . $row['postID'] . "'>Comment</button></form>";

            $postIdArray[] = $row['postID'];

        }

        $stmt->close();


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


        //Add new comment

        foreach ($postIdArray as $postID) {

            if (isset($_POST["commentbtn" . $postID])) {
                if (!empty($_POST["commenttext"])) {
    
                    $text = $_POST['commenttext'];
    
                    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
                    $query = "INSERT INTO comment (text, postID, userID) VALUES (?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("sii", $text, $postID, $_SESSION['userId']);
                    
                    if ($stmt->execute()) {
                        header("Location: feed.php");
                    }
                    
                    $stmt->close();
                    
                } else {
                    echo "Please write something to publish the post.";
                }
            }

        }


    ?>

<?php include 'partials/footer.php'; ?>