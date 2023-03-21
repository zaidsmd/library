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
    <link rel="stylesheet" href="../css/item.min.css">
    <title><?= $items[0]["title"] ?></title>
</head>
<body>
<?php
include "../components/navbar.php";
?>
<main>
    <div class="main-title">
        <a type="button" href="index.php"><i class="fa-solid fa-chevron-left"></i></a>
        <h1><?= $items[0]["title"] ?></h1>
    </div>
    <div class="content">
        <div class="item">
            <div class="item-img">
                <img src="../pictures/<?= $items[0]["picture"] ?>" alt="<?= $items[0]["title"] ?>">
            </div>
            <div class="info">
                <div class="header">
                    <h2 class="title"><?= $items[0]["title"] ?></h2>
                    <span>by: <?= $items[0]["author"] ?></span>
                </div>
                <p><?= $items[0]["title"] ?> est <?php echo ($items[0]["type"] == "livre") ? "un" : "une";
                    echo " " . $items[0]["type"] ?> publiée en <?= $items[0]["release_date"] ?></p>
                <p>Langue: <?= $items[0]["language"] ?></p>
                <p>Nombre de page: <?= $items[0]["page_count"] ?> page</p>
            </div>
            <div class="availability">
                <?php
                if (isset($items[0]["status"])) {
                    ?>
                    <div class="available">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>Disponible</span>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="not-available">
                        <i class="fa-regular fa-circle-xmark"></i>
                        <span>Non-Disponible</span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="items">
            <?php
            if (isset($items[0]["status"])) {
                echo "<h3>Les exemplaires disponibles</h3>"
                ?>
                <div class="scroller">
                    <?php
                    ?>
                    <?php
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
                                <?php
                                $statement = $conn->prepare("SELECT count(*) as count FROM borrowings INNER JOIN reservations r on borrowings.reservation_id = r.id WHERE item_unit_id = '$item_unit_id' AND closing_date is null ");
                                $statement->execute();
                                $count_borrowings = $statement->fetchAll();
                                $statement = $conn->prepare("SELECT count(*) as count FROM reservations WHERE item_unit_id = '$item_unit_id' AND TIMESTAMPDIFF(hour,opening_date,CURRENT_TIMESTAMP) < 24");
                                $statement->execute();
                                $count_reservation = $statement->fetchAll();
                                $count = $count_reservation[0]['count'] + $count_borrowings[0]["count"];
                                if ($count == 0 && $item["status"] != "déchirer") {
                                    ?>
                                    <button data-modal-target="response-modal" data-modal-toggle="response-modal"
                                            class="btn btn-primary" data-id="<?= $item_unit_id ?>">Réserver
                                    </button>
                                    <?php
                                } else {
                                    ?>
                                    <button disabled class="btn btn-primary" data-id="<?= $item_unit_id ?>">Réserver
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</main>
<div id="response-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="modal-content relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Confirmation
                </h3>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6 modal-body">

            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2  rounded-b dark:border-gray-600">
                <button data-modal-hide="response-modal" type="button" class="btn-primary btn w-full">Ok</button>
            </div>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
<script src="../scripts/item.min.js"></script>
</body>
</html>
