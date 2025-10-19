<?php
include('db.php');// Start session to store user data

// Ensure user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details for the given user ID
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user not found, redirect back
    if (!$user) {
        echo "User not found!";
        exit();
    }

    // edit user
    if (isset($_POST['update_user'])) {
        $username = $_POST['username'];
        $email    = $_POST['email'];

        // if mag update required dapat username and email
        if (empty($username) || empty($email)) {
            $error_message = "All fields are required!";
        } else {
            try {
                // Update user details
                $stmt = $pdo->prepare("UPDATE students SET username = :username, email = :email WHERE id = :id");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $success_message = "User updated successfully!";
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
} else {
    echo "No user ID provided!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>Edit User</h2>

    <!-- Display error or success messages -->
    <?php if (isset($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php elseif (isset($success_message)): ?>
        <p class="success"><?= $success_message ?></p>
    <?php endif; ?>

    <!-- User Edit Form -->
    <form action="edit_user.php?id=<?= $user['id'] ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= $user['username'] ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br><br>

        <button type="submit" name="update_user">Update User</button>
    </form>

    <br>
    <a href="view_user.php">Back to Users</a>
</div>
</body>
</html>