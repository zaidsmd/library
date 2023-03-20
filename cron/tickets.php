<?php
include "../pages/dbconfig.php";
$statement = $conn ->prepare("SELECT u.id FROM borrowings INNER JOIN reservations r on borrowings.reservation_id = r.id INNER  JOIN users u on r.user_id = u.id
            WHERE TIMESTAMPDIFF(hour ,borrowings.opening_date,CURRENT_TIMESTAMP) BETWEEN 15*24 AND 16*24 AND closing_date IS NULL;");
$statement->execute();
$result = $statement->fetchAll();
if (count($result)>0){
    foreach ($result as $user){
        $user_id = $user["id"];
        $statement = $conn->prepare("UPDATE users SET tickets = tickets+1 WHERE id = '$user_id'");
        $statement->execute();
    }
}