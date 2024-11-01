<?php

include '../src/database/db.php';
session_start();

ini_set('display_erroes', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Email Validation
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";

    
    if (preg_match($pattern, $email)) { 

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            header('Location: register.php?email-exists');
            exit();
        } 
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header('Location: register.php?username-exists');
            exit();
        }
        
        
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $password, $username);
        $stmt->execute();
        
        header('Location: login.php?registered');
        exit();

        

        $stmt->close();
        $conn->close();
    } else {
        header('Location: register.php?invalid-email');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans min-h-screen flex items-center justify-center">

    <div class="bg-black/60 border border-gray-500 rounded-lg shadow-lg p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center mb-4 text-teal-400">Welcome to SammyVerse</h1>
        <h2 class="text-xl font-semibold text-center mb-6 text-gray-300">Register Now!</h2>

        <form action="" method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-lg font-medium text-gray-300">Username:</label>
                <span class="text-red-500 text-sm">
                    <?php echo (isset($_GET['username-exists'])) ? 'Username already exists' : '';?>
                </span>
                <input type="text" name="username" id="username" placeholder="Create Username" required 
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400"/>
            </div>
            <div>
                <label for="firstName" class="block text-lg font-medium text-gray-300">First Name:</label>
                <input type="text" name="firstName" id="firstName" placeholder="Enter First Name" required 
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400"/>
            </div>
            <div>
                <label for="lastName" class="block text-lg font-medium text-gray-300">Last Name:</label>
                <input type="text" name="lastName" id="lastName" placeholder="Enter Last Name" required 
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400"/>
            </div>
            <div>
                <label for="email" class="block text-lg font-medium text-gray-300">Email:</label>
                <span class="text-red-500 text-sm">
                    <?php 
                    echo (isset($_GET['invalid-email'])) ? 'Invalid Email' : '';
                    echo (isset($_GET['email-exists'])) ? 'Email already exists' : '';
                    ?>
                </span>
                <input type="text" name="email" id="email" placeholder="Enter Email" required 
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400"/>
            </div>
            <div>
                <label for="password" class="block text-lg font-medium text-gray-300">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required 
                       class="w-full p-3 rounded border border-gray-600 bg-transparent text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400"/>
            </div>
            <div>
                <button type="submit" name="Register" 
                        class="w-full bg-teal-500 hover:bg-teal-600 text-black font-semibold p-3 rounded mt-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600">
                    Register
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-base text-gray-400">
            Already have an account? <a href="login.php" class="text-teal-400 hover:underline">Login</a> or 
            <a href="#" class="text-teal-400 hover:underline">Forget Password</a>
        </p>
    </div>

</body>
</html>
