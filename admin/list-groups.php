<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}

require("../database.php");
$items = mysqli_query($database, "select * from `groups` order by id desc ");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="styles/table-style.css">
    <title>Панель администратора</title>
</head>
<body>
<div class="wrapper">

    <?php include("./component/aside.php") ?>

    <main class="main">
        <div class="main-header">
            <h1>Список исполнителей</h1>
            <a href="/admin/add-groups.php">Добавить группу</a>
        </div>
        <div class="information">
            <table class="table">
                <tr>
                    <th>Индефикатор</th>
                    <th>Название группы</th>
                    <th>Страна</th>
                </tr>
                <?php
                while ($item = $items->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='/admin/change-group.php?group_id=" . $item["id"] . "'>" . $item["id"] . "</a></td>";
                    echo "<td>" . $item["name"] . "</td>";
                    echo "<td>" . $item["country"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </main>

</div>
</body>
</html>