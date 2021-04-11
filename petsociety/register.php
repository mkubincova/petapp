<?php include "partials/header.php" ?>

<main>
    <h3>Register</h3>
    <form action="" method="post">
        <input name="username" type="text" placeholder="*Username"><br>
        <input name="fname" type="text" placeholder="First name"><br>
        <input name="lname" type="text" placeholder="Last name"><br>
        <input name="email" type="email" placeholder="Email address"><br>
        <input name="password" type="password" placeholder="*Password"><br>
        <input type="submit" value="Register">
    </form>
</main>


<?php
//check if they filled at least username and password
if (isset($_POST["username"]) && isset($_POST["password"])) {

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
        header("Location: /awa/petsociety/login.php");
    } else {
        echo "<p>The registration failed, please try again.</p>";
    }

    $stmt->close();
}; ?>

<?php include "partials/footer.php" ?>