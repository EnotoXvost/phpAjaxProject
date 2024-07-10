<?php
session_start();
$userId = $_SESSION["userId"] ?? false;
?>

<header class="header">
    <div class="header_panel">
        <div class="container">
            <div class="brand-name">
                <a href="/" class="name">
                    <span>Магазин<br/>виниловых<br/>пластинок</span>
                </a>
            </div>
            <div class="header-info">
                <ul class="user-panel">
                    <?php
                    if ($userId) {
                        echo "<li class='user-panel__item'><a href='/profile.php''>Профиль</a></li>";
                        echo "<li class='user-panel__item'><a href='/cart/cart.php'>Корзина</a></li>";
                    } else {
                        echo "<li class='user-panel__item'><a href='/auth/auth.php'>Авторизация</a></li>";
                    }
                    ?>


                </ul>
            </div>
        </div>
    </div>
    <nav class="main-menu">
        <div class="container">
            <ul class="main-menu__list">
                <li class="main-menu_item"><a href="/">Каталог</a></li>
                <li class="main-menu_item"><a href="/new-release.php">Свежие релизы</a></li>
            </ul>
        </div>
    </nav>
</header>