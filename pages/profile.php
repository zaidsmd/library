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
    if ($result[0]["role"] == "admin") {
        header('Location:');
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../pictures/logo.png" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="https://kit.fontawesome.com/a5fdcae6a3.css" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/design.css">
    <title><?=$result[0]["username"]?></title>
</head>
<body>
<?php
include "../components/navbar.php"
?>
<aside>
    <div class="navbar">
        <h2>Profile</h2>
        <a href="" class="nav-link">
            <div>
                <i class="fa-regular fa-circle-user"></i>
                <span><?=$result[0]["name"]?> <?=$result[0]["last_name"]?></span>
            </div>
        </a>
        <a href="" class="nav-link">
            <div>
                <i class="fa-regular fa-circle-user"></i>
                <span><?=$result[0]["name"]?> <?=$result[0]["last_name"]?></span>
            </div>
        </a>
    </div>
</aside>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
</body>
</html>
