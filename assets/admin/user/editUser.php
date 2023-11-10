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
            $editUserId = $_GET["id"];
            $sql = "SELECT * FROM users WHERE id = ?";
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
                    $password = $_POST["password"];

                    $isBanned = isset($_POST["isBanned"]) ? 1 : 0;
                    $isTester = isset($_POST["isTester"]) ? 1 : 0;
                    $isPremium = isset($_POST["isPremium"]) ? 1 : 0;
                    $isAdmin = isset($_POST["isAdmin"]) ? 1 : 0;
                    $isOwner = isset($_POST["isOwner"]) ? 1 : 0;

                    $sql = "UPDATE users SET 
                            username = ?,
                            password = ?,
                            isBanned = ?,
                            isTester = ?,
                            isPremium = ?,
                            isAdmin = ?,
                            isOwner = ?
                            WHERE id = ?";

                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ssiisiii", $username, $password, $isBanned, $isTester, $isPremium, $isAdmin, $isOwner, $id);

                    if ($stmt->execute()) {
                        header("Location: remoteUser.php");
                        exit();
                    } else {
                        echo "Kullanıcı güncelleme hatası: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    // Kullanıcının bilgilerini kullanarak düzenleme sayfasını görüntüle
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
                                    <form method="POST" action="editUser.php?id=<?php echo $editUserId; ?>">
                                        <label for="username">Kullanıcı Adı:</label>
                                        <input type="text" id="username" name="username" value="<?php echo $userData["username"]; ?>"
                                            required>
                                        <br>
                                        <label for="password">Şifre:</label>
                                        <input type="text" id="password" name="password" value="<?php echo $userData["password"]; ?>"
                                            required>
                                        <br>
                                        <label for="isBanned">Banlı mı?</label>
                                        <input type="checkbox" id="isBanned" name="isBanned" value="1" <?php echo $userData["isBanned"] ? "checked" : ""; ?>>
                                        <br>
                                        <label for="isTester">Tester mı?</label>
                                        <input type="checkbox" id="isTester" name="isTester" value="1" <?php echo $userData["isTester"] ? "checked" : ""; ?>>
                                        <br>
                                        <label for="isPremium">Premium mu?</label>
                                        <input type="checkbox" id="isPremium" name="isPremium" value="1" <?php echo $userData["isPremium"] ? "checked" : ""; ?>>
                                        <br>
                                        <label for="isAdmin">Admin mi?</label>
                                        <input type="checkbox" id="isAdmin" name="isAdmin" value="1" <?php echo $userData["isAdmin"] ? "checked" : ""; ?>>
                                        <br>
                                        <label for="isOwner">Sahip mi?</label>
                                        <input type="checkbox" id="isOwner" name="isOwner" value="1" <?php echo $userData["isOwner"] ? "checked" : ""; ?>>
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