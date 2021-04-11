<?php include "partials/header.php" ?>

<main>
    <h3>Login</h3>
    <form action="" method="post">
        <input name="username" type="text" placeholder="Username"><br>
        <input name="password" type="password" placeholder="Password"><br>
        <input type="submit" value="Login">
    </form>


    <?php
    //user authentication
    if (isset($_POST["username"]) && isset($_POST["password"])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $password = md5(htmlspecialchars($password, ENT_QUOTES, 'UTF-8'));

        $query = "SELECT *
        FROM user
        WHERE username = ? AND password = ? ";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->bind_result($id, $uname, $psw, $type, $fname, $lname, $email);
        $stmt->execute();

        //set session variables
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

        if ($_SESSION) {
            header("Location: /awa/petsociety/index.php");
        } else {
            echo "<p>Your username or password is incorrect! Please try again.</p>";
        }

        $stmt->close();
    };


    ?>
</main>


<?php include "partials/footer.php" ?>