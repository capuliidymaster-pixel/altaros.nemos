<?php
session_start();
include("db.php");

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$new_user_id = $_GET['id'] ?? null;
$current_email = $_SESSION['email'];

//switch account
if (!$new_user_id) {
    header("Location: switch_acc.php");
    exit();
}

// Kunin info ng bagong account pero siguraduhin same email
$stmt = $pdo->prepare("SELECT id, username, email, role FROM students WHERE id = ? AND email = ?");
$stmt->execute([$new_user_id, $current_email]);
$new_user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($new_user) {
    // Update session para sa bagong account
    $_SESSION['user_id']  = $new_user['id'];
    $_SESSION['username'] = $new_user['username'];
    $_SESSION['email']    = $new_user['email'];
    $_SESSION['role']     = $new_user['role'];

    header("Location: dashboard.php");
    exit();
} else {
    echo "Invalid account switch!";
}