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
        header('Location: reservations.php');
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
            <a href="profile.php" class="nav-link active">
                <div>
                    <i class="fa-regular fa-circle-user"></i>
                    <span><?= $result[0]["name"] ?> <?= $result[0]["last_name"] ?></span>
                </div>
            </a>
            <a href="my_reservations.php" class="nav-link">
                <div>
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Mes reservations</span>
                </div>
            </a>
            <a href="my_borrowings.php" class="nav-link">
                <div>
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Mes emprunt</span>
                </div>
            </a>
        </div>
        <a href="logout.php" class="btn btn-danger"><span>Se déconnecter</span> <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </aside>
    <section class="content">
        <h1>Information Personnel</h1>
        <form id="info" action="update_profile.php"  method="get">
            <div class="input-profile">
                <label for="name">Nom et prenom</label>
                <input disabled type="text" name="name" value="<?= $result[0]["name"] ?> <?=$result[0]["last_name"]?>" id="name">
                <button class="hide" disabled type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
            </div>
            <div class="input-profile">
                <label for="username">Nom d'utilisateur</label>
                <input disabled type="text" name="username" value="<?= $result[0]["username"] ?>" id="username">
                <button disabled class="hide" type="button" onclick="this.previousElementSibling.toggleAttribute('disabled'); this.previousElementSibling.focus(); this.classList.toggle('active')" ><i class="fa-regular fa-pen-to-square"></i></button>
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
                <button type="button" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="btn btn-primary" >Sauvegarder</button>
                <button type="button" data-modal-target="password-modal" data-modal-toggle="password-modal" class="btn btn-danger" >Changer le mot de pass</button>
            </div>
        </form>
    </section>
</main>
<!-- verification modal -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-md md:h-auto">
        <!-- Modal content -->
        <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="authentication-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Confirmer vote identification</h3>
                <p class='response hidden'>Le mot de pass est incorrect</p>
                    <div class="input mb-4 mt-4">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Votre mot de pass</label>
                        <input  type="password" name="password" id="password"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    <button type="button" class="btn btn-primary">Confirmer</button>
            </div>
        </div>
    </div>
</div>
<div id="password-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-md md:h-auto">
        <!-- Modal content -->
        <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="password-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Changer votre mot de pass</h3>
                <p class='response hidden'>Le mot de pass est incorrect</p>
                <p class='error response hidden'>Password does not match</p>
                <form action="update_profile.php">
                    <div class="input mb-4 mt-4">
                        <label for="old_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Votre mot de pass actuel</label>
                        <input  type="password" name="old_password" id="old_password"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    <div class="input mb-4 mt-4">
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nouveau mot de pass</label>
                        <input  type="password" name="new_password" id="new_password"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    <div class="input mb-4 mt-4">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmer le mot de pass</label>
                        <input  type="password" name="password_confirmation" id="password_confirmation"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
<script src="../scripts/profile.js" ></script>
</body>
</html>