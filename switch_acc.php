<?php
session_start();
include("db.php");

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_email   = $_SESSION['email']; // dapat naka-save email sa session sa login.php

// Kunin lahat ng accounts na may kaparehong email
$stmt = $pdo->prepare("SELECT id, username, role FROM students WHERE email = ? AND id != ?");
$stmt->execute([$current_email, $current_user_id]);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Switch Account</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>Switch Account</h2>

    <?php if ($accounts): ?>
        <p>Choose another account with the same email (<?= htmlspecialchars($current_email) ?>):</p>
        <ul>
            <?php foreach ($accounts as $acc): ?>
                <li>
                    <a href="switch_acc_process.php?id=<?= $acc['id'] ?>">
                        <?= htmlspecialchars($acc['username']) ?> (<?= htmlspecialchars($acc['role']) ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No other accounts found for this email.</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>