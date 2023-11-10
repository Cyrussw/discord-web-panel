<?php
session_start();
include("../../php/config.php");

if (isset($_SESSION["id"]) && isset($_SESSION["username"])) {
    $userId = $_SESSION["id"];
    $isAdmin = false;
    $isOwner = false;

    $db = new mysqli("localhost", "root", "", "discord");

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
            $editMessageId = $_GET["id"];
            $sql = "SELECT * FROM readymessage WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $editMessageId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $messageData = $result->fetch_assoc();
                $stmt->close();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $id = $_POST["id"];
                    $subject = $_POST["subject"];
                    $content = $_POST["content"];
                    $receiverId = $_POST["receiverId"];

                    $sql = "UPDATE readymessage SET 
                            subject = ?,
                            content = ?,
                            receiverId = ?
                            WHERE id = ?";

                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ssii", $subject, $content, $receiverId, $id);

                    if ($stmt->execute()) {
                        header("Location: config.php");
                        exit();
                    } else {
                        echo "Mesaj güncelleme hatası: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
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
                        <main class="content px-3 py-2">
                            <div class="container-fluid">
                                <div class="mb-3">
                                    <h1>Mesaj Düzenle</h1>
                                    <form method="POST" action="editMessage.php?id=<?php echo $editMessageId; ?>">
                                        <label for="subject">Konu:</label>
                                        <input type="text" id="subject" name="subject" value="<?php echo $messageData["subject"]; ?>"
                                            required>
                                        <br>
                                        <label for="content">Mesaj:</label>
                                        <input type="text" id="content" name="content" value="<?php echo $messageData["content"]; ?>"
                                            required>
                                        <br>
                                        <label for="receiverId">Kanal Id:</label>
                                        <input type="text" id="receiverId" name="receiverId"
                                            value="<?php echo $messageData["receiverId"]; ?>" required>
                                        <br>
                                        <input type="hidden" name="id" value="<?php echo $messageData["id"]; ?>">
                                        <input type="submit" value="Mesajı Güncelle">
                                    </form>
                                </div>
                            </div>
                        </main>
                    </body>

                    </html>
                    <?php
                }
            } else {
                echo "Mesaj bulunamadı.";
            }
        } else {
            echo "Hata: Mesaj ID'si belirtilmedi.";
        }
    } else {
        header("Location: ../../../index.php");
    }
} else {
    header("Location: ../../../index.php");
}
?>