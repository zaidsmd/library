<aside>
    <div class="navbar">
        <h2>Ouvrages</h2>
        <a href="reservations.php" class="nav-link
<?= explode('/', $_SERVER["SCRIPT_FILENAME"])[count(explode('/', $_SERVER["SCRIPT_FILENAME"])) - 1] == "reservations.php" ? "active" : "" ?>">
            <div>
                <i class="fa-solid fa-calendar-days"></i>
                <span>Reservations</span>
            </div>
        </a>
        <a href="borrowings.php" class="nav-link
<?= explode('/', $_SERVER["SCRIPT_FILENAME"])[count(explode('/', $_SERVER["SCRIPT_FILENAME"])) - 1] == "borrowings.php" ? "active" : "" ?>">
            <div>
                <i class="fa-solid fa-box-archive"></i>
                <span>Emprunts</span>
            </div>
        </a>
        <a href="items.php" class="nav-link
<?= explode('/', $_SERVER["SCRIPT_FILENAME"])[count(explode('/', $_SERVER["SCRIPT_FILENAME"])) - 1] == "items.php" ? "active" : "" ?>">
            <div>
                <i class="fa-solid fa-box"></i>
                <span>Ouvrages</span>
            </div>
        </a>
        <a href="users.php" class="nav-link
        <?= explode('/', $_SERVER["SCRIPT_FILENAME"])[count(explode('/', $_SERVER["SCRIPT_FILENAME"])) - 1] == "users.php" ? "active" : "" ?>">
            <div>
                <i class="fa-solid fa-users"></i>
                <span>Adhérents</span>
            </div>
        </a>
    </div>
    <a href="logout.php" class="btn btn-danger">Se déconnecter <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
</aside>