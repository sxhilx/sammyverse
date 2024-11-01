<?php

require '../database/db.php';

session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /public/login.php');
    exit();
}

$profile_id = $_GET['profile_id'];
$user_id = $_SESSION['id'];

if($profile_id === $user_id) {
    header('Location: ../views/profile.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$name = $user['firstName'] . " " . $user['lastName'];
$username = $user['username'];

if (!$user) {
    echo "User not found.";
    exit();
}   

$stmtPosts = $conn->prepare("SELECT * FROM posts WHERE user_id = ?");
$stmtPosts->bind_param("i", $profile_id);
$stmtPosts->execute();
$posts = $stmtPosts->get_result();




?>

<?php include '../includes/header.php';?>

<div class="">

    <div>
    <h1>Profile</h1>
    <p class="text-2xl font-bold mb-2">Username: <?php echo $username ?></p>
    
    <p class="text-xl font-bold  mb-6"><?php echo $name ?></p>

    <hr>

    <h3 class="text-2xl font-bold mt-6  mb-6">Posts</h3>
    </div>

    <div>
        <?php
            if(isset($posts) && $posts->num_rows > 0) {
                while ($row = $posts->fetch_assoc()) {    
                    echo "<div class='bg-[#0b1936] rounded-lg shadow-lg p-6 space-y-4 '>
                            <div class='post-content'>
                                <p class='text-[#edeff5] text-lg'>" . htmlspecialchars($row['content']) . "</p>
                                <small class='text-slate-300 block mt-2'>" . htmlspecialchars($row['created_at']) . "</small>
                            </div>
                        </div>";
                            
                }
            } else {
                echo "<p>No posts yet.</p>";
            }
            if (isset($stmtPosts)) {
                $stmtPosts->close();
            }
        ?>
    </div>
</div>

<?php include '../includes/footer.php';?>