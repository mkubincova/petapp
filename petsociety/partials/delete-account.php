<?php
include '../configurations/config.php';
include '../configurations/db-connection.php';

$id = $_SESSION["userId"];

//delete account by id
$query = "DELETE FROM user WHERE userId = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: logout.php");
}
?>