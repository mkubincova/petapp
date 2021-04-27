<?php 
    include 'partials/header.php'; 
    include 'partials/img-upload.php';
?>

<?php if ($_SESSION) { ?>
    <main>
        <form method="post" enctype="multipart/form-data">
            <textarea name="text" type="text"></textarea><br>
            <input name="img" type="file"><br>
            <input type="submit" name="postbtn" value="Post">
        </form>
        
    </main>
    <?php } else {
        header("Location: login.php");
    } ?>

    <?php


        //Get data from comment & user table and save in array

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

        $query = "SELECT post.postID AS postID, post.userID AS post_userID, post.text AS text, post.timestamp AS timestamp, post.imgUrl AS imgUrl, user.userID AS userID, user.username AS username
        FROM post
        INNER JOIN user ON post.userID = user.userID
        ORDER BY post.postID DESC";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $postIdArray[] = $row['postID'];
            
            echo "<p>" . $row['text'] . "</p>";
            if ($row['imgUrl'] !== NULL) {
                echo "<img src='img/" . $row['imgUrl'] . "'/>";
            }
            echo "<p>Posted by " . $row['username'] . "</p>";
            echo "<p>" . $row['timestamp'] . "</p>";

            if ($row['post_userID'] == $_SESSION['userId'] || $_SESSION['username'] == 'admin') {
                echo '<form method="post"><button class="delete btn-small" name="deletepost' . $row['postID'] . '">Delete this post</button></form>';
            }

            foreach ($comments as $comment) {
                if ($comment['comment_postID'] == $row['postID']) {
                    echo "<p>" . $comment['text'] . "</p>";
                    echo "<p>Posted by " . $comment['username'] . "</p>";
                    echo "<p>" . $comment['timestamp'] . "</p>";

                    if ($comment['comment_userID'] == $_SESSION['userId'] || $_SESSION['username'] == 'admin') {
                        echo '<form method="post"><button class="delete btn-small" name="deletecomment' . $comment['commentID'] . '">Delete this comment</button></form>';
                    }
                    
                }
            }

            echo "<form method='post'><textarea name='commenttext' type='text'></textarea><br>";
            echo "<button name='commentbtn" . $row['postID'] . "'>Comment</button></form>";

        }

        $stmt->close();


        //Add new post
        if (isset($_POST["postbtn"])) {
            if (!empty($_POST["text"])) {

                if ($_FILES['img']['name'] !== '') {

                    //save img to img folder & send back location or error
                    $imgUrl = uploadImg($_FILES['img'], 'feed', false);

                    //check if the img was saved, if yes continue saving data to db
                    if (substr($imgUrl, 0, 5) == 'Error') {

                        echo $imgUrl; //Error: Your image is too big!

                    } else {

                    $text = $_POST['text'];

                    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

                    $query = "INSERT INTO post (text, userID, imgUrl) VALUES (?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("sis", $text, $_SESSION['userId'], $imgUrl);
                    
                    if ($stmt->execute()) {
                        $stmt->close();
                        header("Location: feed.php");
                    }

                }

                } else {
                    $text = $_POST['text'];

                    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

                    $query = "INSERT INTO post (text, userID) VALUES (?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("si", $text, $_SESSION['userId']);
                    
                    if ($stmt->execute()) {
                        $stmt->close();
                        header("Location: feed.php");
                    }
                    
                }


                
            } else {
                echo "Please write something to publish the post.";
            }
        }


        //Delete post
        foreach ($postIdArray as $postID) {
            if (isset($_POST["deletepost" . $postID])) {
                //Delete from comment table
                $query = "DELETE FROM comment WHERE postID = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $postID);
                
                $stmt->execute();
                $stmt->close();

                //Select the imgUrl in order to unlink the image
                $query = "SELECT * FROM post WHERE postID = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $postID);
                $stmt->execute();
                $result = $stmt->get_result();
    
                while ($row = $result->fetch_assoc()) {
                    $imgUrl = $row['imgUrl'];
                };

                $stmt->close();

                //Delete from post table and unlink image
                $query = "DELETE FROM post WHERE postID = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $postID);
                
                if ($stmt->execute()) {
                    unlink('img/' . $imgUrl);
                    header("Location: feed.php");
                }

                $stmt->close();
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


        //Delete comment
        foreach ($comments as $comment) {

            if (isset($_POST["deletecomment" . $comment['commentID']])) {
                $query = "DELETE FROM comment WHERE commentID = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $comment['commentID']);

                if ($stmt->execute()) {
                    header("Location: feed.php");
                }

                $stmt->close();
            }
        }


    ?>

<?php include 'partials/footer.php'; ?>