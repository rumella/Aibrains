<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-in-container">
            <form action="login.php" method="POST">
                <h1>Giriş</h1>
                <div class="social-container">
                    <a href="#" class="social facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social google" target="_blank"><i class="fab fa-google"></i></a>
                    <a href="#" class="social linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>Veya hesabınızı kullanın</span>
                <input type="text" name="fullname" placeholder="Adınızı girin" required>
                <input type="email" name="email" placeholder="E-posta adresinizi girin" required>
                <input type="password" name="password" placeholder="Şifrenizi girin" required>
                <button type="submit">Giriş Yap</button>
            </form>
        </div>
        <div class="form-container sign-up-container">
            <form action="register.php" method="POST">
                <h1>Bize Katılın</h1>
                <div class="social-container">
                    <a href="#" class="social facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social google" target="_blank"><i class="fab fa-google"></i></a>
                    <a href="#" class="social linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>Veya kayıt için e-postanızı kullanın</span>
                <input type="text" name="fullname" placeholder="Adınızı girin" required>
                <input type="email" name="email" placeholder="E-posta adresinizi girin" required>
                <input type="password" name="password" placeholder="Şifre oluşturun" autocomplete="new-password" required>
                <button type="submit">Kayıt Ol</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Merhaba!</h1>
                    <img src="kisi.png" style="width: 250px; margin-top: 20px;" alt="Kişi İkonu">
                    <p>Bizimle bağlantıda kalmak için giriş yapın</p>
                    <button class="ghost" id="signIn">Giriş Yap</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Merhaba!</h1>
                    <img src="mail.png" style="width: 250px; margin-top: 20px;" alt="Mail İkonu">
                    <p>Kaydolun ve topluluğumuza katılın</p>
                    <button class="ghost" id="signUp">Üye Ol</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
