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
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="../pictures/logo.png" type="image/gif" sizes="16x16">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="../css/users.css">
        <title>Adhérents</title>
    </head>
    <body>
    <?php
    include "../components/navbar.php"
    ?>
    <main>
        <?php include "../components/sidebar.php" ?>
        <section class="content">
            <h1>Adhérents</h1>
            <form class="flex gap-4" action="" method="get">
                <div class="relative input">
                    <input type="text" id="search"
                           name="search"
                           value="<?= $_GET["search"] ?? "" ?>"
                           class="block px-2.5 py-2.5 w-full appearance-none "
                           placeholder="Rechercher avec CIN"/>
                    <label for="search"
                           class="hidden">Rechercher avec CIN</label>
                    <button type="submit" class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
                        class="btn btn-primary">Ajouter <i class="fa-solid fa-plus"></i></button>
            </form>
            <div class="card-container">
                <?php
                //-----------------------------------------------------------------------
                $query = "SELECT * from users WHERE role = 'user'";
                if (isset($_GET["search"])) {
                    $search = $_GET["search"];
                    $query .= " AND identity_card_number LIKE '%$search%'";
                }
                //-----------------------------------------------------------------------
                users($query, $conn);
                ?>
            </div>
        </section>
    </main>
    <div id="add-modal" tabindex="-1" aria-hidden="true"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Ajouter un Adherent
                    </h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="add-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="add_user.php" method="get">
                        <div class="input-group">
                            <div class="input">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                                <input type="text" name="name" id="name" placeholder="Nom"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                                <p class="error">Veuillez saisir un nom valide</p>
                            </div>
                            <div class="input">
                                <label for="last-name"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prenom</label>
                                <input type="text" name="last_name" id="last-name" placeholder="Prenom"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                                <p class="error">Veuillez saisir un prénom valide</p>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input">
                                <label for="username"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                <input type="text" name="username" id="username" placeholder="Username"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                            </div>
                            <div class="input">
                                <label for="cin"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CIN</label>
                                <input type="text" name="identity_card_number" id="cin" placeholder="CIN"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                                <p class="error">Veuillez entrer un cin valide</p>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input">
                                <label for="birthday"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthday</label>
                                <input type="date" name="birthday" id="birthday"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                                <p class="error">error</p>
                            </div>
                            <div class="input">
                                <label for="phone-number"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Numero de
                                    Telephone</label>
                                <input type="text" name="phone_number" id="phone-number"
                                       placeholder="Numero de Telephone"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                                <p class="error">Veuillez entrer un numéro de téléphone valide</p>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
                                <input type="email" name="email" id="email" placeholder="E-mail"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                                <p class="error">Veuillez saisir une adresse e-mail valide</p>
                            </div>
                            <div class="select-container w-full">
                                <label for="type" class="">Type</label>
                                <div class="select">
                                    <select name="type" id="type">
                                        <option value="Étudiant">Étudiant</option>
                                        <option value="Fonctionnaire">Fonctionnaire</option>
                                        <option value="Employé">Employé</option>
                                        <option value="Femme au foyer">Femme au foyer</option>
                                    </select>
                                    <div class="arrow">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <input type="submit" value="Ajouter" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_GET["response"])) {
        ?>
        <button id="modal-btn" type="button" data-modal-target="response-modal" data-modal-toggle="response-modal"
                class="btn btn-primary hidden"></button>
        <div id="response-modal" tabindex="-1" aria-hidden="true"
             data-modal-backdrop="static"
             class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-2xl md:h-auto">
                <!-- Modal content -->
                <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Confirmation d'adhérent
                        </h3>
                    </div>
                    <div class="modal-body">
                        <?php
                        if ($_GET["response"] === "done") {
                            ?>
                            <i class="fa-solid fa-circle-check"></i>
                            <p>Votre adherent compte est cree avec succes</p>
                            <p>Votre mot de pass est: <?= $_GET["password"] ?> </p>
                            <?php
                        } elseif ($_GET["response"] === "cin") {
                            ?>
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <p>CIN est deja utiliser dans un autre compte</p>
                            <?php
                        } elseif ($_GET["response"] === "email") {
                            ?>
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <p>Email est deja utiliser dans un autre compte</p>
                            <?php
                        } elseif ($_GET["response"] === "nb") {
                            ?>
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <p>Numero de telephone est deja utiliser dans un autre compte</p>
                            <?php
                        } elseif ($_GET["response"] === "username") {
                            ?>
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <p>Username est deja utiliser</p>
                            <?php
                        }
                        ?>
                        <a href="users.php" class="btn-primary btn">Fermer</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
    <script src="../scripts/users.js"></script>
    <?php
    if (isset($_GET["response"])) {
        ?>
        <script>
            setTimeout(() => {
                document.querySelector('#modal-btn').click()
            }, 400);
        </script>
        <?php
    }
    ?>
    </body>
    </html>
<?php
function users($query, $conn): void
{
    $statement = $conn->prepare($query);
    $statement->execute();
    $users = $statement->fetchAll();

    if (count($users) > 0) {
        foreach ($users as $user) {
            ?>
            <div class="card">
                <div class="col">
                    <p><?= $user["username"] ?></p>
                    <p><?= $user["identity_card_number"] ?></p>
                    <p><?= $user["birthday"] ?></p>
                </div>
                <div class="col">
                    <p><?= $user["phone_number"] ?></p>
                    <p><?= $user["email"] ?></p>
                    <p><?= $user["creation_date"] ?></p>
                </div>
                <div class="col">
                    <p><?= $user["tickets"] ?></p>
                </div>
                <div class="col">
                    <a href="add_user.php?id=<?= $user['id'] ?>&password=" class="btn btn-primary">Changer le mot de
                        pass</a>
                </div>
            </div>
            <?php
        }

    } else { ?>
        <div class="not-found">
            <i class="fa-solid fa-xmark"></i>
            <p>Il n'y a pas de réservations trouvées</p>
        </div>
        <?php
    }

}
