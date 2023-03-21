<?php
session_start();
include "dbconfig.php";
if (!isset($_SESSION["sign"])) {
    header('Location: login.php ');
} else {
    $id = $_SESSION["id"];
    $statement = $conn->prepare("SELECT * FROM users WHERE `id` = '$id' ");
    $statement->execute();
    $result = $statement->fetchAll();
    if ($result[0]["role"] == "user") {
        header('Location: index.php');
    }
}
if (!isset($_GET["item_id"])) {
    header('Location: index.php');
}
$item_id = $_GET["item_id"];
$statement = $conn->prepare("INSERT INTO borrowings (opening_user_id, reservation_id) VALUES ('$id','$item_id')");
$statement->execute();
exit();