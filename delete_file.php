<?php
session_start();
include("db.php");

// Check kung naka-login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check kung may file ID
if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    try {
        // Kunin file path mula DB
        $stmt = $pdo->prepare("SELECT file_path FROM file_uploads WHERE id = ? AND user_id = ?");
        $stmt->execute([$file_id, $user_id]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file) {
            $file_path = $file['file_path'];

            // Burahin sa DB
            $stmt = $pdo->prepare("DELETE FROM file_uploads WHERE id = ? AND user_id = ?");
            $stmt->execute([$file_id, $user_id]);

            // Burahin din yung file sa folder kung existing
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "No file ID provided!";
    exit();
}
?>