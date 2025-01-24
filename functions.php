<?php
// functions.php
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'UTF-8');


require_once __DIR__ . '/config.php'; // Dosya yolu kesinleştirildi

// Kullanıcıya özel selamlama
function getUserGreeting($username) {
    return $username !== 'Misafir' ? "$username Hanım/Bey" : "Misafir";
}

// Tüm kategorileri getirme
function getCategories() {
    $conn = getDatabaseConnection();
    $sql = "SELECT id, name FROM categories";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    } else {
        return [];
    }

    $conn->close();
}


// Yeni blog yazısı ekleme
function addBlogPost($userId, $title, $content, $status, $categoryId) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO blog_post (user_id, title, content, created_at, status, category_id) VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("isssi", $userId, $title, $content, $status, $categoryId);

    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }

    $stmt->close();
    $conn->close();
}


// Blog yazısı güncelleme
function updateBlogPost($id, $title, $content) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("UPDATE blog_post SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Blog yazısı silme
function deleteBlogPost($id) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("DELETE FROM blog_post WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Tüm blog yazılarını getirme
function getBlogPosts() {
    $conn = getDatabaseConnection();
    $sql = "SELECT blog_post.id, blog_post.user_id, blog_post.title, blog_post.content, blog_post.created_at, blog_post.status, categories.name AS category_name
            FROM blog_post
            LEFT JOIN categories ON blog_post.category_id = categories.id
            ORDER BY blog_post.created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    } else {
        return [];
    }

    $conn->close();
}


// Blog yazısı ekleme işlevini çağırır
function handleAddPost($data) {
    $userId = $_SESSION['user_id'] ?? 0;
    $title = trim($data['title']);
    $content = trim($data['content']);
    $status = $data['status'] ?? 'draft';
    $categoryId = intval($data['category_id'] ?? 0);

    if (empty($title) || empty($content) || $categoryId <= 0) {
        die('Başlık, içerik ve kategori boş olamaz.');
    }

    $result = addBlogPost($userId, $title, $content, $status, $categoryId);
    if ($result === true) {
        echo "<script>alert('Yeni blog yazısı başarıyla eklendi.');</script>";
        echo "<script>window.location.href='blogger.php';</script>";
    } else {
        die("Hata: " . $result);
    }
}


// Blog yazısı güncelleme işlevini çağırır
function handleUpdatePost($data) {
    $id = intval($data['id']);
    $title = trim($data['title']);
    $content = trim($data['content']);

    if (empty($title) || empty($content)) {
        die('Başlık ve içerik boş olamaz.');
    }

    $result = updateBlogPost($id, $title, $content);
    if ($result === true) {
        echo "<script>alert('Blog yazısı başarıyla güncellendi.');</script>";
        echo "<script>window.location.href='blogger.php';</script>";
    } else {
        die("Hata: " . $result);
    }
}
?>
