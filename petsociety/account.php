<?php include 'partials/header.php'; ?>

<?php if ($_SESSION) { ?>
    <main>

    <a href="partials/logout.php"><button>Logout</button></a>

    <!--will have a function to toggle disabled attribute & show "save changes" button-->
    <button class="edit">Edit</button>

    <form action="" method="post">
        <input name="username" type="text" value="<?php echo $_SESSION["username"] ?> " placeholder="Username" size="30"><br>
        <input name="fname" type="text" value="<?php echo $_SESSION["firstName"] ?>" placeholder="First name" size="30"><br>
        <input name="lname" type="text" value="<?php echo $_SESSION["lastName"] ?>" placeholder="Last name" size="30"><br>
        <input name="email" type="email" value="<?php echo $_SESSION["email"] ?>" placeholder="Email address" size="30"><br>
        <input name="password" type="password" value="<?php echo $_SESSION["password"] ?>" placeholder="Password" size="30"><br>
        <!--only visible when editing-->
        <input type="submit" value="Save changes">
    </form>

    <a href="partials/delete-account.php"><button class="delete">Delete account</button></a>
</main>
<?php } else {
    header("Location: login.php");
} ?>


<?php include 'partials/footer.php'; ?>


<?php
//check if at least username and password are set
if (isset($_POST["username"]) && isset($_POST["password"])) {

    //get form data
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id = $_SESSION["userId"];

    //sanitize data
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $fname = htmlspecialchars($fname, ENT_QUOTES, 'UTF-8');
    $lname = htmlspecialchars($lname, ENT_QUOTES, 'UTF-8');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    //create user in db
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
        echo "<p>Editing failed, please try again.</p>";
    }

    $stmt->close();
};?>