<?php
session_start();
include("../php/config.php");

if (isset($_SESSION["id"]) && isset($_SESSION["username"])) {
    // Kullanıcı oturumu açıksa, sayfayı göster
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/ba817edb9a.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/style.css">
        <title>Sairus</title>
    </head>

    <body>
        <div class="wrapper">
            <!-- Sidebar -->
            <aside id="sidebar">
                <div class="h-100">
                    <div class="sidebar-logo">
                        <a href="#">Sairus</a>
                    </div>
                    <!-- Sidebar Navigation -->
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="fa-solid fa-list pe-2"></i>
                                Ana Sayfa
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Bot İşlemleri
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#bot"
                                aria-expanded="false" aria-controls="bot">
                                <i class="fa-solid fa-robot pe-2"></i>
                                Botlar
                            </a>
                            <ul id="bot" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item">
                                    <a href="./bot/sendMessage.php" class="sidebar-link">Mesaj Gönder</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="../../php/logout.php" class="sidebar-link">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                Çıkış
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
            <!-- Main Component -->
            <div class="main">
                <nav class="navbar navbar-expand px-3 border-bottom">
                    <!-- Button for sidebar toggle -->
                    <button class="btn" type="button" data-bs-theme="dark">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </nav>
                <main class="content px-3 py-2">
                    <div class="container-fluid">
                        <div class="mb-3">
                            <h1>Merhaba!</h1>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>
        <script src="../js/script.js"></script>
    </body>

    </html>
    <?php
} else {
    // Kullanıcı oturumu açık değilse, giriş sayfasına yönlendir
    header("Location: ../../index.php");
    exit();
}
?>