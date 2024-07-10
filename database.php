<?php
$database = mysqli_connect("localhost", "root", "", "music_shop");

if (!$database) {
    die("Ошибка подключения к базе данных" . mysqli_connect_error());
}

mysqli_set_charset($database, "utf8");