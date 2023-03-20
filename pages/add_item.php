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
if (isset($_GET["status"])){
    $item_id = $_GET["id"];
    $status = $_GET ["status"];
    $brought_date = $_GET["brought_date"];
    $statement = $conn->prepare("INSERT INTO item_unit (status, brought_date, item_id) VALUES ('$status','$brought_date','$item_id')");
    $statement->execute();
    header("Location: item_unit.php?id=$item_id");
}else{
    $title = $_POST["title"];
    $author = $_POST["author"];
    $type = $_POST["type"];
    $language = $_POST["language"];
    $release_date = date('Y',strtotime($_POST["release_date"]));
    $page_count = $_POST["page_count"];
    $ext = explode('.',$_FILES["picture"]["name"]);
    $filename = preg_replace('/[^a-zA-Z0-9-_.]/','', $title).date("yy-mm-ddHis").".".end($ext);
    move_uploaded_file($_FILES["picture"]["tmp_name"],"../pictures/$filename");
    $statement = $conn->prepare("INSERT INTO item (title, author, type, picture, release_date, language, page_count)
VALUES ('$title', '$author', '$type', '$filename', '$release_date', '$language', '$page_count');");
    $statement->execute();
    header('Location: items.php');

}
exit();
