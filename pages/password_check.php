<?php
session_start();
if (!isset($_SESSION['sign'])){
    echo "You don't have permission to be here!";
    exit();
}
include "dbconfig.php";
$id = $_SESSION["id"];
$password = md5($_GET["password"]);
$statement = $conn->prepare("SELECT password FROM users WHERE id = '$id'");
$statement->execute();
$result = $statement->fetchAll();
if ($password == $result[0]["password"]){
    echo json_encode(true);
    exit();
}else {
    echo json_encode(false);
    exit();
}