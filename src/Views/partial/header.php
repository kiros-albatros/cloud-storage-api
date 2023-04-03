<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>Cloud Storage</title>
</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-xxl bd-gutter">
        <a class="navbar-brand" href="/"><img width="50" src="/2.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['user_id'])) { ?>
                <?php if ($_SESSION['user_role'] == 'admin') { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT; ?>/user">Пользователи</a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT; ?>/admin/files">Файлы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT; ?>/admin/directories">Папки</a>
                        </li>
                    <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= URLROOT; ?>/file">Ваши файлы</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT; ?>/directory">Ваши папки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT; ?>/files/shared">Доступные файлы</a>
                    </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Добавить
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= URLROOT; ?>/file/add">Добавить файл</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= URLROOT; ?>/directory/add">Добавить папку</a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php }; ?>
            </ul>

            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li class="nav-item">
                        <span class="nav-link">Добро пожаловать,
                            <a href="<?= URLROOT; ?>/users/<?= $_SESSION['user_id']; ?>"><?= $_SESSION['user_email']; ?></a>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/user/logout">Выйти</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/user/register">Регистрация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/user/login">Войти</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<body>
    <div class="container-xxl bd-gutter py-4">