<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main class="account-page">
        <div class="account-container">
            <a href="partials/logout.php"><button>Logout</button></a>

            <button class="edit">Edit <img class="icon" src="img/icons/edit.png"></button>

            <form action="" method="post">
                <input name="username" type="text" class="required-1" value="<?= $_SESSION["username"] ?>" placeholder="Username (required)" size="30"><br>
                <input name="fname" type="text" value="<?= $_SESSION["firstName"] ?>" placeholder="First name" size="30"><br>
                <input name="lname" type="text" value="<?= $_SESSION["lastName"] ?>" placeholder="Last name" size="30"><br>
                <input name="email" type="email" value="<?= $_SESSION["email"] ?>" placeholder="Email address" size="30"><br>
                <div class="visibility">
                    <input name="password" type="password" class="required-2" id="psw" value="********" placeholder="Password (required)" size="30">
                    <img class="icon visibility" id="eye" src="img/icons/eye-open.png" alt="show" onclick="togglePassword()">
                </div><br>
                <input type="submit" name="submit" value="Save changes">
            </form>

            <form action="" method="post">
                <p class="text-small">*Don't forget to delete all your pets before deleting the account!</p>
                <button class="delete" type="submit" name="delete" onclick="return deleteAccount()">Delete account <img class="icon" src="img/icons/delete.png"></button>
            </form>

        </div>
    </main>
<?php } else {
    header("Location: login.php");
} ?>

<?php

/* EDIT ACCOUNT DETAILS */

if (isset($_POST["submit"])) {

    //check if at least username and password are set
    if (isset($_POST["username"]) && isset($_POST["password"])) {

        //get & sanitize form data
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
        $fname = htmlspecialchars($_POST["fname"], ENT_QUOTES, 'UTF-8');
        $lname = htmlspecialchars($_POST["lname"], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        
        //if the password is changed from the original value (********), sanitize and hash it, otherwise set it to the current password
        if ($_POST["password"] != "********") {
            $password = md5(htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8'));
        } else {
            $password = $_SESSION["password"];
        }

        $id = $_SESSION["userId"];

        //check the password requirements if the password has been changed
        if (strlen($_POST["password"]) >= 8 && preg_match("#[0-9]+#", $_POST["password"]) || $_POST["password"] == "********") {
            
            //create an array for saving existing usernames from the db
            $existingUsernames = array();

            //select all usernames from the user table
            $query = "SELECT username FROM user";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            //save all usernames in the array
            while ($row = $result->fetch_assoc()) {
                $existingUsernames[] = $row["username"];
            }

            $stmt->close();

            //if the entered username is not in the array or the username is the same as the logged in session, update user
            if (!in_array($username, $existingUsernames) || $username == $_SESSION["username"]) {

                //update user in db
                $query = "UPDATE user 
                        SET username = ?, password = ?, firstname = ?, lastname = ?, email = ?
                        WHERE userID = ?";

                $stmt = $db->prepare($query);
                $stmt->bind_param("sssssi", $username, $password, $fname, $lname, $email, $id);

                //if the statement was executed, reset session variables with new values & reload
                if ($stmt->execute()) {
                    $_SESSION["username"] = $username;
                    $_SESSION["password"] = $password;
                    $_SESSION["firstName"] = $fname;
                    $_SESSION["lastName"] = $lname;
                    $_SESSION["email"] = $email;
                    header("Location: account.php");
                } else {
                    echo '<p class="error">Editing failed, please try again.</p>';
                }
                $stmt->close();

            } else { 
                echo '<p class="error">There is already a user with this username. Please choose a different one.</p>';
            } 
        
        } else {
            echo '<p class="error">Your password must be at least 8 characters long and contain at least 1 number.</p>';
        }
    }
}


/* DELETE ACCOUNT */
if (isset($_POST["delete"])) {

    $id = $_SESSION["userId"];

    //delete user comments
    $comments = "DELETE FROM comment WHERE userId = ?";
    $stmt = $db->prepare($comments);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    
    //select the imgUrl to unlink the image
    $postImg = "SELECT * FROM post WHERE userId = ?";
    $stmt = $db->prepare($postImg);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        unlink("img/" . $row["imgUrl"]);
    }
    $stmt->close();

    //delete user posts
    $posts = "DELETE FROM post WHERE userId = ?";
    $stmt = $db->prepare($posts);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    //delete account from user_pet table
    $query = "DELETE FROM user_pet WHERE userId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    //delete account from user table
    $query2 = "DELETE FROM user WHERE userID = ?";
    $stmt2 = $db->prepare($query2);
    $stmt2->bind_param("i", $id);

    if ($stmt2->execute()) {
        $stmt2->close();
        header("Location: partials/logout.php");
    } else {
        echo '<p class="error">There has been an error processing your request, please try again.</p>';
    }
}

?>

<?php include "partials/footer.php"; ?>