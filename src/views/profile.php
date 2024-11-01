<?php

require '../database/db.php';

session_start();

if (!isset($conn)) {
    die("Database connection not established.");
}

if(!isset($_SESSION['id'])) {   
    header('Location: /public/login.php');
    exit();
}

$userId = $_SESSION['id'];

$postsResult = null;
$messagesResult = null;


// Fetch user data
$stmt = $conn->prepare("SELECT firstName, lastName, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch user posts
$stmtPosts = $conn->prepare("SELECT id, content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmtPosts->bind_param("i", $userId);
$stmtPosts->execute();
$postsResult = $stmtPosts->get_result();


?>

<?php include '../includes/header.php'; ?>

            <h3 class='text-2xl font-bold  mb-2'><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>'s Profile</h3>
            <hr class="mb-2">
            <div class="grid grid-cols-1 gap-8">
                <h2 class='text-xl font-bold mt-2'>Your Posts...</h2>
                <?php
                if (isset($postsResult) && $postsResult->num_rows > 0) {
                    echo "<div class='bg-[#0b1936] rounded-lg shadow-lg p-6 space-y-4'>
                            <p class='text-slate-100 text-center text-2xl font-bold'>Create New post</p> 
                            <form 
                                method='POST' 
                                action='../controllers/posts.php'
                                class='mt-4'
                            >
                                <textarea name='content' class='w-full p-2 border border-gray-300 bg-[#0b1936] text-slate-100 text-lg rounded mb-2 resize-none' rows='2' placeholder='Write your post here...' required></textarea>
                                <button 
                                type='submit' 
                                class='px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 transition'
                                >
                                Post
                                </button>
                            </form>                          
                        </div>";
                    while ($row = $postsResult->fetch_assoc()) {    
                    echo "<div class='bg-[#0b1936] rounded-lg shadow-lg p-6 space-y-4 '>
                            <div class='post-content'>
                                <p class='text-[#edeff5] text-lg'>" . htmlspecialchars($row['content']) . "</p>
                                <small class='text-slate-300 block mt-2'>" . htmlspecialchars($row['created_at']) . "</small>
                            </div>
                            <div class='flex space-x-4'>
                                <button 
                                onclick='document.getElementById(\"edit-form-" . (int)$row['id'] . "\").style.display = \"block\"; return false;'
                                class='px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition'
                                >
                                Edit
                                </button>
                                <form action='../controllers/posts.php?id=" . (int)$row['id'] . "' method='POST' class='inline'>
                                <button 
                                    type='submit' 
                                    name='delete'
                                    class='px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition'
                                >
                                    Delete
                                </button>
                                </form>
                            </div>
                            <form 
                                id='edit-form-" . (int)$row['id'] . "' 
                                style='display: none;' 
                                method='POST' 
                                action='../controllers/posts.php?id=" . (int)$row['id'] . "'
                                class='mt-4'
                            >
                                <textarea 
                                name='content' 
                                class='w-full p-2 border border-gray-300 bg-[#0b1936] text-slate-100 text-lg rounded mb-2 resize-none'
                                rows='2'
                                >" . htmlspecialchars($row['content']) . "</textarea>
                                <button 
                                type='submit' 
                                name='update'
                                class='px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 transition'
                                >
                                Update
                                </button>
                            </form>
                            </div>";
                    }
                } else {
                    echo "<div class='col-span-2 bg-[#0b1936] rounded-lg shadow-lg p-6'>
                            <p class='text-slate-100 text-center text-2xl font-bold'>Create your first post</p> 
                            <form 
                                method='POST' 
                                action='../controllers/posts.php'
                                class='mt-4'
                            >
                                <textarea name='content' class='w-full p-2 border border-gray-300 bg-[#0b1936] text-slate-100 text-lg rounded mb-2 resize-none' rows='3' placeholder='Write your post here...'></textarea>
                                <button 
                                type='submit' 
                                class='px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition'
                                >
                                Post
                                </button>
                            </form>                          
                        </div>";
                }
                if (isset($stmtPosts)) {
                    $stmtPosts->close();
                }
                ?> 
            </div>
        
    
    <?php include '../includes/footer.php'; ?>