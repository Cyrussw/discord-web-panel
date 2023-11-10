<?php
session_start();
include("../../php/config.php");

if (isset($_SESSION["id"]) && isset($_SESSION["username"])) {
    // Kullanıcı oturumu açıksa, kullanıcının yetkilerini kontrol et
    $userId = $_SESSION["id"];
    $isAdmin = false; // Varsayılan olarak isAdmin değeri false
    $isOwner = false; // Varsayılan olarak isOwner değeri false

    $servername = "localhost"; // Veritabanı sunucu adı
    $db_username = "root"; // Veritabanı kullanıcı adı
    $db_password = ""; // Veritabanı şifresi
    $database = "discord"; // Veritabanı adı

    // Veritabanı bağlantısı
    $db = new mysqli($servername, $db_username, $db_password, $database);

    if ($db->connect_error) {
        die("Veritabanına bağlanılamadı: " . $db->connect_error);
    }

    // Kullanıcının yetkilerini kontrol et
    $sql = "SELECT isAdmin, isOwner FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($isAdmin, $isOwner);

    if ($stmt->fetch()) {
        // Kullanıcının yetkileri alındı, işlem yapabilirsiniz
        if ($isAdmin == 1 || $isOwner == 1) {
            // Site kodları
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
                // Yeni kullanıcı verilerini alın
                $username = $_POST["username"];
                $password = $_POST["password"];

                $servername = "localhost"; // Veritabanı sunucu adı
                $db_username = "root"; // Veritabanı kullanıcı adı
                $db_password = ""; // Veritabanı şifresi
                $database = "discord"; // Veritabanı adı

                // Veritabanı bağlantısı
                $db = new mysqli($servername, $db_username, $db_password, $database);

                if ($db->connect_error) {
                    die("Veritabanına bağlanılamadı: " . $db->connect_error);
                }

                // SQL Injection önleme: Hazır ifade kullanarak yeni kullanıcı ekleme sorgusu
                $sql = "INSERT INTO `users` (username, password, createdTime) VALUES (?, ?, NOW())";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ss", $username, $password);

                if ($stmt->execute()) {
                    // Yeni kullanıcı başarıyla eklendi
                    header("Location: addUser.php"); // Kullanıcıların listelendiği sayfaya yönlendirin
                    exit();
                } else {
                    echo "Yeni kullanıcı ekleme hatası: " . $stmt->error;
                }

                $stmt->close();
                $db->close();
            }
            ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <script src="https://kit.fontawesome.com/ba817edb9a.js" crossorigin="anonymous"></script>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
                    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
                <link rel="stylesheet" href="../../css/style.css">
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
                                    <a href="../index.php" class="sidebar-link">
                                        <i class="fa-solid fa-list pe-2"></i>
                                        Ana Sayfa
                                    </a>
                                </li>
                                <li class="sidebar-header">
                                    Yönetici İşlemleri
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#users"
                                        aria-expanded="false" aria-controls="users">
                                        <i class="fa fa-user pe-2"></i>
                                        Kullanıcı
                                    </a>
                                    <ul id="users" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                        <li class="sidebar-item">
                                            <a href="./addUser.php" class="sidebar-link">Kullanıcı Ekle</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="./remoteUser.php" class="sidebar-link">Kullanıcıları Düzenle</a>
                                        </li>
                                    </ul>
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
                                            <a href="../bot/createBot.php" class="sidebar-link">Bot Oluştur</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="../bot/config.php" class="sidebar-link">Konfigürasyon</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-header">
                                    Mesaj İşlemleri
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#message"
                                        aria-expanded="false" aria-controls="message">
                                        <i class="fa-solid fa-message pe-2"></i>
                                        Mesaj
                                    </a>
                                    <ul id="message" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                        <li class="sidebar-item">
                                            <a href="../messages/createMessage.php" class="sidebar-link">Hazır Mesaj Oluştur</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="../messages/config.php" class="sidebar-link">Konfigürasyon</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-header">
                                    Hesap
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
                    <!-- Main Content -->
                    <div class="main">
                        <nav class="navbar navbar-expand px-3 border-bottom">
                            <!-- Button for sidebar toggle -->
                            <button class="btn" type="button" data-bs-theme="dark">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </nav>
                        <main class="content px-3 py-2">
                            <div class="container">
                                <h1>Yeni Kullanıcı Ekle</h1>
                                <form method="POST">
                                    <label for="username">Kullanıcı Adı:</label>
                                    <input type="text" id="username" name="username" required>
                                    <br>
                                    <label for="password">Şifre:</label>
                                    <input type="text" id="password" name="password" required>
                                    <br>
                                    <input type="submit" value="Kullanıcı Ekle">
                                </form>
                            </div>
                        </main>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
                    crossorigin="anonymous"></script>
                <script src="../../js/script.js"></script>
            </body>

            </html>
            <?php
        } else {
            // Yetkiler yetersiz, index sayfasına geri dön
            header("Location: ../../../index.php");
        }
    } else {
        // Kullanıcı bulunamadı, hata mesajı veya işlem yapılabilir
    }

    $stmt->close();
    $db->close();
} else {
    header("Location: ../../../index.php");
}
?>