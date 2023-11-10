<?php
session_start();
include("../php/config.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $uname = $_POST['username'];
    $password = $_POST['password'];

    if (empty($uname) || empty($password)) {
        header("Location: ../../index.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :uname AND password = :password");
        $stmt->bindParam(':uname', $uname, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "Başarıyla Giriş Yapıldı!";
            $_SESSION["username"] = $row["username"];
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header("Location: index.php");
            exit();
        } else {
            header("Location: ../../index.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Veritabanı Hatası: " . $e->getMessage();
    }
}
?>