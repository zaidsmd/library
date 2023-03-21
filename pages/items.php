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
?>
    <!doctype html>
    <html lang="en">
    <head>
        <?php include "../components/head.php"?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="../css/items.min.css">
        <title>Ouvrages</title>
    </head>
    <body>
    <?php
    include "../components/navbar.php"
    ?>
    <main>
        <?php include "../components/sidebar.php" ?>
        <section class="content">
            <h1>Ouvrages</h1>
            <form action="" method="get">
                <div class="relative input">
                    <input type="text" id="search"
                           name="search"
                           class="block px-2.5 py-2.5 w-full appearance-none "
                           placeholder="Rechercher par titre ou auteur"/>
                    <label for="search"
                           class="hidden">Rechercher par titre ou auteur</label>
                    <button type="submit" class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <button data-modal-target="add-modal" data-modal-toggle="add-modal" class="btn-primary btn"
                        type="button">
                    <span>Ajouter</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
            </form>
            <div class="card-container">
                <?php
                //-----------------------------------------------------------------------
                $query = "SELECT * FROM item WHERE 1";
                //-----------------------------------------------------------------------
                if (isset($_GET["search"])) {
                    $search = $_GET["search"];
                    if ($search != "" or $search != ' ') {
                        $query .= " AND title LIKE '%$search%' OR author LIKE '%$search%'";
                    }
                }
                cards($query, $conn);
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
                        Ajouter un ouvrage
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
                <!-- Modal body -->
                <div class="modal-body">
                    <form action="add_item.php" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            <div class="input mb-4 mt-4">
                                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Titre</label>
                                <input type="text" name="title" id="title" placeholder="Titre"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                            </div>
                            <div class="input mb-4 mt-4">
                                <label for="author"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Auteur</label>
                                <input type="text" name="author" id="author" placeholder="Auteur"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="select-container w-full">
                                <label for="language" class="">Langue</label>
                                <div class="select">
                                    <select name="language" id="language">
                                        <option value="0">Langue</option>
                                        <option value="French">Français</option>
                                        <option value="English">Anglais</option>
                                        <option value="Arabic">Arabe</option>
                                    </select>
                                    <div class="arrow">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="select-container w-full">
                                <label for="type" class="">Type</label>
                                <div class="select">
                                    <select name="type" id="type">
                                        <option value="0">Type</option>
                                        <option value="livre">Livre</option>
                                        <option value="roman">Roman</option>
                                        <option value="revues">Revues</option>
                                        <option value="cassettes vidéo">Cassettes vidéo</option>
                                        <option value="CDs">CDs</option>
                                        <option value="DVDs">DVDs</option>
                                    </select>
                                    <div class="arrow">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="input-group">
                            <div class="input mb-4 mt-4">
                                <label for="release_date"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                    d’edition</label>
                                <input type="date" name="release_date" id="release_date"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                            </div>
                            <div class="input mb-4 mt-4">
                                <label for="page_count"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de
                                    pages</label>
                                <input type="number" name="page_count" id="page_count" placeholder="Nombre de pages"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                       required>
                            </div>
                        </div>
                        <div class="input  mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                   for="file_input">Ouvrage Cover</label>
                            <input required
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                   aria-describedby="file_input_help" id="file_input" type="file" name="picture">
                        </div>
                        <input type="submit" value="Ajouter" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
    </body>
    </html>
<?php
function cards($query, $conn): void
{
    $statement = $conn->prepare($query);
    $statement->execute();
    $items = $statement->fetchAll();
    if (count($items) > 0) {
        foreach ($items as $item) {
            ?>
            <a class="card-link" href="item_unit.php?id=<?= $item["id"] ?>">
                <div class="card">
                    <div class="card-img">
                        <img src="../pictures/<?= $item["picture"] ?>" alt="">
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <p class="title"><?= $item["title"] ?></p>
                            <p class="author"><?= $item["author"] ?></p>
                        </div>
                        <div class="card-footer">
                            <?= $item["release_date"] ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php }
    } else {
        ?>
        <div class="not-found">
            <i class="fa-solid fa-xmark"></i>
            <p>Il n'y a aucun exemplaire trouvées</p>
        </div>
        <?php
    }
}
