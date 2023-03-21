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
if (!isset($_GET["status"]) || !isset($_GET["borrowing_id"]) ) {
    header('Location: index.php');
}
$borrowing_id = $_GET["borrowing_id"];
$status = $_GET["status"];
$statement= $conn->prepare("UPDATE borrowings INNER JOIN reservations r on r.id = borrowings.reservation_id INNER JOIN item_unit iu on iu.id = r.item_unit_id
                                   SET borrowings.closing_user_id = '$id',
                                       borrowings.closing_date    = CURRENT_TIMESTAMP,
                                       iu.status                  = '$status'
                                   WHERE borrowings.id = '$borrowing_id'");
$statement->execute();
header('Location: borrowings.php');
exit();
