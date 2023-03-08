<?php
session_start();
include "dbconfig.php";
$id = $_SESSION["id"];
$statement = $conn->prepare("SELECT * FROM users WHERE `id` = '$id' ");
$statement->execute();
$result = $statement->fetchAll();
if (!isset($_SESSION["sign"])) {
    header('Location: login.php ');
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
    <title>Library Accueil</title>
</head>
<body>
<?php include "../components/navbar.php" ?>
<?php
if (!isset($_GET["type"]) && !isset($_GET["search"])){
    ?>
    <div class="search-section">
        <div class="right">
            <h1>TOP LIVRES ET TENDANCES</h1>
                <div class="relative input">
                    <input type="text" id="search"
                           name="search"
                           class="block px-2.5 py-2.5 w-full appearance-none "
                           placeholder="Rechercher"/>
                    <label for="search"
                           class="hidden">Rechercher</label>
                    <div class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
        </div>
        <div class="left card-container">
            <?php
            $query = "SELECT * FROM item ORDER BY id DESC LIMIT 3";
            cards($query,$conn);
            ?>
        </div>
    </div>
<?php
}
?>
<div class="filter-bar">
    <form action="" method="get">
      <div class="select">
          <label for="language" class="hidden">language</label>
          <select name="language" id="language">
              <option value="0">Langue</option>
              <option value="french">Français</option>
              <option value="english">English</option>
              <option value="arabic">Arabe</option>
          </select>
          <div class="arrow">
              <i class="fa-solid fa-chevron-down"></i>
          </div>
      </div>
        <div class="select">
            <label for="type" class="hidden">Type</label>
            <select name="type" id="type">
                <option value="0">Type</option>
                <option value="livre">Livre</option>
                <option value="revues">Revues</option>
                <option value="cassettes vidéo">Cassettes vidéo</option>
                <option value="CDs">CDs</option>
                <option value="DVDs">DVDs</option>
            </select>
            <div class="arrow">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" value="Rechercher">
    </form>
</div>
<div class="card-container">
    <?php
    $query ="SELECT * FROM item WHERE 1";
    cards($query,$conn);
    ?>
</div>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
function cards($query , $conn){
    $statement = $conn->prepare($query);
    $statement->execute();
    $items = $statement->fetchAll();
    if (count($items) > 0) {
        foreach ($items as $item) {
            ?>
            <a class="card-link" href="item.php?id=<?=$item["id"]?>">
                <div class="card">
                    <div class="card-img">
                        <img src="../pictures/<?=$item["picture"]?>" alt="">
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <p class="title"><?=$item["title"]?></p>
                            <p class="author"><?=$item["author"]?></p>
                        </div>
                        <div class="card-footer">
                            <?=$item["release_date"]?>
                        </div>
                    </div>
                </div>
            </a>
        <?php }
    }else {
        echo "<p>nothing found</p>";
    }
};



