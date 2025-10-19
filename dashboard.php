<?php
session_start(); // Start session to store user data
include("db.php");

// Check kung naka-login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$users_id = $_SESSION['user_id'];

// Kunin  si user info kasama profile picture at role
$stmt = $pdo->prepare("SELECT username, profile_picture, role FROM students WHERE id = ?");
$stmt->execute([$users_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Kunin lahat ng uploaded files ng user (id + file_path para sa delete)
$stmt = $pdo->prepare("SELECT id, file_path FROM file_uploads WHERE user_id = ? ORDER BY id ASC");
$stmt->execute([$users_id]);
$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        /* Original CSS mo dito */
        body { font-family: "Segoe UI", Arial, sans-serif; background: #eef2f7; margin: 0; padding: 0; }
        .container { width: 90%; max-width: 1000px; margin: 40px auto; }
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 20px; margin-bottom: 30px; }
        .profile-pic { text-align: center; }
        .profile-pic img { width: 140px; height: 140px; border-radius: 50%; border: 4px solid #007bff; object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
        form { text-align: center; margin-top: 15px; }
        input[type="file"] { display: block; margin: 10px auto; }
        button { padding: 10px 18px; background: #007bff; color: white; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #0056b3; }
        .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 18px; margin-top: 20px; }
        .gallery-item { position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.15); background: #fff; padding: 10px; }
        .gallery-item img { width: 100%; height: 140px; object-fit: cover; display: block; border-radius: 12px; transition: transform 0.3s; }
        .gallery-item img:hover { transform: scale(1.05); }
        .section-title { margin-bottom: 15px; font-size: 20px; color: #444; border-left: 4px solid #007bff; padding-left: 10px; }
    </style>
</head>
<body>
<div class="container">
  <div class="card">
    <h3 class="section-title">Profile Picture</h3>
    <div class="profile-pic">
        <?php if (!empty($user['profile_picture'])): ?>
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
        <?php else: ?>
            <img src="default.png" alt="Default Profile Picture">
        <?php endif; ?>
    </div>

    <h2 style="text-align:center; margin-top:15px;">
        Welcome, <?php echo htmlspecialchars($user['username']); ?>
    </h2>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_pic" required>
        <button type="submit">Upload Profile</button>
    </form>
  </div>

  <div class="card">
      <h3 class="section-title">Upload File</h3>
      <form action="upload.php" method="POST" enctype="multipart/form-data">
          <input type="file" name="file" required>
          <button type="submit">Upload Image</button>
      </form>

      <h3 class="section-title">My Uploaded Files</h3>
      <div class="gallery">
          <?php if ($uploads): ?>
              <?php foreach ($uploads as $upload): ?>
                  <div class="gallery-item">
                      <img src="<?php echo htmlspecialchars($upload['file_path']); ?>" alt="Upload">
                      <form action="delete_file.php" method="GET" style="text-align:center; margin-top:5px;">
                          <input type="hidden" name="id" value="<?php echo $upload['id']; ?>">
                          <button type="submit" style="background:#e74c3c; padding:6px 10px; border:none; border-radius:6px; color:white; cursor:pointer;">Delete</button>
                      </form>
                  </div>
              <?php endforeach; ?>
          <?php else: ?>
              <p style="text-align:center; color:#666;">No uploaded files yet.</p>
          <?php endif; ?>
      </div>
  </div>
  
  <a href="logout.php?id=<?= $_SESSION['user_id'] ?>">Log out</a>

  <!-- Admin-only View Users link -->
  <?php if ($user['role'] === 'admin'): ?>
      <div class="card" style="text-align:center;">
          <a href="view_user.php" style="padding:10px 20px; background:#28a745; color:white; border-radius:8px; text-decoration:none;">View All Users</a>
      </div>
  <?php endif; ?>
  	<?php if ($_SESSION['role'] === 'user'): ?>
    <div class="card" style="text-align:center;">
        <a href="switch_acc.php" style="padding:10px 20px; background:#f39c12; color:white; border-radius:8px; text-decoration:none;">Switch Account</a>
    </div>
<?php endif; ?>
</div>
</body>
</html>