<?php
include('db.php');

// Kunin admin profile picture
$stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE role = 'admin' LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
$admin_profile_pic = $admin['profile_picture'] ?? 'uploads/default_admin.jpg';

//declare variable for registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // dapat i fillup lahat para makapag register
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $error_message = "All fields are required!";
    } else {
        //dapat naka hash yung password para hindi kita ng iba at ikaw lang ang mayalam
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $profile_picture = "default.png";

            // Check kung may existing admin
            if ($role === 'admin') {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE role = 'admin'");
                $stmt->execute();
                $admin_count = $stmt->fetchColumn();
                // Dapat mag-isa lang si admin
                if ($admin_count > 0) {
                    $error_message = "An Admin already exists. You cannot register another Admin!";
                }
            }
            //pagbabind para maiconnect sa phpmyadmin
            if (!isset($error_message)) {
                $stmt = $pdo->prepare("INSERT INTO students (username, email, password, role, profile_picture) 
                                       VALUES (:username, :email, :password, :role, :profile_picture)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':profile_picture', $profile_picture);
                $stmt->execute();
                $success_message = "User registered successfully!";
            }
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Registration</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Uncial Antiqua', serif;
        background: url('<?= htmlspecialchars($admin_profile_pic) ?>') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #fff;
    }
    .container {
        background: rgba(0, 0, 0, 0.7);
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.6);
    }
    h2 {
        margin-bottom: 20px;
        color: #ff4d4d;
        text-shadow: 0 0 10px #ff1a1a;
    }
    label {
        display: block;
        margin-top: 10px;
        text-align: left;
        color: #ffcccc;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: none;
        border-radius: 8px;
        outline: none;
        background: #222;
        color: #fff;
    }
    button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Uncial Antiqua', serif;
        background: #ff1a1a;
        color: #fff;
        font-size: 16px;
        transition: 0.3s;
    }
    button:hover {
        background: #cc0000;
    }
    .error {
        color: #ff6666;
        margin-bottom: 15px;
    }
    .success {
        color: #66ff66;
        margin-bottom: 15px;
    }
    a {
        display: inline-block;
        margin-top: 15px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }
    a:hover {
        color: #ff4d4d;
    }
</style>
</head>
<body>
<div class="container">
    <h2>User Registration</h2>
    <?php if (isset($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php elseif (isset($success_message)): ?>
        <p class="success"><?= $success_message ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="register">Register</button>
        <a href="index.php">← Back to Home</a>
    </form>
</div>
</body>
</html>