<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

$deletedRecords = mysqli_query($database, "SELECT * FROM deleted_records ORDER BY deleted_at DESC");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="styles/table-style.css">
    <title>Удаленные записи</title>
</head>
<body>
<div class="wrapper">
    <?php include("./component/aside.php")?>
    <main class="main">
        <div class="main-header">
            <h1>Удаленные записи</h1>
            <a href="/admin/records.php">Вернуться к записям</a>
        </div>
        <div class="information">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Группа</th>
                    <th>Цена</th>
                    <th>Дата выпуска</th>
                    <th>Дата удаления</th>
                </tr>
                <?php while ($record = $deletedRecords->fetch_assoc()): ?>
                    <tr>
                        <td><?= $record['record_id'] ?></td>
                        <td><?= $record['title'] ?></td>
                        <td><?= $record['group_name'] ?></td>
                        <td><?= $record['price'] ?> ₽</td>
                        <td><?= $record['date_release'] ?></td>
                        <td><?= $record['deleted_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</div>
</body>
</html>
