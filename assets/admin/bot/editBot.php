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
            $editUserId = $_GET["id"];
            $sql = "SELECT * FROM bot WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $editUserId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $stmt->close();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $id = $_POST["id"];
                    $username = $_POST["username"];
                    $token = $_POST["token"];

                    $sql = "UPDATE bot SET 
                            username = ?,
                            token = ? 
                            WHERE id = ?";

                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ssi", $username, $token, $id);

                    if ($stmt->execute()) {
                        header("Location: config.php");
                        exit();
                    } else {
                        echo "Kullanıcı güncelleme hatası: " . $stmt->error;
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
                                    <h1>Kullanıcı Düzenle</h1>
                                    <form method="POST" action="editBot.php?id=<?php echo $editUserId; ?>">
                                        <label for="username">Kullanıcı Adı:</label>
                                        <input type="text" id="username" name="username" value="<?php echo $userData["username"]; ?>"
                                            required>
                                        <br>
                                        <label for="token">Token:</label>
                                        <input type="text" id="token" name="token" value="<?php echo $userData["token"]; ?>" required>
                                        <br>
                                        <input type="hidden" name="id" value="<?php echo $userData["id"]; ?>">
                                        <input type="submit" value="Kullanıcıyı Güncelle">
                                    </form>
                                </div>
                            </div>
                        </main>
                    </body>

                    </html>
                    <?php
                }
            } else {
                echo "Kullanıcı bulunamadı.";
            }
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