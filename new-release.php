<?php
require("./database.php");

$records = mysqli_query($database, "select * from vinylRecords left join music_shop.`groups` g on g.id = vinylRecords.group_id where quantity != 0  order by vinylRecords.id desc limit 30");

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
    <link rel="stylesheet" href="./styles/index.css">
    <title>Магазин виниловых пластинок</title>
</head>
<body>
<div class="wrapper">
    <?php include("./navbar.php") ?>

    <main class="main">
        <div class="container">
            <div class="content">
                <h1 class="page-title">Новыве поступления</h1>
                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                    <?php
                    while ($item = $records->fetch_assoc()) {
                        echo "<div class='card'>";
                        echo "<div class='album_cover'><a href='./record.php?id=" . $item["id"] . "'><img src='" . $item["img_album"] . "' alt=''/></a></div>";
                        echo "<div class='album_cont'><a href='./record.php?id=" . $item["id"] . "'>" . $item["name"] . "<br/> " . $item["title"] . " </a></div>";
                        echo "<div class='album_footer'>" . $item["price"] . " ₽</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>