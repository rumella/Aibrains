<?php
session_start();

// Kullanıcı zaten giriş yapmışsa blogger sayfasına yönlendir
if (isset($_SESSION['user_id'])) {
    header("Location: blogger.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Form doğrulamaları
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi giriniz.";
    } elseif (empty($password)) {
        $error = "Şifre alanı boş bırakılamaz.";
    } else {
        // Veritabanı bağlantı bilgileri
        $host = 'sql310.infinityfree.com';
        $db = 'if0_37988276_logindb';
        $user = 'if0_37988276';
        $pass = 'q9MEx8lZgy';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Kullanıcıyı e-posta adresine göre sorgula
            $stmt = $pdo->prepare("SELECT id, fullname, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Şifre doğrulama
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname']; // Fullname oturuma ekleniyor
                header("Location: blogger.php");
                exit();
            } else {
                $error = "E-posta veya şifre hatalı.";
            }
        } catch (PDOException $e) {
            $error = "Veritabanı bağlantı hatası: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h1>Giriş Yap</h1>
    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label for="email">E-posta:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Giriş Yap</button>
    </form>
    <p>Hesabınız yok mu? <a href="register.php">Kayıt Olun</a></p>
</div>
</body>
</html>
