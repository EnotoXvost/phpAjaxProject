<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];

//    вставлем значения
    $inserted = mysqli_query($database, "insert into genres (id, name) values (null, '$name')");

//    если вставилось успешно, то переносим изображение в папку /img/ и делаем редирект на list.php
    if ($inserted) {
        header("Location: /admin/genres-list.php");
        exit();
    } else {
        echo "Error updating genre: " . mysqli_error($conn);
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

    <?php include("./component/aside.php") ?>

    <main class="main">
        <div class="main-header">
            <h1>Добавление жанра</h1>
            <a href="/admin/genres-list.php">Вернуться назад</a>
        </div>

        <div class="information">
            <form method="post" class="form" enctype="multipart/form-data">
                <label>
                    Название
                    <input type="text" name="name" required>
                </label>


                <input type="submit" value="Сохранить">
            </form>
        </div>
    </main>

</div>
</body>
</html>