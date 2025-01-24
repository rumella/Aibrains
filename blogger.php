<?php
header('Content-Type: text/html; charset=utf-8'); // UTF-8 çıktısını zorunlu yap
ini_set('default_charset', 'UTF-8'); // PHP'nin varsayılan karakter setini UTF-8 yap
require_once 'config.php';
require_once 'functions.php';
session_start();

// Kullanıcı oturumu kontrolü
if (!isset($_SESSION['fullname'])) {
    $_SESSION['fullname'] = 'Misafir';
}

$loggedInFullname = htmlspecialchars($_SESSION['fullname'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');

// CSRF token kontrolü ve oluşturma
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}



$loggedInFullname = htmlspecialchars($_SESSION['fullname']);
$greeting = $loggedInFullname !== 'Misafir'
    ? "Hoşgeldiniz $loggedInFullname Hanım"
    : "Hoşgeldiniz Misafir Blogger";

// POST isteği işlem kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF doğrulaması başarısız oldu.');
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            handleAddPost($_POST);
            break;
        case 'update':
            handleUpdatePost($_POST);
            break;
        default:
            echo "Geçersiz işlem.";
            break;
    }

    // İşlem tamamlandıktan sonra kullanıcıyı aynı sayfaya yönlendir
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
    
}

// Silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $postId = intval($_GET['delete']);
    deleteBlogPost($postId);
}

$blogPosts = getBlogPosts();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>
<body>
<header>
    <h1>Blogger</h1>
    <div class="welcome">
        <?= $greeting; ?>
    </div>
    <a href="logout.php">Çıkış Yap</a>
</header>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide"><img src="foto01.jpg" alt="Resim 1"></div>
        <div class="swiper-slide"><img src="website.jpg" alt="Resim 2"></div>
        <div class="swiper-slide"><img src="yapayzeka.jpg" alt="Resim 3"></div>
        <div class="swiper-slide"><img src="designer.jpg" alt="Resim 3"></div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
<div class="container">
    <!-- Yeni Blog Yazısı Ekle -->
    <div class="form-container">
        <h2>Yeni Blog Yazısı Ekle</h2>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <label for="addTitle">Başlık:</label>
        <input type="text" name="title" id="addTitle" required>
        <label for="addContent">İçerik:</label>
        <textarea name="content" id="addContent" rows="5" required></textarea>
        <label for="addCategory">Kategori:</label>
        <select name="category_id" id="addCategory" required>
            <option value="">Kategori Seçin</option>
            <?php foreach (getCategories() as $category): ?>
                <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="addStatus">Durum:</label>
        <select name="status" id="addStatus" required>
            <option value="draft">Taslak</option>
            <option value="published">Yayınla</option>
        </select>
        <input type="hidden" name="action" value="add">
        <button type="submit">Ekle</button>
    </form>
    </div>

    <!-- Blog Yazıları -->
    <?php if ($blogPosts): ?>
        <?php foreach ($blogPosts as $post): ?>
            <div class="blog-post">
                <h2><?= htmlspecialchars($post['title']); ?></h2>
                <p>Yazan: <strong><?= htmlspecialchars($loggedInFullname); ?></strong></p>
                <p class="date">Yayınlanma tarihi: <?= htmlspecialchars($post['created_at']); ?></p>
                <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                <p>Durum: <?= htmlspecialchars($post['status']); ?></p>
                <div class="actions">
                    <a href="?delete=<?= $post['id']; ?>" onclick="return confirm('Bu yazıyı silmek istediğinize emin misiniz?')">Sil</a>
                    <button onclick="showUpdateModal(<?= $post['id']; ?>, '<?= htmlspecialchars($post['title']); ?>', '<?= htmlspecialchars($post['content']); ?>')">Güncelle</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">Henüz yayınlanmış blog yazısı bulunmamaktadır.</p>
    <?php endif; ?>
</div>

<!-- Güncelleme Modal -->
<div id="updateModal" class="modal">
    <div class="modal">
        <h2>Blog Yazısını Güncelle</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" id="updateId">
            <label for="updateTitle">Başlık:</label>
            <input type="text" name="title" id="updateTitle" required>
            <label for="updateContent">İçerik:</label>
            <textarea name="content" id="updateContent" rows="5" required></textarea>
            <input type="hidden" name="action" value="update">
            <button type="submit">Kaydet</button>
            <button type="button" onclick="closeUpdateModal()">İptal</button>
        </form>
    </div>
</div>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper-container', {
        loop: true,
        slidesPerView: 1, // Tek slayt göster
        spaceBetween: 20,
        autoplay: {
            delay: 3000, // 3 saniyede bir geçiş
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    document.addEventListener("DOMContentLoaded", function() {
    // Sayfa yüklendiğinde modalı gizle
    document.getElementById('updateModal').style.display = 'none';
});
    function showUpdateModal(id, title, content) {
        document.getElementById('updateModal').style.display = 'block';
        document.getElementById('updateId').value = id;
        document.getElementById('updateTitle').value = title;
        document.getElementById('updateContent').value = content;
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').style.display = 'none';
    }
</script>
</body>
</html>