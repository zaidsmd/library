<?php
session_start();
if (!isset($_SESSION['sign'])) {
    header('Location: login.php');
}
include "dbconfig.php";
$id = $_SESSION["id"];
$statement = $conn->prepare("SELECT * FROM users WHERE id = '$id'");
$statement->execute();
$result = $statement->fetchAll();
if ($result[0]["role"] == "user") {
    header('Location: home.php');
}
$item_id= $_GET["id"];

if (!isset($_GET["status"])){
    $title = $_GET["title"];
    $author = $_GET["author"];
    $type = $_GET["type"];
    $language = $_GET["language"];
    $release_date = date('Y',strtotime($_GET["release_date"]));
    $page_count = $_GET["page_count"];
    $statement = $conn->prepare("UPDATE item SET title='$title' ,author='$author',type='$type',language='$language',release_date='$release_date',page_count='$page_count' WHERE id = '$item_id'");
    $statement->execute();
}else{
    $status = $_GET["status"];
    $brought_date = $_GET["brought_date"];
    $statement = $conn->prepare("UPDATE item_unit SET brought_date = '$brought_date', status = '$status' WHERE id = '$item_id'");
    $statement->execute();
    $statement= $conn->prepare("SELECT item_id FROM item_unit WHERE id = '$item_id'");
    $statement->execute();
    $result = $statement->fetchAll();
    $item_id = $result[0]["item_id"];
}
header("Location: item_unit.php?id=$item_id");
exit();