<?php
session_start(); // Start session to store user data
include("db.php");

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Only allow admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php"); // redirect non-admin back
    exit();
}

// Fetch all users with role and created_at
$stmt = $pdo->query("SELECT id, username, email, role, created_at FROM students");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Users</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>View All Registered Users</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['username'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><?= $u['role'] ?></td>
            <td><?= $u['created_at'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $u['id'] ?>">Edit</a> | 
                <a href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>