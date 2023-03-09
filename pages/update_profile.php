<?php
session_start();
if (!isset($_SESSION['sign'])) {
    header('Location: login.php');
    exit();
}
include "dbconfig.php";
if (isset($_GET["password_confirmation"])){
    $id = $_SESSION["id"];
    $password = md5($_GET["new_password"]);
    $statement = $conn->prepare("UPDATE users SET password = '$password' WHERE id = '$id'");
    $statement->execute();
    header('Location: profile.php');
    exit();
}else{
    $id = $_SESSION["id"];
    $email = $_GET["email"];
    $phone_number = $_GET["phone_number"];
    $statement = $conn->prepare("UPDATE users SET email = '$email' , phone_number = '$phone_number' WHERE id = '$id'");
    $statement->execute();
    header('Location: profile.php');
    exit();
}