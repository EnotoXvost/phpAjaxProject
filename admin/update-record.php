<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    echo json_encode(["status" => "error", "message" => "Доступ запрещен."]);
    exit();
}
require("../database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $group = $_POST["group"];
    $genre = $_POST["genre"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $date = $_POST["date"];

    $updated = mysqli_query($database, "UPDATE vinylRecords SET
            title = '$title',
            group_id = $group,
            genre_id = $genre,
            price = $price,
            quantity = $quantity,
            date_release = '$date'
            WHERE id = $id
        ");

    if ($updated) {
        $record = mysqli_query($database, "SELECT * FROM vinylRecords WHERE id = $id")->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $record]);
    } else {
        echo json_encode(["status" => "error", "message" => "Ошибка при обновлении записи: " . mysqli_error($database)]);
    }
}
?>
