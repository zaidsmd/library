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
    if ($result[0]["role"] == "user") {
        header('Location: index.php');
    }
}
if (!isset($_GET["id"])) {
    header('Location: index.php');
}
$item_id = $_GET["id"];
//------------------------------------------------------------------------
$statement = $conn->prepare("SELECT item_id,
                                           item_unit.id,
                                           status,
                                           title,
                                           brought_date,
                                           language,
                                           picture,
                                           author,
                                           type,
                                           page_count,
                                           release_date
                                    FROM item_unit
                                             INNER JOIN item i on item_unit.item_id = i.id
                                    WHERE item_id = '$item_id'");
//-------------------------------------------------------------------------
$statement->execute();
$items = $statement->fetchAll();
if (count($items) == 0) {
    $statement = $conn->prepare("SELECT * FROM item  WHERE id = '$item_id'");
    $statement->execute();
    $items = $statement->fetchAll();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <?php include "../components/head.php"?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/item_unit.min.css">
    <title><?= $items[0]["title"] ?></title>
</head>
<body>
<?php
include "../components/navbar.php";
?>
<main>
    <div class="main-title">
        <a type="button" href="items.php"><i class="fa-solid fa-chevron-left"></i></a>
        <h1><?= $items[0]["title"] ?></h1>
    </div>
    <div class="content">
        <div class="item">
            <div class="item-img">
                <img src="../pictures/<?= $items[0]["picture"] ?>" alt="<?= $items[0]["title"] ?>">
            </div>
            <div class="info">
                <div>
                    <div class="header">
                        <h2 class="title"><?= $items[0]["title"] ?></h2>
                        <span>by: <?= $items[0]["author"] ?></span>
                    </div>
                    <p><?= $items[0]["title"] ?> est <?php echo ($items[0]["type"] == "livre") ? "un" : "une";
                        echo " " . $items[0]["type"] ?> publiée en <?= $items[0]["release_date"] ?></p>
                    <p>Langue: <?= $items[0]["language"] ?></p>
                    <p>Nombre de page: <?= $items[0]["page_count"] ?> page</p>
                </div>
                <button data-id="<?= $item_id ?>" data-modal-target="item-modal" data-modal-toggle="item-modal"
                        class="btn btn-primary">
                    <span>Modifier</span>
                    <i class="fa-solid fa-pen"></i>
                </button>
            </div>
        </div>
        <div class="items">
            <h3>Les exemplaires disponibles</h3>
            <form action="" method="get">
                <button data-modal-target="add-modal" data-modal-toggle="add-modal" class="btn-primary btn"
                        type="button">
                    <span>Ajouter</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
            </form>
            <?php
            if (isset($items[0]["status"])) {
                ?>
                <div class="scroller">
                    <?php
                    ?>
                    <?php
                    if (count($items) > 0) {
                        foreach ($items as $item) {
                            $item_unit_id = $item["id"];
                            ?>
                            <div class="item-unit">
                                <div class="item-unit-img">
                                    <img src="../pictures/<?= $item['picture'] ?>" alt="<?= $item["title"] ?>">
                                </div>
                                <div class="content">
                                    <div class="info">
                                        <p>Numéro d’ouvrage: <?= $item_unit_id ?></p>
                                        <p>Status: <?= $item["status"] ?></p>
                                        <p>Date d’importation: <?= $item["brought_date"] ?></p>
                                    </div>
                                    <button data-modal-target="item-unit-modal" data-modal-toggle="item-unit-modal"
                                            class="btn btn-primary" data-id="<?= $item_unit_id ?>">
                                        <span>Modifier</span>
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                </div>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
                <?php
            } else { ?>
                <div class="not-found">
                    <i class="fa-solid fa-xmark"></i>
                    <p>Il n'y a aucun exemplaire trouvées</p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</main>
<div id="item-modal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                </h3>
                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="item-modal">
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
                <form action="modify_item.php" method="get" enctype="multipart/form-data">
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
                    <input type="text" class="hidden" name="id" value="<?= $item_id ?>">
                    <input type="submit" value="Modifier" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<div id="item-unit-modal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Modifier Exemplaire
                </h3>
                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="item-unit-modal">
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
                <form action="modify_item.php" method="get" enctype="multipart/form-data">
                    <div class="input mb-4 mt-4">
                        <label for="brought_date"
                               class="block mb-2 text-gray-900 dark:text-white">Date d’importation</label>
                        <input type="date" name="brought_date" id="brought_date" placeholder="Date d’importation"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                               required>
                    </div>
                    <div class="select-container w-full">
                        <label for="status" class="">Status</label>
                        <div class="select">
                            <select name="status" id="status">
                                <option value="0">Status</option>
                                <option value="neuf">Neuf</option>
                                <option value="bon état">Bon état</option>
                                <option value="acceptable">Acceptable</option>
                                <option value="assez usé">Assez usé</option>
                                <option value="déchiré">Déchiré</option>
                            </select>
                            <div class="arrow">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="hidden" name="id" value="">
                    <input type="submit" value="Modifier" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<div id="add-modal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ajouter un exemplaire
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
                <form action="add_item.php" method="get" enctype="multipart/form-data">
                    <div class="input mb-4 mt-4">
                        <label for="brought_date"
                               class="block mb-2 text-gray-900 dark:text-white">Date d’importation</label>
                        <input type="date" name="brought_date" id="brought_date" placeholder="Date d’importation"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                               required>
                    </div>
                    <div class="select-container w-full">
                        <label for="status-add" class="">Status</label>
                        <div class="select">
                            <select name="status" id="status-add">
                                <option value="neuf">Neuf</option>
                                <option value="bon état">Bon état</option>
                                <option value="acceptable">Acceptable</option>
                                <option value="assez usé">Assez usé</option>
                                <option value="déchiré">Déchiré</option>
                            </select>
                            <div class="arrow">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="hidden" name="id" value="<?=$item_id?>">
                    <input type="submit" value="Ajouter" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
<script src="../scripts/item_unit.min.js"></script>
</body>
</html>
