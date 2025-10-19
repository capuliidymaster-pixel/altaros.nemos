<?php
include('db.php');

// Ensure user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user from the database
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to users list after deletion to view_user.php
        header("Location:view_user.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }

} else {
    echo "No user ID provided!";
    exit();
}
?>