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
    <link rel="stylesheet" href="../css/profile..css">
    <title><?= $result[0]["username"] ?></title>
</head>
<body>
<?php
include "../components/navbar.php"
?>
<main>
    <aside>
        <div class="navbar">
            <h2>Profile</h2>
            <a class="nav-link active">
                <div>
                    <i class="fa-regular fa-circle-user"></i>
                    <span><?= $result[0]["name"] ?> <?= $result[0]["last_name"] ?></span>
                </div>
            </a>
            <a class="nav-link">
                <div>
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Mes reservations</span>
                </div>
            </a>
            <a class="nav-link">
                <div>
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Mes emprunt</span>
                </div>
            </a>
        </div>
    </aside>
    <section class="content">
        <h1>Information Personnel</h1>
        <form action="">
            <div class="input-profile">
                <label for="name">Nom et prenom</label>
                <input disabled type="text" name="name" value="<?= $result[0]["name"] ?> <?=$result[0]["last_name"]?>" id="name">
                <button class="hide" disabled type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="input-profile">
                <label for="username">Nom d'utilisateur</label>
                <input disabled type="text" name="username" value="<?= $result[0]["username"] ?>" id="username">
                <button type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="input-profile">
                <label for="phone_number">Numéro de téléphone</label>
                <input disabled type="text" name="phone_number" value="<?= $result[0]["phone_number"] ?>" id="phone_number">
                <button  type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="input-profile">
                <label for="email">E-mail</label>
                <input disabled type="text" name="email" value="<?= $result[0]["email"] ?>" id="email">
                <button type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="input-profile">
                <label for="birthday">Date de naissense</label>
                <input disabled type="text" name="birthday" value="<?= $result[0]["birthday"] ?>" id="birthday">
                <button disabled class="hide" type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="input-profile">
                <label for="identity_card_number">CIN</label>
                <input disabled type="text" name="identity_card_number" value="<?= $result[0]["identity_card_number"] ?>" id="identity_card_number">
                <button disabled type="button" class="hide" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="buttons">
                <button type="button" class="btn btn-primary" >Sauvegarder</button>
                <button type="button" class="btn btn-danger" >Changer le mot de pass</button>
            </div>
        </form>
    </section>
</main>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
</body>
</html>
