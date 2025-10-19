<?php
session_start();  // Start session to store user data

// Include the database connection
include('db.php');

// Kunin admin profile picture
$stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE role = 'admin' LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
$admin_profile_pic = $admin['profile_picture'] ?? 'uploads/default_admin.jpg';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // fetch ang email na naregister para hindi na mag type sa email pag mag lologin
    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // fetch ang lahat email na naregister para hindi na mag type sa email pag mag lologin
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // mag eexecute lang ito pag tama yung email at password na ginamit sa login
    $validUser = null;
    foreach ($users as $user) {
        if (password_verify($password, $user['password'])) {
            $validUser = $user;
            break;
        }
    }
    // diplayed yung data user na nagawa 
    if ($validUser) {
        $_SESSION['user_id'] = $validUser['id'];   $_SESSION['username'] = $validUser['username'];
        $_SESSION['email']   = $validUser['email'];
        $_SESSION['role']    = $validUser['role'];

        // Redirect to intro.php
        header('Location: intro.php');
        exit();
    } else {
        $error = "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 8px;
            outline: none;
            background: #222;
            color: #fff;
        }
        input[type="submit"] {
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
        input[type="submit"]:hover {
            background: #cc0000;
        }
        .error {
            color: #ff6666;
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
        <h2>User Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
            <a href="index.php">⬅ Back to Home</a>
        </form>
    </div>
</body>
</html>