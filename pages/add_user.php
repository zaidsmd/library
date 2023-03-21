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
    header('Location: index.php');
}

$random = generateRandomString(8);
$password = md5($random);
if (isset($_GET["password"])) {
    $user=$_GET["id"];
    $statement = $conn->prepare("UPDATE users SET password = '$password' WHERE id = '$user'");
    $statement->execute();
    header("Location: users.php?response=done&password=$random");
    exit();
}
$name =trim( $_GET["name"]);
$las_name = trim($_GET["last_name"]);
$identity_card = trim($_GET["identity_card_number"]);
$birthday = $_GET["birthday"];
$type = $_GET["type"];
$phone_number = trim($_GET["phone_number"]);
$email = $_GET["email"];
$username =strtolower($_GET["username"]);



// check for same username in db
$statement = $conn->prepare("SELECT id FROM users WHERE username = '$username'");
$statement->execute();
$result= $statement->fetchAll();
if (count($result)!=0){
    header("Location: users.php?response=username");
    exit();
}
//----------------------------------------
// check for same username in db
$statement = $conn->prepare("SELECT id FROM users WHERE phone_number = '$phone_number'");
$statement->execute();
$result= $statement->fetchAll();
if (count($result)!=0){
    header("Location: users.php?response=nb");
    exit();
}
//----------------------------------------
// check for same username in db
$statement = $conn->prepare("SELECT id FROM users WHERE identity_card_number = '$identity_card'");
$statement->execute();
$result= $statement->fetchAll();
if (count($result)!=0){
    header("Location: users.php?response=cin");
    exit();
}
//----------------------------------------
// check for same username in db
$statement = $conn->prepare("SELECT id FROM users WHERE email = '$email'");
$statement->execute();
$result= $statement->fetchAll();
if (count($result)!=0){
    header("Location: users.php?response=email");
    exit();
}
//----------------------------------------
$statement = $conn->prepare("INSERT INTO users (username, name, last_name, identity_card_number, birthday, type, phone_number, email, password, creation_date, tickets, role, creator_id) 
VALUES
    (
     '$username',
     '$name',
     '$las_name',
     '$identity_card',
     '$birthday',
     '$type',
     '$phone_number',
     '$email',
     '$password',
     CURRENT_TIMESTAMP,
     0,
     'user',
     '$id'
    )");
$statement->execute();
header("Location: users.php?response=done&password=".$random);
exit();
function generateRandomString($length = 10): string
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
