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
if (!isset($_GET["set"]) && !isset($_GET["search"])) {
    header('Location: borrowings.php?set=all');
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
        <link rel="stylesheet" href="../css/borrowings.min.css">
        <title>Emprunts</title>
    </head>
    <body>
    <?php
    include "../components/navbar.php"
    ?>
    <main>
        <?php include "../components/sidebar.php" ?>
        <section class="content">
            <h1>Emprunts</h1>
            <form action="" method="get">
                <div class="input btn btn-checkbox <?= ($_GET['set'] == "all") ? "active" : "" ?> ">
                    <label for="all">Tout</label>
                    <input id="all" name="set" value="all"
                           type="checkbox" <?= ($_GET['set'] == "all") ? "checked" : "" ?>>
                </div>
                <div class="input btn btn-checkbox  <?= ($_GET['set'] == "active") ? "active" : "" ?>">
                    <label for="active">Active</label>
                    <input id="active" name="set" value="active"
                           type="checkbox" <?= ($_GET['set'] == "active") ? "checked" : "" ?> >
                </div>
                <div class="input btn btn-checkbox  <?= ($_GET['set'] == "complete") ? "active" : "" ?>">
                    <label for="complete">Complete</label>
                    <input id="complete" name="set" value="complete"
                           type="checkbox" <?= ($_GET['set'] == "complete") ? "checked" : "" ?> >
                </div>
                <div class="input btn btn-checkbox  <?= ($_GET['set'] == "0") ? "active" : "" ?>">
                    <label for="not-complete">Non complete</label>
                    <input id="not-complete" name="set" value="0"
                           type="checkbox" <?= ($_GET['set'] == "0") ? "checked" : "" ?> >
                </div>
                <div class="relative input">
                    <input type="text" id="search"
                           name="search"
                           class="block px-2.5 py-2.5 w-full appearance-none "
                           placeholder="Rechercher avec CIN"/>
                    <label for="search"
                           class="hidden">Rechercher avec CIN</label>
                    <button type="submit" class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
            <div class="card-container">
                <?php
                //-----------------------------------------------------------------------
                $query = "SELECT borrowings.id,
                                 borrowings.opening_date,
                                 borrowings.closing_date,
                                 borrowings.closing_user_id,
                                 borrowings.opening_user_id,
                                 u.identity_card_number,
                                 i.title,
                                 i.author,
                                 iu.status,
                                 i.picture,
                                 r.item_unit_id,
                                 u.name,
                                 u.last_name
                          FROM borrowings
                                   INNER JOIN reservations r on borrowings.reservation_id = r.id
                                   INNER JOIN item_unit iu on iu.id = r.item_unit_id
                                   INNER JOIN item i on i.id = iu.item_id
                                   INNER JOIN users u on u.id = r.user_id
                          WHERE 1";
                //-----------------------------------------------------------------------
                if ($_GET["set"] == 0) {
                    $query .= " AND borrowings.closing_date is null AND TIMESTAMPDIFF(hour,borrowings.opening_date, CURRENT_TIMESTAMP) > 24*15";
                } else if ($_GET["set"] == "complete") {
                    $query .= " AND borrowings.closing_date is not null";
                } else if ($_GET["set"] == "active") {
                    $query .= "  AND borrowings.closing_date is null AND TIMESTAMPDIFF(hour,borrowings.opening_date, CURRENT_TIMESTAMP) < 24*15";
                }
                if (isset($_GET["search"])) {
                    $search = $_GET["search"];
                    if ($search != "" or $search != ' ') {
                        $query .= " AND identity_card_number LIKE '%$search%'";
                    }
                }
                try {
                    emprunt($query, $conn);
                } catch (Exception $e) {
                    echo $e;
                }
                ?>
            </div>
        </section>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
    <script src="../scripts/borrowings.min.js"></script>
    </body>
    </html>
<?php
/**
 * @throws Exception
 */
function emprunt($query, $conn): void
{
    $statement = $conn->prepare($query);
    $statement->execute();
    $reservations = $statement->fetchAll();
    if (count($reservations) > 0) {
        foreach ($reservations as $borrowing) {
            $now_date = new DateTime();
            $opening_date = new DateTime(date('Y-m-d H:i:s', strtotime($borrowing["opening_date"] . '+15 days')));
            $interval = $now_date->diff($opening_date);
            ?>
            <div class="card-form">
                <div class="card">
                    <div class="card-content">
                        <div class="card-img">
                            <img src="../pictures/<?= $borrowing["picture"] ?>" alt="">
                        </div>
                        <div class="card-body">
                            <div class="info">
                                <p class="title"><?= $borrowing["title"] ?></p>
                                <p class="capitalize">Status: <?= $borrowing["status"] ?></p>
                                <p>CIN d'adhérent: <?= $borrowing["identity_card_number"] ?></p>
                                <p>Ouvrage id: <?= $borrowing["item_unit_id"] ?></p>
                                <p class="author"><?= $borrowing["author"] ?></p>
                            </div>
                            <div class="gerent">
                                <p>Gerent id: <?= $borrowing["opening_user_id"] ?></p>
                                <p><?php echo($borrowing["closing_user_id"] != "" ? "Gerent id:" . $borrowing["closing_user_id"] : "") ?></p>
                            </div>
                            <div class="date">
                                <p><?= $borrowing["opening_date"] ?></p>
                                <p><?= $borrowing["closing_date"] ?></p>
                            </div>
                        </div>
                    </div>
                    <button class="time" <?php
                    if (($interval->format('%d') == 0 && $interval->format('%h') == 0) || $borrowing["closing_date"] != "") {
                        echo "disabled";
                    }
                    ?>
                    >
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                </div>
                <div class="form hidden">
                    <form action="close_borrow.php" method="get">
                        <p>CIN d’Adherent: <?=$borrowing["identity_card_number"]?></p>
                        <p>Nom d’Adherent: <?=$borrowing["name"]." ".$borrowing["last_name"]?></p>
                        <div class="input-group">
                            <label for="status" class="hidden"></label>
                            <select name="status" id="status">
                                <option value="neuf">Neuf</option>
                                <option value="bon état">Neuf</option>
                                <option value="acceptable">Acceptable</option>
                                <option value="assez usé">Assez usé</option>
                                <option value="déchiré">Déchiré</option>
                            </select>
                            <?php
                            if (($interval->format('%d') != 0 && $interval->format('%h') != 0) && $borrowing["closing_date"] == "") {
                                echo '<input type="text" name="borrowing_id" class="hidden" value="'.$borrowing["id"].'">';
                            }
                            ?>
                            <input type="submit" class="btn btn-primary" value="Fermer Emprunt">
                        </div>
                    </form>
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
