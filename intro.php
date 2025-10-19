<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kunin username (fallback email kung wala)
$username = $_SESSION['username'] ?? $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scary Intro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', monospace;
            background: black;
            color: #ff1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            animation: flickerBg 4s infinite;
        }
        .intro {
            text-align: center;
            font-size: 34px;
            white-space: pre-wrap;
            border-right: 2px solid #ff1a1a;
            text-shadow: 
                0 0 5px #ff0000, 
                0 0 15px #ff0000, 
                0 0 30px #990000,
                0 0 60px #330000;
            animation: blink 0.7s infinite, glitchShake 0.2s infinite;
        }
        @keyframes blink {
            0%, 100% { border-color: transparent }
            50% { border-color: #ff1a1a }
        }
        @keyframes flickerBg {
            0% { background-color: black; }
            5% { background-color: #1a0000; }
            10% { background-color: black; }
            15% { background-color: #0d0000; }
            25% { background-color: black; }
            30% { background-color: #330000; }
            100% { background-color: black; }
        }
        @keyframes glitchShake {
            0% { transform: translate(0, 0) }
            20% { transform: translate(-1px, 1px) }
            40% { transform: translate(-2px, -1px) }
            60% { transform: translate(2px, 1px) }
            80% { transform: translate(1px, -2px) }
            100% { transform: translate(0, 0) }
        }
    </style>
    <script>
        // Redirect after 6.5 seconds
        setTimeout(function() {
            window.location.href = "dashboard.php";
        }, 6500);
    </script>
</head>
<body>
  <div class="intro" id="introText"></div>

<script>
    const username = "<?php echo htmlspecialchars($username); ?>";
    const element = document.getElementById("introText");
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";

    let iteration = 0;
    let displayText = "";
    const typingSpeed = 150;   // delay bago lumipat sa susunod na letter
    const scrambleSpeed = 40;  // bilis ng pagpalit ng random chars

    function typeWriter() {
        if (iteration < username.length) {
            let currentChar = username[iteration];
            let scrambleCount = 0;

            let scrambleInterval = setInterval(() => {
                element.innerText = "W e l c o m e  b a c k , " + displayText +
                    letters[Math.floor(Math.random() * letters.length)] + "...";

                scrambleCount++;
                if (scrambleCount > 6) { // ilang beses mag-glitch bago lumabas yung totoong letter
                    clearInterval(scrambleInterval);
                    displayText += currentChar;
                    element.innerText = "W e l c o m e  back , " + displayText + "...";
                    iteration++;
                    setTimeout(typeWriter, typingSpeed);
                }
            }, scrambleSpeed);
        }
    }

    typeWriter();
</script>
</body>
</html>