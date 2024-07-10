<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}

require("../database.php");
$orders = mysqli_query($database, "select orders.*, orders.id as order_id, u.username from orders join music_shop.users u on u.id = orders.user_id");
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
            <h1>Список покупок</h1>
        </div>
        <div class="information">
            <table class="table">
                <tr>
                    <th>Индефикатор</th>
                    <th>Имя пользователя</th>
                    <th>Дата покупки</th>
                </tr>
                <?php
                while ($item = $orders->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $item["order_id"] . "</td>";
                    echo "<td>" . $item["username"] . "</td>";
                    echo "<td>" . $item["date"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </main>

</div>
</body>
</html>