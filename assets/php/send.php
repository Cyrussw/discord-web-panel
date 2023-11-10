<?php
session_start();
include("./config.php");

if (isset($_SESSION["id"]) && isset($_SESSION["username"])) {
    $userId = $_SESSION["id"];

    // Kullanıcı yetkilerini kontrol et
    $db = new mysqli("localhost", "root", "", "discord");
    if ($db->connect_error) {
        die("Veritabanına bağlanılamadı: " . $db->connect_error);
    }

    // Mesaj ID'sini al
    $messageId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($messageId) {
        // Mesajı ve ilgili kanal ID'sini veritabanından al
        $sql = "SELECT content, receiverId FROM readymessage WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $messageId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = $result->fetch_assoc();
            $channelId = $message["receiverId"];

            // Bot tokenini veritabanından al
            $sqlBotToken = "SELECT token FROM bot LIMIT 1";
            $resultBotToken = $db->query($sqlBotToken);

            if ($resultBotToken && $rowBotToken = $resultBotToken->fetch_assoc()) {
                $botToken = $rowBotToken["token"];

                $payload = array(
                    "content" => $message["content"]
                );

                $ch = curl_init("https://discord.com/api/v9/channels/{$channelId}/messages");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json",
                    "Authorization: {$botToken}" // Note: Add 'Bot' before the token
                )
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);

                // Check for errors
                if ($response === false) {
                    echo 'Curl error: ' . curl_error($ch);
                } else {
                    // Handle the response as needed
                    header('Location: ../pages/bot/sendMessage.php');
                }

                curl_close($ch);
            } else {
                echo "Bot tokeni bulunamadı.";
            }
        } else {
            echo "Mesaj bulunamadı.";
        }
    } else {
        echo "Mesaj ID'si belirtilmedi.";
    }
} else {
    // Kullanıcı oturumu açık değilse, giriş sayfasına yönlendir
    header("Location: ../../index.php");
    exit();
}
?>