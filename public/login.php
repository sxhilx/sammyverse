<?php

require '../src/database/db.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($conn)) {
    die("Database connection not established.");
}

$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailOrUsername = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE (email = '$emailOrUsername' OR username = '$emailOrUsername') AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();        
        $_SESSION['email'] = $row['email'];
        $_SESSION['firstName'] = $row['firstName'];
        $_SESSION['lastName'] = $row['lastName'];  
        $_SESSION['id'] = $row['id'];
        header("Location: /sammyverse/index.php");
        exit();            
        
    } else {
        header("Location: /sammyverse/public/login.php?invalid");
        exit();
    }

    $conn->close();
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans flex items-center justify-center min-h-screen">

    <div class="container bg-black/60 border border-gray-500 rounded-lg shadow-lg p-8 w-full max-w-md flex flex-col items-center">
        <?php
            if(isset($_GET['invalid'])) {
                echo '<span class="block w-full bg-red-400 text-white font-bold text-center p-3 rounded-lg shadow-md mb-6">Invalid credentials</span>';
            }else if(isset($_GET['registered'])) {
                echo '<span class="block w-full bg-teal-300 text-black font-bold text-center p-3 rounded-lg shadow-md mb-6">Registered successfully Please login</span>';
            }
        ?>
        <h1 class=" text-xl md:text-3xl font-bold text-teal-400 mb-2">Welcome to SammyVerse</h1>
        <h2 class="text-xl font-semibold text-gray-300 mb-6">Login</h2>

        <form action="" method="post" class="w-full space-y-4">
            <div class="email">
                <label for="email" class="block text-lg font-medium text-gray-300">Email:</label>
                <input type="text" name="email" placeholder="Enter Email or Username" required
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400 mt-2"/>
            </div>
            <div class="password">
                <label for="password" class="block text-lg font-medium text-gray-300">Password:</label>
                <input type="password" name="password" placeholder="Enter Password" required
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400 mt-2"/>
            </div>
            <div class="button">
                <button type="submit" name="login"
                        class="w-full bg-teal-500 hover:bg-teal-600 text-black font-semibold p-3 rounded mt-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600">
                    Login
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-base text-gray-400">
            Don't have an account? <a href="register.php" class="text-teal-400 hover:underline">Register</a>
        </p>
    </div>

</body>
</html>
