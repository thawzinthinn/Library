<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Media Library') ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/style.css">
</head>

<body>

    <div class="page-container">

        <!-- HEADER -->
        <header class="header">
            <div class="wrapper">

                <!-- LOGO -->
                <h1 class="logo">
                    <a href="<?= BASE_URL ?>/Public/index.php">
                        <img src="<?= BASE_URL ?>/Public/img/Brand-title.png" alt="Media Library">
                    </a>
                </h1>

                <!-- NAVIGATION -->
                <ul class="nav">

                    <li class="<?= ($section === 'books') ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=books">
                            <img src="<?= BASE_URL ?>/Public/img/book.png" alt="">
                            Books
                        </a>
                    </li>

                    <li class="<?= ($section === 'movies') ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=movies">
                            <img src="<?= BASE_URL ?>/Public/img/movie.png" alt="">
                            Movies
                        </a>
                    </li>

                    <li class="<?= ($section === 'music') ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=music">
                            <img src="<?= BASE_URL ?>/Public/img/music.png" alt="">
                            Music
                        </a>
                    </li>

                    <li class="<?= ($section === 'suggest') ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=suggest">
                            <img src="<?= BASE_URL ?>/Public/img/suggestion.png" alt="">
                            Suggest
                        </a>
                    </li>

                    <!-- AUTH LINKS -->
                    <?php if (!isset($_SESSION['user_id'])): ?>

                        <li>
                            <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                                Login
                            </a>
                        </li>

                        <li>
                            <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                                Register
                            </a>
                        </li>

                    <?php else: ?>

                        <li class="welcome-user">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </li>

                        <li>
                            <a href="<?= BASE_URL ?>/Public/index.php?page=logout">
                                Logout
                            </a>
                        </li>

                    <?php endif; ?>
                </ul>

                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="success-message">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>

                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- </ul> -->

            </div>
        </header>

        <!-- SEARCH -->
        <?php if (empty($hideSearch)): ?>
            <div class="search">
                <div class="wrapper">

                    <form method="get" action="<?= BASE_URL ?>/Public/index.php">

                        <input type="hidden" name="page" value="catalog">

                        <?php if (!empty($section)): ?>
                            <input type="hidden" name="cat" value="<?= htmlspecialchars($section) ?>">
                        <?php endif; ?>

                        <label for="s">Search:</label>

                        <input type="text" name="s" id="s">

                        <input type="submit" value="Go">

                    </form>

                </div>
            </div>
        <?php endif; ?>

        <!-- MAIN CONTENT -->
        <main id="content">