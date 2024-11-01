<?php

require '../database/db.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['id'])) {   
    header('Location: /public/login.php');
    exit();
} 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username-update'])) {
    $userId = $_SESSION['id'];
    $username = $_POST['username'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: ../views/settings.php?username-exists');
        exit();
    }else{
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $userId);
        $stmt->execute();
        header('Location: ../views/settings.php?updated');
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname-update'])) {
    $userId = $_SESSION['id'];
    $firstName = $_POST['firstname'];

    $stmt = $conn->prepare("UPDATE users SET firstName = ? WHERE id = ?");
    $stmt->bind_param("si", $firstName, $userId);
    $stmt->execute();
    header('Location: ../views/settings.php?updated');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lastname-update'])) {
    $userId = $_SESSION['id'];
    $lastName = $_POST['lastname']; 

    $stmt = $conn->prepare("UPDATE users SET lastName = ? WHERE id = ?");
    $stmt->bind_param("si", $lastName, $userId);
    $stmt->execute();
    header('Location: ../views/settings.php?updated');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email-update'])) {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: ../views/settings.php?updated');
        exit();
    }else{
        $userId = $_SESSION['id'];        
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        header('Location: ../views/settings.php?updated');
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password-update'])) {
    $password = $_POST['password'];
    $userId = $_SESSION['id'];
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $userId);
    $stmt->execute();
    header('Location: ../views/settings.php?updated');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: /sammyverse/index.php');
    exit();
}
?>

