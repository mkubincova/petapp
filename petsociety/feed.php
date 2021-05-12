<?php
include "partials/header.php";
include "partials/img-upload-form.php";
//header() not working without this
ob_start();
?>

<?php if ($_SESSION) { ?>
    <main>
        <h2>Feed</h2>
        <form class="post-form" method="post" enctype="multipart/form-data">
            <textarea class="post-input emoji" name="text" type="text" style="display:none"></textarea><br>
            <div class="post-buttons">
                <input name="img" type="file"><br>
                <button type="submit" name="postbtn">Create a post <img class="icon" src="img/icons/add-yellow.png"></button>
            </div>
        </form>
        <p class="error"></p>
    <?php } else {
    header("Location: login.php");
} ?>

    <?php
    /* GET ALL POSTS AND COMMENT FROM DB (display operations)*/

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
        $postIdArray[] = $row["postID"]; ?>

        <div class="post-container">

            <!-- Post (left) -->
            <div class="single-post">
                <p class="post-text"><?= $row["text"] ?></p>

                <!-- If there is post image, display it -->
                <?php if ($row["imgUrl"] !== NULL) { ?>
                    <img src="img/<?= $row["imgUrl"] ?>" />
                <?php } ?>

                <div class="author-time-delete">
                    <p class="author-timestamp"><?= $row['username'] ?></p>
                    <p class="timestamp"><?= $row['timestamp'] ?></p>

                    <!-- If the user created this post (or is admin) show delete button -->
                    <?php if ($row["post_userID"] == $_SESSION["userId"] || $_SESSION["userType"] == "admin") { ?>
                        <form method="post">
                            <button class="btn-small btn-icon" name="deletepost<?= $row["postID"] ?>">
                                <img src="img/icons/delete-orange.png" alt="delete" class="icon">
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>

            <!-- Comments (right) -->
            <div class="all-comments">

                <!-- Loop through comments array dispaly those with matching ID -->
                <?php foreach ($comments as $comment) {
                    if ($comment["comment_postID"] == $row["postID"]) { ?>

                        <div class="single-comment">
                            <p class="comment-text"><?= $comment["text"] ?></p>

                            <div class="author-time-delete-comment">
                                <div class="author-time-comment">
                                    <p class="author-comment"><?= $comment["username"] ?></p>
                                    <p class="timestamp-comment"><?= $comment["timestamp"] ?></p>
                                </div>

                                <!-- If the user created this comment (or is admin) show delete button -->
                                <?php if ($comment["comment_userID"] == $_SESSION["userId"] || $_SESSION["userType"] == "admin") { ?>
                                    <form method="post">
                                        <button type="submit" class="btn-small btn-icon" name="deletecomment<?= $comment["commentID"] ?>">
                                            <img src="img/icons/delete-orange.png" alt="delete" class="icon">
                                        </button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                <?php }
                } ?>

                <div class="post-comment">
                    <form method="post">
                        <textarea class="comment-input emoji" cols="50" rows="2" name="commenttext" type="text" style="display:none"></textarea><br>
                        <button name="commentbtn<?= $row["postID"] ?>">Comment <img class="icon comment" src="img/icons/comment-yellow.png"></button>
                    </form>
                </div>
            </div>
        </div>
    <?php  }
    $stmt->close();
    ?>
    </main>

    <?php
    /* CREATE POST, CREATE COMMENT, DELETE POST, DELETE COMMENT (button-click operations) */

    //CREATE POST
    if (isset($_POST["postbtn"])) {
        if (!empty($_POST["text"])) {

            $text = htmlspecialchars($_POST["text"], ENT_QUOTES, "UTF-8");

            if ($_FILES["img"]["name"] !== "") {
                //save img to img folder & send back location or error
                $imgUrl = uploadImg($_FILES["img"], "feed", false);

                //check if the img was saved, if yes continue saving data to db
                if (substr($imgUrl, 0, 5) == "Error") {
                    echo '<script>document.querySelector(".error").textContent = "' . $imgUrl . '";</script>';
                } else {
                    $query = "INSERT INTO post (text, userID, imgUrl) VALUES (?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("sis", $text, $_SESSION["userId"], $imgUrl);

                    if ($stmt->execute()) {
                        $stmt->close();
                        header("Location: feed.php");
                    }
                }
            } else {
                $query = "INSERT INTO post (text, userID) VALUES (?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("si", $text, $_SESSION["userId"]);

                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: feed.php");
                }
            }
        } else {
            echo '<script>document.querySelector(".error").textContent = "Please write something to publish the post.";</script>';
        }
    }


    //DELETE POST
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
                $imgUrl = $row["imgUrl"];
            };

            $stmt->close();

            //Delete from post table and unlink image
            $query = "DELETE FROM post WHERE postID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $postID);

            if ($stmt->execute()) {
                unlink("img/" . $imgUrl);
                header("Location: feed.php");
            }

            $stmt->close();
        }
    }


    //CREATE COMMENT
    foreach ($postIdArray as $postID) {
        if (isset($_POST["commentbtn" . $postID])) {
            if (!empty($_POST["commenttext"])) {

                $text = htmlspecialchars($_POST["commenttext"], ENT_QUOTES, 'UTF-8');

                $query = "INSERT INTO comment (text, postID, userID) VALUES (?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("sii", $text, $postID, $_SESSION["userId"]);

                if ($stmt->execute()) {
                    header("Location: feed.php");
                }

                $stmt->close();
            } else {
                echo
                '<script>document.querySelector(".error").textContent = "Please write something to publish the comment.";</script>';
            }
        }
    }


    //DELETE COMMENT
    foreach ($comments as $comment) {
        if (isset($_POST["deletecomment" . $comment["commentID"]])) {

            $query = "DELETE FROM comment WHERE commentID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $comment["commentID"]);

            if ($stmt->execute()) {
                header("Location: feed.php");
            }
            $stmt->close();
        }
    } 
    
    ob_end_flush();
    ?>

    <?php include "partials/footer.php"; ?>