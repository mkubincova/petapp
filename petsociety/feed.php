<?php 
    include 'partials/header.php'; 
    include 'partials/img-upload.php';
    //header() not working without this
    ob_start();
?>

<?php if ($_SESSION) { ?>
    <main>
        <h2>Feed</h2>
        <form class="post-form" method="post" enctype="multipart/form-data">
            <textarea class="post-input" name="text" type="text"></textarea><br>
            <div class="post-buttons">
                <input name="img" type="file"><br>
                <input type="submit" name="postbtn" value="Create a post">
            </div>
        </form>
    
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
            
            echo "<div class='post-container'><div class='single-post'><p class='post-text'>" . $row['text'] . "</p>";
            if ($row['imgUrl'] !== NULL) {
                echo "<img src='img/" . $row['imgUrl'] . "'/>";
            }
            echo "<div class='author-time-delete'><p class='author-timestamp'>" . $row['username'] . "</p>";
            echo "<p class='timestamp'>" . $row['timestamp'] . "</p>";

            if ($row['post_userID'] == $_SESSION['userId'] || $_SESSION['userType'] == 'admin') {
                echo '<form method="post"><button class="delete btn-small" name="deletepost' . $row['postID'] . '">Delete this post</button></form></div></div><div class="all-comments">';
            } else {
                echo "</div></div><div class='all-comments'>";
            }

            foreach ($comments as $comment) {
                if ($comment['comment_postID'] == $row['postID']) {

                    echo "<div class='single-comment'><p class='comment-text'>" . $comment['text'] . "</p>";

                    echo "<div class='author-time-delete-comment'><div class='author-time-comment'><p class='author-comment'>" . $comment['username'] . "</p>";
                    echo "<p class='timestamp-comment'>" . $comment['timestamp'] . "</p></div>";

                    if ($comment['comment_userID'] == $_SESSION['userId'] || $_SESSION['username'] == 'admin') {
                        echo '<form method="post"><input type="submit" value="Delete this comment" class="delete btn-small" name="deletecomment' . $comment['commentID'] . '"></form></div></div>';
                    } else {
                        echo "</div></div>";
                    }   
                }
            }

            echo "<div class='post-comment'><form method='post'><textarea class='comment-input' cols='50' rows='2' name='commenttext' type='text'></textarea><br>";
            echo "<button name='commentbtn" . $row['postID'] . "'>Comment <img class='icon comment' src='img/icons/comment-yellow.png'></button></form></div></div></div>";

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
                            header('Location: feed.php');
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
                        header('Location: feed.php');
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


    ?></main>

<?php include 'partials/footer.php'; ?>