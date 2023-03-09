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
if (!isset($_GET["set"])) {
    header('Location: my_borrowings.php?set=active');
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
        <link rel="stylesheet" href="../css/borrowings.css">
        <title>Mes emprunts</title>
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
                <a href="my_reservations.php" class="nav-link">
                    <div>
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Mes reservations</span>
                    </div>
                </a>
                <a href="my_borrowings.php" class="nav-link active">
                    <div>
                        <i class="fa-solid fa-box-archive"></i>
                        <span>Mes emprunt</span>
                    </div>
                </a>
            </div>
        </aside>
        <section class="content">
            <h1>Mes reservations</h1>
            <form action="" method="get">
                <a href="my_borrowings.php?set=active"
                   class="btn  <?php if ($_GET["set"] === "active") echo "active" ?>">Active</a>
                <a href="my_borrowings.php?set=historique"
                   class="btn <?php if ($_GET["set"] === "historique") echo "active" ?> ">Historique</a>
            </form>
            <div class="card-container">
                <?php
                //-----------------------------------------------------------------------------------------

                $query = "SELECT i.picture,
                                 i.title,
                                 i.language,
                                 i.author,
                                 iu.status,
                                 r.item_unit_id,
                                 r.user_id,
                                 borrowings.opening_date,
                                 borrowings.closing_date,
                                 borrowings.opening_user_id,
                                 borrowings.closing_user_id,
                                 u.name
                                 FROM borrowings
                                          INNER JOIN reservations r on borrowings.reservation_id = r.id
                                          INNER JOIN item_unit iu on r.item_unit_id = iu.id
                                          INNER JOIN item i on iu.item_id = i.id
                                          INNER JOIN users u on borrowings.opening_user_id
                                 where user_id = '$id'";
                //-----------------------------------------------------------------------------------------
                if ($_GET["set"] == "active") {
                    $query .= " AND closing_date is null";
                } else {
                    $query .= " AND closing_date is not null";
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
    </body>
    </html>
<?php
function emprunt($query, $conn)
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
            <div class="card">
                <div class="card-content">
                    <div class="card-img">
                        <img src="../pictures/<?= $borrowing["picture"] ?>" alt="">
                    </div>
                    <div class="card-body">
                        <div class="info">
                            <p class="title"><?= $borrowing["title"] ?></p>
                            <p>Status: <?= $borrowing["status"] ?></p>
                            <p>Langue: <?= $borrowing["language"] ?></p>
                            <p>Oeuvre id: <?= $borrowing["item_unit_id"] ?></p>
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
                <div class="time <?php
                if ($interval->format('%h') < 5) {
                    echo "red";
                } elseif ($interval->format('%h') == 0 || $borrowing["closing_date"] != "") {
                    echo "gray";
                } else {
                    echo "green";
                }
                ?>">
                    <i class="fa-solid fa-stopwatch"></i>
                    <p><?= $interval->format('%d') . "j" . "\n" . $interval->format('%h') . "h" ?></p>
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
