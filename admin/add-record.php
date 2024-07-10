<?php
session_start();
require("../database.php");

$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $group = $_POST["group"];
    $genre = $_POST["genre"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $date = $_POST["date"];
    $statuss = $_POST["statuss"];

    $image = $_FILES["image"];
    $file_name = $image['name'];
    $file_tmp = $image['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid('', true) . "." . $file_ext;
    $imagePath = "/images/" . $new_file_name;

    $inserted = mysqli_query($database, "INSERT INTO vinylRecords 
    (id, title, group_id, genre_id, price, quantity, date_release, img_album, statuss)
    VALUES (NULL, '$title', $group, $genre, $price, $quantity, $date, '$imagePath', '$statuss')");

    if ($inserted) {
        move_uploaded_file($file_tmp, "../images/" . $new_file_name);
        $new_record_id = mysqli_insert_id($database);
        $new_record = mysqli_query($database, "SELECT vr.id as record_id, vr.title, vr.price, vr.img_album, g.name 
                                               FROM vinylRecords vr 
                                               JOIN music_shop.`groups` g ON g.id = vr.group_id 
                                               WHERE vr.id = $new_record_id")->fetch_assoc();
        echo json_encode([
            "status" => "success",
            "message" => "Record added successfully",
            "record" => $new_record,
            "data" => [
                "title" => $title,
                "group" => $group,
                "genre" => $genre,
                "price" => $price,
                "quantity" => $quantity,
                "date" => $date,
                "img_album" => $imagePath,
                "statuss" => $statuss
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error inserting record: " . mysqli_error($database)]);
    }
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/table-style.css">
    <link rel="stylesheet" href="./styles/add-item.css">
    <title>Панель администратора</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="wrapper">
    <?php include("./component/aside.php") ?>

    <main class="main">
        <div class="main-header">
            <h1>Лист пластинок</h1>
            <a href="/admin/records.php">Вернуться назад</a>
        </div>

        <div class="information">
            <form id="addRecordForm" class="form" enctype="multipart/form-data">
                <label>
                    Название
                    <input type="text" name="title" required>
                </label>

                <label>
                    Группа
                    <select name="group" required>
                        <?php
                        $groups = mysqli_query($database, "SELECT * FROM `groups`");
                        while ($gr = $groups->fetch_assoc()) {
                            echo "<option value='" . $gr["id"] . "'>" . $gr["name"] . "</option>";
                        }
                        ?>
                    </select>
                </label>

                <label>
                    Жанр
                    <select name="genre" required>
                        <?php
                        $genres = mysqli_query($database, "SELECT * FROM genres");
                        while ($genre = $genres->fetch_assoc()) {
                            echo "<option value='" . $genre["id"] . "'>" . $genre["name"] . "</option>";
                        }
                        ?>
                    </select>
                </label>

                <label>
                    Статус
                    <select name="statuss" required>
                        <option value = "В наличии">В наличии</option>
                        <option value = "Не в наличии">Не в наличии</option>
                    </select>
                </label>

                <label>
                    Цена
                    <input type="number" name="price" required>
                </label>

                <label>
                    Кол-во
                    <input type="number" name="quantity" required>
                </label>

                <label>
                    Дата выпуска альбома
                    <input type="number" maxlength="4" minlength="4" name="date" required>
                </label>

                <label>
                    Обложка
                    <input type="file" name="image" required>
                </label>

                <input type="submit" value="Сохранить">
            </form>
        </div>

        <div class="content">
            <h1 class="page-title">Каталог</h1>
            <div id="recordsList" style="display: flex; flex-wrap: wrap; gap: 4px;">
                <?php
                $records = mysqli_query($database, "SELECT vr.id as record_id, vr.title, vr.price, vr.img_album, g.name 
                                                    FROM vinylRecords vr 
                                                    JOIN music_shop.`groups` g ON g.id = vr.group_id 
                                                    ORDER BY vr.id DESC");
                while ($item = $records->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<div class='album_cover'><a><img src='" . $item["img_album"] . "' alt=''/></a></div>";
                    echo "<div class='album_cont'><a'>" . $item["name"] . "<br/> " . $item["title"] . " </a></div>";
                    echo "<div class='album_footer'>" . $item["price"] . " ₽</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </main>
</div>

<script>
    $(document).ready(function () {
        $('#addRecordForm').on('submit', function (e) {
            e.preventDefault();
            if (validateForm()) {
                var formData = new FormData(this);

                $.ajax({
                    url: 'add-record.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            var record = res.record;
                            var newCard = "<div class='card'>" +
                                "<div class='album_cover'><a><img src='" + record.img_album + "' alt=''/></a></div>" +
                                "<div class='album_cont'><a'>" + record.name + "<br/> " + record.title + " </a></div>" +
                                "<div class='album_footer'>" + record.price + " ₽</div>" +
                                "</div>";
                            $('#recordsList').prepend(newCard);
                            $('#addRecordForm')[0].reset();
                            alert("добавлен товар с:\n" +
                            "Название: " + record.title + "\n" +
                            "Цена: " + record.price + " ₽\n" +
                            "Количество: " + record.quantity + " шт.\n" +
                            "Дата выпуска: " + record.date_release + "\n\n");
                        } else {
                            alert(res.message);
                        }
                    }
                });
            }
        });

        function validateForm() {
            var isValid = true;
            var requiredFields = ['title', 'group', 'genre', 'price', 'quantity', 'date', 'image'];

            requiredFields.forEach(function(field) {
                if (!$('[name="' + field + '"]').val()) {
                    isValid = false;
                    alert('Поле ' + field + ' обязательно для заполнения');
                }
            });

            if (!isValid) return false;

            var date = $('[name="date"]').val();
            if (date.length !== 4 || isNaN(date)) {
                alert('Введите корректную дату выпуска (4 цифры)');
                isValid = false;
            }

            return isValid;
        }
    });
</script>
</body>
</html>
