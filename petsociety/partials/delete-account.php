<?php
include '../configurations/config.php';
include '../configurations/db-connection.php';

$id = $_SESSION["userId"];

//delete account from user_pet table
$query = "DELETE FROM user_pet WHERE userId = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    //delete account from user table
    $query2 = "DELETE FROM user WHERE userID = ?";
    $stmt2 = $db->prepare($query2);
    $stmt2->bind_param("i", $id);

    if ($stmt2->execute()) {
        $stmt2->close();
        header("Location: logout.php");
    }
}
?>