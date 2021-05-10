<?php include "partials/header.php"; ?>

<main class="register-page">
    <div class="register-container">
        <h2>Register</h2>
        <form action="" method="post">
            <input name="username" type="text" placeholder="Username (required)"><br>
            <input name="fname" type="text" placeholder="First name"><br>
            <input name="lname" type="text" placeholder="Last name"><br>
            <input name="email" type="email" placeholder="Email address"><br>
            <div class="visibility">
                <input name="password" type="password" id="psw" placeholder="Password (required)">
                <img class="icon visibility" id="eye" src="img/icons/eye-open.png" alt="show" onclick="togglePassword()">
            </div><br>
            <p class="text-small">*Your password must be at least 8 characters long and contain at least 1 number</p>
            <input type="submit" name="submit" value="Register">
        </form>
    </div>
</main>


<?php
if (isset($_POST["submit"])) {

    //check if they filled at least username and password, that the password contains at least 8 characters & has a number
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && strlen($_POST["password"]) >= 8 && preg_match("#[0-9]+#", $_POST["password"])) {

        //get & sanitize form data
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
        $fname = htmlspecialchars($_POST["fname"], ENT_QUOTES, 'UTF-8');
        $lname = htmlspecialchars($_POST["lname"], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = md5(htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8'));
        $type = "user";

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

        //if the entered username is not in the array
        if (!in_array($username, $existingUsernames)) {

            //create user in db
            $query = "INSERT INTO user (userType, username, password, firstname, lastname, email) 
            VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $db->prepare($query);
            $stmt->bind_param("ssssss", $type, $username, $password, $fname, $lname, $email);

            //if the statement was executed, redirect user to login page
            if ($stmt->execute()) {
                header("Location: login.php");
            } else {
                echo '<p class="error">The registration failed, please try again.</p>';
            }

            $stmt->close();
        } else {
            echo '<p class="error">There is already a user with this username. Please choose a different one.</p>';
        }

    } else {
        echo '<p class="error">Your password must be at least 8 characters long and contain at least one number.</p>';
    }
}
?>

<?php include "partials/footer.php" ?>