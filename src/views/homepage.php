<?php

require BASE_PATH . '/src/database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    die("Database connection not established.");
}


$userId = $_SESSION['id'];



$allPosts = null;

// fetch all the posts 

$stmtAllPosts = $conn->prepare("SELECT users.id, users.username, posts.content, posts.created_at 
                                 FROM posts 
                                 JOIN users ON posts.user_id = users.id 
                                 ORDER BY posts.created_at DESC");
$stmtAllPosts->execute();
$allPosts = $stmtAllPosts->get_result();


?>

<?php include BASE_PATH . '/src/includes/header.php'; ?>



<h3 class="text-2xl font-bold mb-6">See what everyone's upto</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <?php
    if (isset($allPosts) && $allPosts->num_rows > 0) {
        while ($row = $allPosts->fetch_assoc()) {
            echo "<div class='bg-[#000000] border border-gray-500 rounded-lg shadow-lg p-4 space-y-4 flex flex-col'>
                    <div class='flex flex-col md:flex-row justify-between items-start md:items-center'>
                        <div class='post-content md:flex-grow'>
                            <p class='text-[#edeff5]'><strong>" . htmlspecialchars($row['username']) . "</strong></p>
                            <p class='text-[#edeff5]'>" . htmlspecialchars($row['content']) . "</p>
                            <small class='text-slate-300 block mt-2'>" . htmlspecialchars($row['created_at']) . "</small>
                        </div>
                        <div class='flex space-x-4 mt-4 md:mt-0 md:ml-4'>
                             <a href='see-message.php?sender_id=" . htmlspecialchars($row['id']) . "'><button class='w-full md:w-auto py-3 px-4 bg-teal-500 text-white rounded hover:bg-teal-600 transition ease-in-out duration-200'>
                                Message
                            </button></a>
                            <a href='see-profile.php?profile_id=" . htmlspecialchars($row['id']) . "'>
                            <button class='w-full md:w-auto py-3 px-4 bg-red-500 text-white rounded hover:bg-red-600 transition ease-in-out duration-200'>
                                View Profile
                            </button>
                            </a>
                        </div>
                    </div>
                </div>";
        }
    } else {
        echo "<div class='col-span-2 bg-white rounded-lg shadow-lg p-6'>
                <p class='text-gray-800 text-center'>No posts yet</p>
            </div>";
    }
    if (isset($stmtAllPosts)) {
        $stmtAllPosts->close();
    }
    ?> 
</div>


<?php include BASE_PATH . '/src/includes/footer.php'; ?>
