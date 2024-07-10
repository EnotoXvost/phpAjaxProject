<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

$group_id = $_GET["group_id"] ?? false;
$group = mysqli_query($database, "select * from `groups` where id = $group_id")->fetch_assoc();

if (!$group) {
    header("Location: /admin/genres-list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $country = $_POST["country"];


    $updated = mysqli_query($database, "UPDATE `groups` SET
            name = '$name',
            country = '$country'
            WHERE id = $id
        ");

    if ($updated) {
        header("Location: /admin/change-group.php?group_id=" . $id);
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($database);
    }
}
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
    <link rel="stylesheet" href="./styles/table-style.css">
    <link rel="stylesheet" href="./styles/add-item.css">
    <title>Панель администратора</title>
</head>
<body>
<div class="wrapper">

    <?php include("./component/aside.php")?>

    <main class="main">
        <div class="main-header">
            <h1>Обновление группы: <?= $group["name"] ?></h1>
            <a href="/admin/list-groups.php">Вернуться назад</a>
        </div>

        <div class="information">
            <form method="post" class="form">
                <label>
                    Название группы
                    <input type="text" name="name" value="<?= $group["name"] ?>" required>
                </label>
                <label>
                    Страна
                    <input type="text" name="country" value="<?= $group["country"] ?>" required>
                </label>
                <input type="hidden" name="id" value="<?= $group["id"] ?>">

                <input type="submit" value="Сохранить">
            </form>
        </div>
    </main>

</div>
</body>
</html>