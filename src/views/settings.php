<?php

require '../database/db.php';

session_start();

if(!isset($_SESSION['id'])) {   
    header('Location: /public/login.php');
    exit();
}

$userId = $_SESSION['id'];


// Fetch user data
$stmt = $conn->prepare("SELECT username, firstName, lastName, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}




?>

<?php include '../includes/header.php'; ?>

<div class="">
    <h1 class="text-3xl font-bold mb-2">Settings</h1>
    <hr class="mb-6 bg-slate-500">

    <div class="">
    <h3 class="text-2xl font-bold mb-6">Update Profile</h3>


    <div class="text-lg font-bold mb-6 border border-gray-500 rounded shadow-lg p-6 mt-6 overflow-hidden">
    <?php
        if (isset($_GET['username-exists'])) {
            echo '<span class="block w-full bg-red-400 text-white text-center p-3 rounded-lg shadow-md mb-6">Username already exists</span>';
        } else if (isset($_GET['email-exists'])) {
            echo '<span class="block w-full bg-red-400 text-white text-center p-3 rounded-lg shadow-md mb-6">Email already exists</span>';
        } else if (isset($_GET['updated'])) {
            echo '<span class="block w-full bg-green-400 text-white text-center p-3 rounded-lg shadow-md mb-6">Profile updated successfully</span>';
        }
    ?>


        <h2 class="text-xl font-bold mb-6">Account Settings</h2>

    

        <form action="../controllers/profile-update.php" method="POST">
            <label for="username">Username:</label><br>
            <div class="flex w-full mb-6">
            <input type="text" placeholder="<?php echo $user['username']; ?>" name="username" class="flex-1 p-3 bg-[#000000]  text-slate-100 text-lg rounded-l border border-gray-500 border-r-0 outline-none">
            <button class="p-3 bg-teal-500 text-white text-lg rounded-r hover:bg-teal-600 transition ease-in-out duration-200"  type="submit" name="username-update">
                Update
            </button>
            </div>
        </form>

        <form action="../controllers/profile-update.php" method="POST">
            <label for="name">Firstname: </label>
            <div class="flex w-full mb-6">
            <input type="text" placeholder="<?php echo $user['firstName']; ?>" name="firstname" class="flex-1 p-3 bg-[#000000]  text-slate-100 text-lg rounded-l border border-gray-500 border-r-0 outline-none">
            <button class="p-3 bg-teal-500 text-white text-lg rounded-r hover:bg-teal-600 transition ease-in-out duration-200"  type="submit" name="firstname-update">
                Update
            </button>
            </div>
        </form>

        <form action="../controllers/profile-update.php" method="POST">
            <label for="name">Lastname:</label>
            <div class="flex w-full mb-6">
            <input type="text" placeholder="<?php echo $user['lastName']; ?>" name="lastname" class="flex-1 p-3 bg-[#000000]  text-slate-100 text-lg rounded-l border border-gray-500 border-r-0 outline-none">
            <button class="p-3 bg-teal-500 text-white text-lg rounded-r hover:bg-teal-600 transition ease-in-out duration-200"  type="submit" name="lastname-update">
                Update
            </button>
            </div>
        </form>

        <form action="../controllers/profile-update.php" method="POST">
            <label for="email" class="block mb-2 text-white">Email:</label>
            <div class="flex w-full mb-6">
                <input type="text" placeholder="<?php echo $user['email']; ?>" name="email" class="flex-1 p-3 bg-[#000000]  text-slate-100 text-lg rounded-l border border-gray-500 border-r-0 outline-none">
                <button type="submit" name="email-update" class="p-3 bg-teal-500 text-white text-lg rounded-r hover:bg-teal-600 transition ease-in-out duration-200">
                    Update
                </button>
            </div>
        </form>


        <form action="../controllers/profile-update.php" method="POST">
            <label for="username">Password:</label><br>
            <div class="flex w-full mb-6">
            <input type="text" placeholder="" name="password" class="flex-1 p-3 bg-[#000000]  text-slate-100 text-lg rounded-l border border-gray-500 border-r-0 outline-none">
            <button class="p-3 bg-teal-500 text-white text-lg rounded-r hover:bg-teal-600 transition ease-in-out duration-200"  type="submit" name="password-update">
                Update
            </button>
            </div>
        </form>
</div>


    <div class="bg-[#000000] p-6 rounded-lg shadow-lg border border-gray-500">

        <form action="../controllers/profile-update.php" method="post">
            <button type="submit" name="logout" class=" w-full px-4 py-2 bg-red-500 text-black font-bold text-lg rounded hover:bg-red-600 transition">Logout</button>
        </form>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
