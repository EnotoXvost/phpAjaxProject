<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

$record_id = $_GET["record_id"] ?? false;

$deleted = mysqli_query($database, "delete from vinylRecords where id = $record_id");
if ($deleted) {
    header("Location: /admin/records.php");
    exit();
}