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
if (!isset($_GET["set"])) {
    header('Location: my_reservations.php?set=active');
}
?>
    <!doctype html>
    <html lang="fr">
    <head>
        <?php include "../components/head.php"?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="../css/reservation.min.css">
        <title>Mes reservation</title>
    </head>
    <body>
    <?php
    include "../components/navbar.php"
    ?>
    <main>
        <aside>
            <div class="navbar">
                <h2>Profile</h2>
                <a href="profile.php" class="nav-link">
                    <div>
                        <i class="fa-regular fa-circle-user"></i>
                        <span><?= $result[0]["name"] ?> <?= $result[0]["last_name"] ?></span>
                    </div>
                </a>
                <a href="my_reservations.php" class="nav-link active">
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
            <h1>Mes reservations</h1>
            <form action="" method="get">
                <a href="my_reservations.php?set=active"
                   class="btn  <?php if ($_GET["set"] === "active") echo "active" ?>">Active</a>
                <a href="my_reservations.php?set=complete"
                   class="btn <?php if ($_GET["set"] === "complete") echo "active" ?> ">Complete</a>
                <a href="my_reservations.php?set=0" class="btn <?php if ($_GET["set"] == "0") echo "active" ?>">Non
                    Complete</a>
            </form>
            <div class="card-container">
                <?php
                $query = "SELECT * FROM reservations JOIN item_unit iu on iu.id = reservations.item_unit_id join item i on i.id = iu.item_id WHERE user_id = '$id' ";
                if ($_GET["set"] == "active") {
                    $query .= " AND DATEDIFF(CURTIME(),opening_date) < 24 AND iu.id NOT IN (SELECT reservation_id FROM borrowings)";
                } elseif ($_GET["set"] == 0) {
                    $query .= " AND iu.id NOT IN (SELECT reservation_id FROM borrowings)";
                } else {
                    $query .= " AND iu.id IN (SELECT reservation_id FROM borrowings)";
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
    </body>
    </html>
<?php
function reservations($query, $conn)
{
    $statement = $conn->prepare($query);
    $statement->execute();
    $reservations = $statement->fetchAll();
    if (count($reservations) > 0) {
        foreach ($reservations as $reservation) {
            $date = new DateTime(date('y-m-d H:i:s'));
            $date2 = new DateTime($reservation["opening_date"]);
            $interval = $date->diff($date2);
            $interval_final = 24 - $interval->format('%h');
            if ($interval->format('%d') > 0) {
                $interval_final = 0;
            }
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
                            <p>Langue: <?= $reservation["language"] ?></p>
                            <p>Oeuvre id: <?= $reservation["item_unit_id"] ?></p>
                            <p class="author"><?= $reservation["author"] ?></p>
                        </div>
                        <div class="date">
                            <p><?= $reservation["opening_date"] ?></p>
                        </div>
                    </div>

                </div>
                <div class="time <?php
                if ($interval_final < 3 and $interval_final > 0 ) {
                    echo "red";
                } elseif ($interval_final == 0) {
                    echo "gray";
                } else {
                    echo "green";
                }
                ?>">
                    <i class="fa-solid fa-stopwatch"></i>
                    <p><?= $interval_final . "h" ?></p>
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