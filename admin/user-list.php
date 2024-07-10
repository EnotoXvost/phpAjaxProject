<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}

require("../database.php");
$users = mysqli_query($database,
    "select * from users");
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
            <h1>Список пользователей</h1>
        </div>
        <div class="information">
            <table class="table">
                <tr>
                    <th>Индефикатор</th>
                    <th>Имя пользователей</th>
                    <th>Почта</th>
                    <th>Статус блокировки</th>
                    <th>Покупок совершено</th>
                </tr>
                <?php
                while ($user = $users->fetch_assoc()) {
                    $user_id = $user["id"];
                    $count_orders = mysqli_query($database, "select COUNT(id) as count from orders where user_id = $user_id")->fetch_assoc();
                    echo "<tr>";
                    echo "<td>" . $user["id"] . "</td>";
                    echo "<td>" . $user["username"] . "</td>";
                    echo "<td>" . $user["email"] . "</td>";
                    echo "<td>" . $user["can_login"] . "</td>";
                    echo "<td>" . $count_orders["count"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </main>

</div>
</body>
</html>