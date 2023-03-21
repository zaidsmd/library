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
    header('Location: reservations.php?set=all');
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
        <link rel="stylesheet" href="../css/reservation.min.css">
        <title>Reservations</title>
    </head>
    <body>
    <?php
    include "../components/navbar.php";
    ?>
    <main>
        <?php include "../components/sidebar.php" ?>
        <section class="content">
            <h1>Reservations</h1>
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
                $query = "SELECT reservations.id,
                                 opening_date,
                                 u.identity_card_number,
                                 i.title,
                                 i.author,
                                 iu.status,
                                 i.picture,
                                 reservations.item_unit_id
                          FROM reservations
                                   INNER JOIN item_unit iu on reservations.item_unit_id = iu.id
                                   INNER JOIN item i on i.id = iu.item_id
                                   INNER JOIN users u on u.id = reservations.user_id
                          WHERE 1";
                //-----------------------------------------------------------------------
                if ($_GET["set"] === "0") {
                    $query .= " AND reservations.id NOT IN (SELECT reservation_id FROM borrowings)";
                } else if ($_GET["set"] == "complete") {
                    $query .= " AND reservations.id IN (SELECT reservation_id FROM borrowings)";
                } else if ($_GET["set"] == "active") {
                    $query .= " AND TIMESTAMPDIFF(hour,opening_date, CURRENT_TIMESTAMP) < 24 AND reservations.id NOT IN (SELECT reservation_id FROM borrowings)";
                }
                if (isset($_GET["search"])) {
                    $search = $_GET["search"];
                    if ($search != "" or $search != ' ') {
                        $query .= " AND identity_card_number LIKE '%$search%'";
                    }
                }
                try {
                    reservations($query, $conn);
                } catch (Exception $e) {
                    echo $e;
                }
                ?>
            </div>
        </section>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/a5fdcae6a3.js" crossorigin="anonymous"></script>
    <script src="../scripts/reservations.min.js"></script>
    </body>
    </html>
<?php
function reservations($query, $conn): void
{
    $statement = $conn->prepare($query);
    $statement->execute();
    $reservations = $statement->fetchAll();
    if (count($reservations) > 0) {
        foreach ($reservations as $reservation) {
            try {
                $date = new DateTime(date('y-m-d H:i:s'));
            } catch (Exception) {
            }
            try {
                $date2 = new DateTime($reservation["opening_date"]);
            } catch (Exception) {
            }
            $interval = $date->diff($date2);
            $interval_final = 24 - $interval->format('%h');
            if ($interval->format('%d') > 0) {
                $interval_final = 0;
            }
            $reservation_id = $reservation["id"];
            $statement = $conn->prepare("SELECT id FROM borrowings where reservation_id ='$reservation_id'");
            $statement->execute();
            $result = $statement->fetchAll();
            if (count($result) > 0) $active = false; else $active = true;
            ?>
            <div class="card">
                <div class="card-content">
                    <div class="card-img">
                        <img src="../pictures/<?= $reservation["picture"] ?>" alt="">
                    </div>
                    <div class="card-body">
                        <div class="info">
                            <p class="title"><?= $reservation["title"] ?></p>
                            <p>Status: <?= $reservation["status"] ?></p>
                            <p>Ouvrage id: <?= $reservation["item_unit_id"] ?></p>
                            <p>CIN d'Adherent: <?= $reservation["identity_card_number"] ?></p>
                            <p class="author"><?= $reservation["author"] ?></p>
                        </div>
                        <div class="date">
                            <p><?= $reservation["opening_date"] ?></p>
                        </div>
                    </div>

                </div>
                <button <?= ($active && $interval_final != 0) ? "data-id='$reservation_id'" : "disabled" ?>
                        class="time <?= ($active && $interval_final != 0) ? "green" : '' ?>">
                    <i class="fa-solid fa-bookmark"></i>
                </button>
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