<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}

require("../database.php");
$items = mysqli_query($database, "select *, vinylRecords.id as record_id, g.name as group_name from vinylRecords join music_shop.`groups` g on g.id = vinylRecords.group_id order by vinylRecords.id desc");
$records = mysqli_query($database, "select * from vinylRecords join music_shop.`groups` g on g.id = vinylRecords.group_id order by  vinylRecords.id desc ;");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="styles/table-style.css">
    <title>Панель администратора</title>
</head>
<body>
<div class="wrapper">

    <?php include("./component/aside.php") ?>

    <main class="main">
        <div class="main-header">
            <h1>Список пластинок</h1>
        </div>
        <div class="information">
            <table class="table">
                <tr>
                    <th>Индефикатор</th>
                    <th>Название</th>
                    <th>Группа</th>
                    <th>Цена</th>
                    <th>Год</th>
                    <th>Статус</th>
                </tr>
                <?php
                while ($item = $items->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='/admin/change-recod.php?record_id=" . $item["record_id"] . "'>" . $item["record_id"] . "</a></td>";
                    echo "<td>" . $item["title"] . "</td>";
                    echo "<td>" . $item["group_name"] . "</td>";
                    echo "<td>" . $item["price"] . "</td>";
                    echo "<td>" . $item["date_release"] . "</td>";
                    echo "<td>" . $item["statuss"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div class="content">
            <h1 class="page-title">Каталог</h1>
            <div id="recordsList" style="display: flex; flex-wrap: wrap; gap: 4px;">
                <?php
                $records = mysqli_query($database, "SELECT vr.id as record_id, vr.title, vr.price, vr.img_album, g.name 
                                                    FROM vinylRecords vr 
                                                    JOIN music_shop.`groups` g ON g.id = vr.group_id 
                                                    ORDER BY vr.id DESC");
                while ($item = $records->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<div class='album_cover'><a href='/admin/change-recod.php?record_id=" . $item["record_id"] . "'><img src='" . $item["img_album"] . "' alt=''/></a></div>";
                    echo "<div class='album_cont'><a href='/admin/change-recod.php?record_id=" . $item["record_id"] . "'>" . $item["name"] . "<br/> " . $item["title"] . " </a></div>";
                    echo "<div class='album_footer'>" . $item["price"] . " ₽</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </main>

</div>
</body>
</html>