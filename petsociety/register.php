<?php include "partials/header.php";?>

<main class="register-page">
    <div class="register-container">
        <h2>Register</h2>
        <form action="" method="post">
            <input name="username" type="text" placeholder="*Username"><br>
            <input name="fname" type="text" placeholder="First name"><br>
            <input name="lname" type="text" placeholder="Last name"><br>
            <input name="email" type="email" placeholder="Email address"><br>
            <input name="password" type="password" placeholder="*Password"><br> <!--add the eye to toggle password visibilty -->
            <input type="submit" value="Register">
        </form>
    </div>
</main>


<?php
    //check if they filled at least username and password, that the password contains at least 8 characters & has a number
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && strlen($_POST["password"]) >= 8 && preg_match("#[0-9]+#", $_POST["password"])) {

        //get form data
        $username = $_POST['username'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $type = "user";
        $password = $_POST['password'];

        //sanitize data
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $fname = htmlspecialchars($fname, ENT_QUOTES, 'UTF-8');
        $lname = htmlspecialchars($lname, ENT_QUOTES, 'UTF-8');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = md5(htmlspecialchars($password, ENT_QUOTES, 'UTF-8'));

        //create user in db
        $query = "INSERT INTO user (userType, username, password, firstname, lastname, email) 
            VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ssssss", $type, $username, $password, $fname, $lname, $email);

        //if the statement was executed, redirect user to login page
        if ($stmt->execute()) {
            header("Location: login.php");
        } else {
            echo "<p>The registration failed, please try again.</p>";
        }

        $stmt->close();

    } else {
        echo "Your password must be at least 8 characters long and contain at least one number.";
    }

?>

<?php include "partials/footer.php" ?>