<?php
$record_id = $_GET["id"] ?? false;

if ($record_id) {
    require("./database.php");
    $record_data = mysqli_query($database,
        "select vinylRecords.*, g.name as group_name, g.country, genre.name as genre_name
from vinylRecords
         join music_shop.`groups` g on g.id = vinylRecords.group_id
        join music_shop.genres genre on genre.id = vinylRecords.genre_id
where vinylRecords.id = $record_id;
")->fetch_assoc();

    if (!isset($record_data)) {
        header("Location: not_found.php");
        exit();
    }

} else {
    header("Location: not_found.php");
    exit();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/reset.css">
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./styles/record.css">
    <title>Document</title>
</head>
<body>
<div class="wrapper">
    <?php include("./navbar.php") ?>

    <main class="main">
        <div class="record">
            <div class="record-album">
                <img src="<?= $record_data["img_album"] ?>" alt="record - album">
            </div>

            <div class="record-info">
                <div class="info-title">
                    <h1><?= $record_data["title"] ?></h1>
                    <h2><?= $record_data["group_name"] ?></h2>
                </div>
                <div class="info-content">
                    <ul>
                        <li>Жанр: <?= $record_data["genre_name"] ?></li>
                        <li>Группа: <?= $record_data["group_name"] ?></li>
                        <li>Стиль: <?= $record_data["genre_name"] ?></li>
                        <li>Страна: <?= $record_data["country"] ?></li>
                    </ul>
                </div>
                <div class="info-footer">

                    <?php
                    if ($record_data["quantity"] != 0) {
                        echo "<a href='/cart/add-to-cart.php?id=" . $record_data["id"] . "'>Купить за " . $record_data["price"] . "</a>";
                    } else {
                        echo "<button disabled>Купить за " . $record_data["price"] . "</button>";
                    }
                    ?>
                    <?php
                    if ($record_data["quantity"] != 0) {
                        echo "<span>Есть в наличии</span>";
                    } else {
                        echo "<span>Нет в наличии</span>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>