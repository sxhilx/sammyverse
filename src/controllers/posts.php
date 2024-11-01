<?php

require '../database/db.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id'])) {
    header('Location: /public/index.php'); 
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update']) && !isset($_POST['delete'])) {
    $post = $_POST['content'];
    $dateTime = date('Y-m-d H:i:s');
    $userId = $_SESSION['id'];


    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $post, $dateTime);
    $stmt->execute();
    $stmt->close();

    header('Location: ../views/profile.php');
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid post ID";
    header('Location: ../views/profile.php');
    exit();
}

$postId = $_GET['id'];

// Handle post update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    if (empty($postId)) {
        $_SESSION['error'] = "Post ID is required";
        header('Location: ../views/profile.php');
        exit();
    }
    
    $newContent = $_POST['content'];
    
    // Verify the post exists and belongs to the current user
    $stmt = $conn->prepare("UPDATE posts SET content = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $newContent, $postId, $_SESSION['id']);
    $stmt->execute();


    
    if ($stmt->affected_rows > 0) {
        $_SESSION['success'] = "Post updated successfully";
    } else {
        $_SESSION['error'] = "Unable to update post";
    }
    
    $stmt->close();
    header('Location: ../views/profile.php');
    exit();
}

// Delete post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    if (empty($postId)) {
        $_SESSION['error'] = "Post ID is required";
        header('Location: ../views/profile.php');
        exit();
    }
    
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $postId, $_SESSION['id']);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $_SESSION['success'] = "Post deleted successfully";
    } else {
        $_SESSION['error'] = "Unable to delete post";
    }
    
    $stmt->close();
    header('Location: ../views/profile.php');    
    exit();
}

?>