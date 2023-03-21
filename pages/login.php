<?php
session_start();
if (isset($_SESSION["sign"])) {
    header('Location: index.php ');
    exit();
}
if (isset($_GET["username"])) {
    include "dbconfig.php";
    $username = strtolower(trim($_GET["username"]));
    $password = md5($_GET["password"]);
    $statement = $conn->prepare("SELECT * FROM users WHERE `username` = '$username' AND `password` = '$password'");
    $statement->execute();
    $result = $statement->fetchAll();
    if (count($result) > 0 && $result[0]["tickets"] < 3 ) {
        $_SESSION["id"] = $result[0]["id"];
        $_SESSION["sign"] = true;
        header('Location: index.php');
        exit();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <?php include "../components/head.php"?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/login.min.css">
    <title>Se connecter à la médiathèque</title>
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
<form action="login.php" method="get">
    <h1>Login</h1>
    <?php
    if (isset($result)) {
        if (isset($result[0])) {
            if ($result[0]["tickets"] > 3) {
                ?> <p class='response'>Votre compte a été suspendu</p>
                <?php
            }
        } else {
            ?>
            <p class='response'>Le mot de pass ou username est incorrect</p>
            <?php
        }
    }
    ?>
    <div class="relative input">
        <input type="text" id="username"
               name="username"
               class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg  border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
               placeholder=" "/>
        <label for="username"
               class="absolute text-sm  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Username</label>
    </div>
    <div class="relative input">
        <input type="password" id="password"
               name="password"
               class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg  border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
               placeholder=" "/>
        <label for="password"
               class="absolute text-sm  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Mot
            de pass</label>
    </div>
    <input class="btn btn-primary w-full" type="submit" value="Se connecter">
</form>
</body>
</html>
