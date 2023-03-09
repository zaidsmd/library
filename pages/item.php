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
$item_id = $_GET["id"];
$statement = $conn->prepare("SELECT * FROM item_unit INNER JOIN item i on item_unit.item_id = i.id WHERE item_id = '$item_id'");
$statement->execute();
$items = $statement->fetchAll();
if (count($items)==0){
    $statement = $conn->prepare("SELECT * FROM item  WHERE id = '$item_id'");
    $statement->execute();
    $items = $statement->fetchAll();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://kit.fontawesome.com/a5fdcae6a3.css" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/item.css">
    <title><?= $items[0]["title"]?></title>
</head>
<body>
<?php
include "../components/navbar.php";
?>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
</body>
</html>
