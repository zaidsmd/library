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
if (!isset($_GET["item_id"])) {
    header('Location: home.php');
}
$item_id = $_GET["item_id"];
$statement= $conn->prepare("SELECT * FROM item_unit WHERE id = '$item_id'");
$statement->execute();
$item = $statement->fetchAll();
if ($item[0]["status"] == "déchirer") {
    echo "cette ouvrage est déchirer";
    exit();
}
$statement = $conn->prepare("SELECT COUNT(*) AS COUNT FROM reservations WHERE user_id= '$id' AND TIMESTAMPDIFF(hour,opening_date,CURRENT_TIMESTAMP) < 24 AND id NOT IN (SELECT reservation_id FROM borrowings)");
$statement->execute();
$user_reservation_count = $statement->fetchAll();
$statement = $conn->prepare("SELECT COUNT(*) as count FROM borrowings JOIN reservations r on r.id = borrowings.reservation_id WHERE user_id = '$id' AND closing_date IS NULL ");
$statement->execute();
$user_borrowing_count = $statement->fetchAll();
$user_remaining_reservation = 3 - ($user_borrowing_count[0]["count"] + $user_reservation_count[0]["count"]);
if ($user_remaining_reservation <= 0 ){
    echo "Vous avez dépasser le limit de votre reservations et emprunts";
    exit();
}
$statement = $conn->prepare("SELECT COUNT(*) AS COUNT FROM reservations WHERE item_unit_id= '$item_id' AND TIMESTAMPDIFF(hour,opening_date,CURRENT_TIMESTAMP) < 24 AND id NOT IN (SELECT reservation_id FROM borrowings)");
$statement->execute();
$item_reservation_count = $statement->fetchAll();
$statement = $conn->prepare("SELECT COUNT(*) as count FROM borrowings JOIN reservations r on r.id = borrowings.reservation_id WHERE item_unit_id = '$item_id' AND closing_date IS NULL ");
$statement->execute();
$item_borrowing_count = $statement->fetchAll();
$item_availability = $item_borrowing_count[0]["count"]+$item_reservation_count[0]["count"];
if ($item_availability > 0 ){
    echo "Cette ouvrage est reservée ou emprunté";
    exit();
}
$statement = $conn->prepare("INSERT INTO reservations (opening_date, user_id, item_unit_id) VALUES (CURRENT_TIMESTAMP,'$id','$item_id')");
$statement->execute();
echo "Votre réservation a été effectuée avec succès";
exit();


