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
        header('Location: index.php ');
        exit();
    }
}
if (isset($_GET["id_item"])) {
    $item_id = $_GET["id_item"];
    $statement = $conn->prepare("SELECT * FROM item_unit WHERE id = '$item_id'");
    $statement->execute();
    $result = $statement->fetchAll();
    $array = ["brought_date" => $result[0]["brought_date"], "status" => $result[0]["status"]];
} else {
    $item_id = $_GET["id"];
    $statement = $conn->prepare("SELECT * FROM item WHERE id = '$item_id'");
    $statement->execute();
    $result = $statement->fetchAll();
    $array = ["title" => $result[0]["title"], "author" => $result[0]["author"], "type" => $result[0]["type"], "language" => $result[0]["language"], "page_count" => $result[0]["page_count"]];
}
echo json_encode($array);