<?php
session_start();
include("../../php/config.php");

if (isset($_SESSION["id"]) && isset($_SESSION["username"])) {
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

    $sql = "SELECT isAdmin, isOwner FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($isAdmin, $isOwner);
    $stmt->fetch();
    $stmt->close();

    if ($isAdmin == 1 || $isOwner == 1) {
        if (isset($_GET["id"])) {
            $banUserId = $_GET["id"];

            // Kullanıcıyı yasakla (isBanned değerini 1 yap)
            $sql = "UPDATE users SET isBanned = 1 WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $banUserId);

            if ($stmt->execute()) {
                // Kullanıcı başarıyla yasaklandı
                header("Location: remoteUser.php"); // Kullanıcı listesi sayfasına yönlendirin.
                exit();
            } else {
                echo "Kullanıcı yasaklama hatası: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Kullanıcı ID'si belirtilmedi.";
        }
    } else {
        header("Location: ../../../index.php");
    }
} else {
    header("Location: ../../../index.php");
}
?>