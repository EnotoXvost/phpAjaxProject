<?php
require("./database.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
// Получение данных из формы
    $musician = $_GET["musician"];
    $album = $_GET["album"];
    $price_from = $_GET["price_from"];
    $price_to = $_GET["price_to"];
    $selected_genre = $_GET["genres"];

    $sql = "SELECT *, vinylRecords.id as record_id 
    FROM VinylRecords 
    join music_shop.`groups` g on g.id = VinylRecords.group_id 
    join music_shop.genres g2 on g2.id = VinylRecords.genre_id
    WHERE 1=1";

    if (!empty($musician)) {
        $sql .= " AND g.name LIKE '%$musician%'";
    }

    if (!empty($album)) {
        $sql .= " AND title LIKE '%$album%'";
    }

    if (!empty($price_from)) {
        $sql .= " AND price >= $price_from";
    }

    if (!empty($price_to)) {
        $sql .= " AND price <= $price_to";
    }

    if (!empty($selected_genre)) {
        $sql .= " AND g2.name = '$selected_genre'";
    }

    $sql.= " order by vinylRecords.id desc";
    $records = mysqli_query($database, $sql);
} else {
    $records = mysqli_query($database, "select * from vinylRecords join music_shop.`groups` g on g.id = vinylRecords.group_id order by  vinylRecords.id desc ;");
}

$genres = mysqli_query($database, "SELECT * FROM genres");


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/reset.css">
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./styles/index.css">
    <title>Магазин виниловых пластинок</title>
</head>
<body>
<div class="wrapper">
    <?php include("./navbar.php") ?>

    <main class="main">
        <div class="container">
            <aside>
                <div class="filters">
                    <h2 class="filter_title">
                        Поиск по каталогу
                    </h2>
                    <form method="get" class="form">
                        <div class="form-group">
                            <label for="musician">Исполнитель</label>
                            <input class="form-control" type="text"
                                   value="<?php echo isset($_GET['musician']) ? htmlspecialchars($_GET['musician']) : ''; ?>"
                                   id="musician" name="musician"
                                   placeholder="например, Michael Jackson" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="album">Альбом</label>
                            <input class="form-control" type="text"
                                   value="<?php echo isset($_GET['album']) ? htmlspecialchars($_GET['album']) : ''; ?>"
                                   id="album" name="album"
                                   placeholder="например, Thriller" autocomplete="off">
                        </div>
                        <fieldset class="form-group">
                            <legend>Цена</legend>
                            <div class="row">
                                <div class="col">
                                    <input class="form-control" type="text"
                                           value="<?php echo isset($_GET['price_from']) ? htmlspecialchars($_GET['price_from']) : ''; ?>"
                                           name="price_from" placeholder="от">
                                </div>
                                <div class="col center">
                                    <p class="">-</p>
                                </div>
                                <div class="col">
                                    <input class="form-control" type="text"
                                           value="<?php echo isset($_GET['price_to']) ? htmlspecialchars($_GET['price_to']) : ''; ?>"
                                           name="price_to" placeholder="до">
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <div class="genres-container">
                                <label for="genres">Жанры</label>
                                <select class="select" name="genres" id="genres">
                                    <option></option>
                                    <?php
                                    while ($genre = $genres->fetch_assoc()) {
                                        $selected = ($_GET['genres'] === $genre["name"]) ? 'selected' : '';
                                        echo "<option $selected>" . $genre["name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input class="submit-btn" type="submit" value="Применить">
                            <br/>
                            <a class="reset-btn" href="/">Сбросить фильтры</a>
                        </div>
                    </form>
                </div>
            </aside>

            <div class="content">
                <h1 class="page-title">Каталог</h1>
                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                    <?php
                    while ($item = $records->fetch_assoc()) {
                        echo "<div class='card'>";
                        echo "<div class='album_cover'><a href='./record.php?id=" . $item["record_id"] . "'><img src='" . $item["img_album"] . "' alt=''/></a></div>";
                        echo "<div class='album_cont'><a href='./record.php?id=" . $item["record_id"] . "'>" . $item["name"] . "<br/> " . $item["title"] . " </a></div>";
                        echo "<div class='album_footer'>" . $item["price"] . " ₽</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>