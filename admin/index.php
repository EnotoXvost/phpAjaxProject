<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}

require("../database.php");
$orders = mysqli_query($database, "select COUNT(id) as count_orders from orders")->fetch_assoc();
$active_items = mysqli_query($database, "select COUNT(id) as count from vinylRecords where quantity != 0")->fetch_assoc();
$non_active_items = mysqli_query($database, "select COUNT(id) as count from vinylRecords where quantity = 0")->fetch_assoc();
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
    <title>Панель администратора</title>
</head>
<body>
<div class="wrapper">

   <?php include("./component/aside.php") ?>

    <main class="main">
        <h1>Панель администратора</h1>
        <div class="information">
            <div class="card">
                Кол-во заказов:
                <p><?= $orders["count_orders"] ?></p>
            </div>
            <div class="card">
                Товары, которые есть в наличии:
                <p><?= $active_items["count"] ?></p>
            </div>
            <div class="card">
                Товары, которых нет в наличии:
                <p><?= $non_active_items["count"] ?></p>
            </div>
        </div>
    </main>

</div>
</body>
</html>