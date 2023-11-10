<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Sairus</title>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="assets/pages/login.php" method="post">
        <h3>Giriş</h3>

        <label for="username">Kullanıcı Adı</label>
        <input type="text" name="username" placeholder="Kullanıcı Adı" id="username"><br>
        <label for="username">Şifre</label>
        <input type="password" name="password" placeholder="Şifre" id="password"><br>
        <button type="submit">Giriş</button>
    </form>
</body>

</html>