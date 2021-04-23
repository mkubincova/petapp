<?php
include '../configurations/config.php';
include '../configurations/db-connection.php';

$petId = $_POST['petId'];
$email = filter_var($_POST['new-owner'], FILTER_SANITIZE_EMAIL);
$userId = '';

//check if user with this email address exists & get their id
$query = "SELECT userID FROM user WHERE email = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$rowcount = mysqli_num_rows($result);

//check if we have a user with this email address
if ($rowcount > 0) {
    //assign userId to variable for next query
    while ($row = $result->fetch_assoc()) {
        $userId = $row['userID'];
        $stmt->close();

        $query2 = "INSERT INTO user_pet (userID, petID) VALUES (?, ?)";
        $stmt2 = $db->prepare($query2);
        $stmt2->bind_param("ii", $userId, $petId);

        if ($stmt2->execute()) {
            header("Location: ../petprofiles-single.php?id=$petId");
        } else {
            echo "Ops, something went wrong, please try again.";
        }
        
        $stmt2->close();

    }
} else {
    echo "There is no account with this email address";
}





?>