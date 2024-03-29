<?php include "partials/header.php" ?>

<main class="login-page">
    <div class="login-container">
    <h2>Login</h2>
        <form action="" method="post">
            <input name="username" type="text" placeholder="Username"><br>
            <input name="password" type="password" placeholder="Password"><br>
            <input type="submit" value="Login">
        </form>
    </div>
</main>

<?php
//user authentication
if (isset($_POST["username"]) && isset($_POST["password"])) {

    //get & sanitize form data
    $username = htmlspecialchars($_POST["username"]);
    $password = md5(htmlspecialchars($_POST["password"]));

    //find user in db
    $query = "SELECT *
        FROM user
        WHERE username = ? AND password = ? ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->bind_result($id, $type, $uname, $psw, $fname, $lname, $email);
    $stmt->execute();

    //set session variables if we found a matching user
    while ($stmt->fetch()) {
        $_SESSION["userId"] = $id;
        $_SESSION["username"] = $uname;
        $_SESSION["password"] = $psw;
        $_SESSION["userType"] = $type;
        $_SESSION["firstName"] = $fname;
        $_SESSION["lastName"] = $lname;
        $_SESSION["email"] = $email;
        $_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
    }

    $stmt->close();

    //if user logged in, redirect them
    if ($_SESSION) {
        header("Location: index.php");
    } else {
        echo '<p class="error">Your username or password is incorrect! Please try again.</p>';
    }

    
};
?>

<?php include "partials/footer.php" ?>