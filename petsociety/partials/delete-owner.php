<?php
include '../configurations/config.php';
include '../configurations/db-connection.php';

$petId = $_POST['petId'];
$userId = $_POST['userId'];

//delete ownership record from user_pet table
$query = "DELETE FROM user_pet WHERE petID = ? AND userID = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("ii", $petId, $userId);

if ($stmt->execute()) {
    header("Location: ../mypets.php");
} else {
    echo "Ops, something went wrong, please try again.";
}

$stmt->close();

?>