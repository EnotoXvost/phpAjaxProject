<?php
session_start();
$admin = $_SESSION["admin_able"] ?? false;
if (!$admin) {
    header("Location: /index.php");
    exit();
}
require("../database.php");

$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    $record_id = $_POST["record_id"] ?? false;

    if ($record_id) {
        $record = mysqli_query($database, "SELECT vr.*, g.name as group_name FROM vinylRecords vr JOIN music_shop.`groups` g ON g.id = vr.group_id WHERE vr.id = $record_id")->fetch_assoc();

        if ($record) {
            $stmt = $database->prepare("INSERT INTO deleted_records (record_id, title, group_name, price, date_release) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issdi", $record['id'], $record['title'], $record['group_name'], $record['price'], $record['date_release']);
            $stmt->execute();
            $stmt->close();

            $deleted = mysqli_query($database, "DELETE FROM vinylRecords WHERE id = $record_id");

            if ($deleted) {
                $response = [
                    "status" => "success",
                    "message" => "Запись успешно удалена",
                    "deletedTitle" => $record["title"]
                ];
            } else {
                $response = ["status" => "error", "message" => "Ошибка при удалении записи: " . mysqli_error($database)];
            }
        } else {
            $response = ["status" => "error", "message" => "Запись не найдена"];
        }
    } else {
        $response = ["status" => "error", "message" => "Неверный идентификатор записи"];
    }

    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajax'])) {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $group = $_POST["group"];
    $genre = $_POST["genre"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $date = $_POST["date"];

    $oldRecord = mysqli_query($database, "SELECT * FROM vinylRecords WHERE id = $id")->fetch_assoc();

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
        $newRecord = mysqli_query($database, "SELECT * FROM vinylRecords WHERE id = $id")->fetch_assoc();

        $response = [
            "status" => "success",
            "message" => "Запись успешно обновлена",
            "oldData" => $oldRecord,
            "newData" => $newRecord
        ];
    } else {
        $response = ["status" => "error", "message" => "Ошибка при обновлении записи: " . mysqli_error($database)];
    }

    echo json_encode($response);
    exit();
}

$record_id = $_GET["record_id"] ?? false;
$record = mysqli_query($database, "SELECT * FROM vinylRecords WHERE vinylRecords.id = $record_id")->fetch_assoc();

if (!$record) {
    header("Location: /admin/records.php");
    exit();
}

$groups = mysqli_query($database, "SELECT * FROM `groups`");
$genres = mysqli_query($database, "SELECT * FROM genres");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/table-style.css">
    <link rel="stylesheet" href="./styles/add-item.css">
    <title>Панель администратора</title>
    <style>
        .information, .current-info, .updated-info {
            margin-top: 20px;
        }

        .card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .album_cover img {
            max-width: 100%;
            border-radius: 4px;
        }

        .album_cont {
            margin: 10px 0;
        }

        .info-label {
            font-weight: bold;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="wrapper">

    <?php include("./component/aside.php")?>

    <main class="main">
        <div class="main-header">
            <h1>Лист пластинок</h1>
            <a href="/admin/records.php">Вернуться назад</a>
            <br/>
            <br/>
            <a id="deleteRecord" style="color: #8b0000" href="#" data-id="<?= $record_id ?>">Удалить</a>
        </div>

        <div class="information">
            <form id="updateRecordForm" class="form" enctype="multipart/form-data">
                <label>
                    Название
                    <input type="text" name="title" value="<?= $record["title"] ?>" required>
                </label>

                <label>
                    Группа
                    <select name="group" required>
                        <?php
                        while ($gr = $groups->fetch_assoc()) {
                            $selected = ($record["group_id"] == $gr["id"]) ? 'selected' : '';
                            echo "<option $selected value='" . $gr["id"] . "'>" . $gr["name"] . "</option>";
                        }
                        ?>
                    </select>
                </label>

                <label>
                    Жанр
                    <select name="genre" required>
                        <?php
                        while ($genre = $genres->fetch_assoc()) {
                            $selected = ($record["genre_id"] == $genre["id"]) ? 'selected' : '';
                            echo "<option $selected value='" . $genre["id"] . "'>" . $genre["name"] . "</option>";
                        }
                        ?>
                    </select>
                </label>

                <label>
                    Цена
                    <input type="number" name="price" value="<?= $record["price"] ?>" required>
                </label>

                <label>
                    Кол-во
                    <input type="number" name="quantity" value="<?= $record["quantity"] ?>" required>
                </label>

                <label>
                    Дата выпуска альбома
                    <input type="number" maxlength="4" minlength="4" name="date" value="<?= $record["date_release"] ?>" required>
                </label>

                <div>
                    <img width="140" src="<?= $record["img_album"] ?>" alt="">
                </div>
                <input type="hidden" name="id" value="<?= $record["id"] ?>">

                <input type="submit" value="Сохранить">
            </form>
        </div>

        <div class="current-info">
            <h2>Текущая информация</h2>
            <div class="card">
                <div class="album_cover"><img width="140" src="<?= $record["img_album"] ?>" alt=""></div>
                <div class="album_cont"><span class="info-label">Название: </span><?= $record["title"] ?></div>
                <div class="album_cont"><span class="info-label">Цена: </span><?= $record["price"] ?> ₽</div>
                <div class="album_cont"><span class="info-label">Количество: </span><?= $record["quantity"] ?> шт.</div>
                <div class="album_cont"><span class="info-label">Дата выпуска: </span><?= $record["date_release"] ?></div>
            </div>
        </div>

        <div class="updated-info" style="display:none;">
            <h2>Обновленная информация</h2>
            <div class="card" id="updatedInfoCard">

            </div>
        </div>

    </main>

</div>

<script>
    $(document).ready(function () {
        $('#updateRecordForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('ajax', true);

            $.ajax({
                url: '',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            var oldData = res.oldData;
                            var newData = res.newData;

                            var updatedInfoCard = `
                                <div class="album_cover"><img width="140" src="${newData.img_album}" alt=""></div>
                                <div class="album_cont"><span class="info-label">Название: </span>${newData.title}</div>
                                <div class="album_cont"><span class="info-label">Цена: </span>${newData.price} ₽</div>
                                <div class="album_cont"><span class="info-label">Количество: </span>${newData.quantity} шт.</div>
                                <div class="album_cont"><span class="info-label">Дата выпуска: </span>${newData.date_release}</div>
                            `;

                            $('#updatedInfoCard').html(updatedInfoCard);
                            $('.updated-info').show();

                            alert(
                                'Запись успешно обновлена\n\n' +
                                'Старые данные:\n' +
                                'Название: ' + oldData.title + '\n' +
                                'Цена: ' + oldData.price + ' ₽\n' +
                                'Количество: ' + oldData.quantity + ' шт.\n' +
                                'Дата выпуска: ' + oldData.date_release + '\n\n' +
                                'Новые данные:\n' +
                                'Название: ' + newData.title + '\n' +
                                'Цена: ' + newData.price + ' ₽\n' +
                                'Количество: ' + newData.quantity + ' шт.\n' +
                                'Дата выпуска: ' + newData.date_release
                            );
                        } else {
                            alert(res.message);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }
                }
            });
        });

        $('#deleteRecord').on('click', function (e) {
            e.preventDefault();
            var recordId = $(this).data('id');

            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    delete: true,
                    record_id: recordId
                },
                success: function (response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            alert('Запись успешно удалена\n\n' + 'Название: ' + res.deletedTitle);
                            window.location.href = '/admin/records.php';
                        } else {
                            alert(res.message);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }
                }
            });
        });
    });
</script>
</body>
</html>
