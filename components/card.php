<?php
$query ="SELECT * FROM item WHERE 1";
$statement = $conn->prepare($query);
$statement->execute();
$items = $statement->fetchAll();
if (count($items) > 0) {
    foreach ($items as $item) {
        ?>
        <a class="card-link" href="item.php?id=<?=$item["id"]?>">
            <div class="card">
                <div class="card-img">
                    <img src="../pictures/<?=$item["picture"]?>" alt="">
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <p class="title"><?=$item["title"]?></p>
                        <p class="author"><?=$item["author"]?></p>
                    </div>
                    <div class="card-footer">
                        <?=$item["release_date"]?>
                    </div>
                </div>
            </div>
        </a>
    <?php }
}else {
    echo "<p>nothing found</p>";
}

