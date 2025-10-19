<?php
session_start(); // Start session to store user data
include("db.php"); 

// Fetch admin profile picture from DB
$stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE role = 'admin' LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Fallback if admin picture doesn't exist
$admin_profile_pic = $admin['profile_picture'] ?? 'uploads/default_admin.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shadowrealm Horror Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Roboto', sans-serif;
            background: url('<?php echo $admin_profile_pic; ?>') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top:0; left:0;
            width:100%; height:100%;
            background: rgba(0,0,0,0.6); /* dark overlay */
            z-index: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Mist effect */
        .mist {
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle, rgba(255,0,0,0.05), transparent 70%);
            animation: drift 30s infinite linear;
            z-index: 0;
        }

        @keyframes drift {
            0% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(50px) translateY(50px); }
            100% { transform: translateX(0) translateY(0); }
        }

        /* Horror eyes */
        .eye {
            position: absolute;
            width: 12px;
            height: 12px;
            background: radial-gradient(circle, #ff0000, transparent 70%);
            border-radius: 50%;
            box-shadow: 0 0 10px #ff0000, 0 0 20px #ff0000;
            animation: flicker 2s infinite alternate;
        }

        @keyframes flicker {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.3); }
        }

        /* Main container */
        .container {
    position: relative;
    z-index: 1;
    width: 95%;
    max-width: 360px;
    background: rgba(20, 20, 30, 0.5); /* reduced opacity from 0.95 to 0.5 */
    border: 2px solid #ff0000;
    border-radius: 15px;
    padding: 25px 15px;
    text-align: center;
    box-shadow: 0 0 20px rgba(255,0,0,0.5);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

        h1 {
            font-family: 'Creepster', cursive;
            font-size: 2em;
            color: #ff4b4b;
            text-shadow: 0 0 5px #ff0000, 0 0 10px #aa0000;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        p {
            font-size: 1em;
            color: #fff;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        li {
            margin: 10px 0;
        }

        a {
            display: block;
            text-decoration: none;
            background: #1c1c2a;
            color: #fff;
            font-weight: bold;
            padding: 12px 0;
            border-radius: 10px;
            border: 1px solid #ff0000;
            transition: 0.3s ease, transform 0.2s ease;
            width: 100%;
            text-align: center;
        }

        a:hover {
            background: #ff0000;
            color: #000;
            transform: scale(1.05);
            box-shadow: 0 0 10px #ff0000, 0 0 20px #aa0000;
        }

        @media (max-width: 400px) {
            h1 { font-size: 1.8em; }
            p { font-size: 0.95em; }
            a { padding: 10px 0; font-size: 0.95em; }
        }
    </style>
</head>
<body>
    <div class="mist"></div>

    <!-- Horror eyes -->
    <div class="eye" style="top:15%; left:10%; animation-delay: 0s;"></div>
    <div class="eye" style="top:35%; left:75%; animation-delay: 1s;"></div>
    <div class="eye" style="top:55%; left:25%; animation-delay: 2s;"></div>
    <div class="eye" style="top:75%; left:85%; animation-delay: 3s;"></div>
    <div class="eye" style="top:50%; left:50%; animation-delay: 4s;"></div>

    <div class="container">
        <h1>Shadowrealm Portal</h1>
        <p>Enter if you dare. Choose your path:</p>

        <ul>
            <li><a href="register.php">Register a New User</a></li>
            <li><a href="login.php">Log in</a></li>
        </ul>
    </div>
</body>
</html>