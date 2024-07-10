<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

$genre_id = $_GET["genre_id"] ?? false;

$deleted = mysqli_query($database, "delete from genres where id = $genre_id");
if ($deleted) {
    header("Location: /admin/genres-list.php");
    exit();
}