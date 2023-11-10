<?php
session_start();
include("./config.php");

if (isset($_SESSION['username'])) {
    // Oturumu sonlandır
    session_unset();
    session_destroy();
    header("Location: ../../index.php"); // Çıkış yaptıktan sonra yönlendirilecek sayfa
    exit();
} else {
    // Kullanıcı oturumu zaten kapalıysa ya da oturum açılmamışsa başka bir işlem yapabilirsiniz.
    header("Location: ../../index.php");
    exit();
}
?>