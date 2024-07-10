<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

$genre_id = $_GET["genre_id"] ?? false;
$genre = mysqli_query($database, "select * from genres where id = $genre_id")->fetch_assoc();

if (!$genre) {
    header("Location: /admin/genres-list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];


    $updated = mysqli_query($database, "UPDATE genres SET
            name = '$name'
            WHERE id = $id
        ");

    if ($updated) {
        header("Location: /admin/change-genre.php?genre_id=" . $id);
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
            <h1>Лист пластинок</h1>
            <a href="/admin/genres-list.php">Вернуться назад</a>
            <br/>
            <br/>
            <a style="color: #8b0000" href="./delete-genre.php?genre_id=<?= $genre_id ?>">Удалить</a>
        </div>

        <div class="information">
            <form method="post" class="form">
                <label>
                    Жанр
                    <input type="text" name="name" value="<?= $genre["name"] ?>" required>
                </label>
                <input type="hidden" name="id" value="<?= $genre["id"] ?>">

                <!--                <label>-->
                <!--                    Обложка-->
                <!--                    <input type="file" name="image" required>-->
                <!--                </label>-->

                <input type="submit" value="Сохранить">
            </form>
        </div>
    </main>

</div>
</body>
</html>