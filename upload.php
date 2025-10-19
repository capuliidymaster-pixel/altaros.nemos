<?php
session_start();
include("db.php");

// Check kung naka-login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// === Profile Picture Upload ===
if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] === 0) {
    $target_dir = __DIR__ . "/uploads/"; // absolute path
    $db_dir = "uploads/"; // relative path for database

    // create folder if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // sanitize file name para iwas special chars
    $file_name = uniqid("pp_") . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["profile_pic"]["name"]));
    $target_file = $target_dir . $file_name;
    $db_path = $db_dir . $file_name;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check kung valid image
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
    }

    // Limit size (2MB)
    if ($_FILES["profile_pic"]["size"] > 2000000) {
        $uploadOk = 0;
    }

    // Allowed extensions
    $allowedExt = ["jpg", "jpeg", "png", "gif", "webp"];
    if (!in_array($imageFileType, $allowedExt)) {
        $uploadOk = 0;
    }

    // Upload kung pasado
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            // update DB
            $stmt = $pdo->prepare("UPDATE students SET profile_picture = ? WHERE id = ?");
            $stmt->execute([$db_path, $user_id]);
        } else {
            die("❌ Upload failed! Check folder permission sa uploads/");
        }
    } else {
        die("❌ File not allowed or too large.");
    }

    header("Location: dashboard.php");
    exit();
}

// === Gallery File Upload ===
if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
    $target_dir = __DIR__ . "/uploads/";
    $db_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = uniqid("gal_") . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['file']['name']));
    $target_file = $target_dir . $file_name;
    $db_path = $db_dir . $file_name;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO file_uploads (user_id, file_path) VALUES (?, ?)");
            $stmt->execute([$user_id, $db_path]);
        } else {
            die(" Upload failed sa gallery!");
        }
    }
    header("Location: dashboard.php");
    exit();
}
?>
