<?php

require '../database/db.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sender_id = isset($_GET['sender_id']) ? (int)$_GET['sender_id'] : 0;
$receiver_id = $_SESSION['id']; 


if ($sender_id === 0) {
    die("Invalid sender ID.");
} 


$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $sender_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user) {
    $_SESSION['username'] = htmlspecialchars($user['username']);
    $username = $_SESSION['username'];
} else {
    $username = "Unknown User"; 
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $receiver_id = (int)$_GET['sender_id']; // receiver's ID will be the sender's ID
    $sender_id = $_SESSION['id']; 
    $message = $_POST['message'];

    // Prepare and execute the insertion of the message
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    $stmt->execute();

    // Redirect to the same page with the sender_id to maintain context
    header('Location: see-message.php?sender_id=' . $receiver_id);
    exit();
}



$stmtMessages = $conn->prepare("
        SELECT 
        messages.*, 
        users.username as sender_username 
    FROM messages 
    JOIN users ON messages.sender_id = users.id 
    WHERE (sender_id = ? AND receiver_id = ?) 
    OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY messages.timestamp ASC"
);
$stmtMessages->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmtMessages->execute();
$messagesResult = $stmtMessages->get_result();
?>


<?php include '../includes/header.php';?>

<div class="flex justify-center items-center container mx-auto">
    <div class="w-full grid grid-cols-1 gap-8">
        <div class="bg-[#0b1936] p-6 rounded-lg shadow-lg space-y-4">
            <h3 class="text-2xl font-bold text-slate-100 mb-6"><strong>Chat with </strong> 
            <?php 
            if($sender_id == $_SESSION['id']) {
                echo "$username" . " (You)";
            } else {    
                echo $username;
            }
             ?></h3>
            <div class="grid grid-cols-1 gap-4 max-h-[65vh] md:max-h-[70vh] overflow-y-auto" id="messages-container">
            <?php
                if ($messagesResult->num_rows > 0) {
                    while ($row = $messagesResult->fetch_assoc()) {
                        // Display messages from the other user on the left
                        if ($row['sender_id'] == $sender_id) {
                            // Your messages
                            echo "<div class='flex justify-start mb-2'>
                                <div class='bg-[#10375C] p-3 rounded-lg shadow-lg space-y-4 max-w-xs'>
                                    <p class='text-slate-100'>" . htmlspecialchars($row['message']) . "</p>
                                    <p class='text-slate-300 text-sm'>" . htmlspecialchars($row['timestamp']) . "</p>
                                </div>
                            </div>";
                        } else {
                            // Other user's messages
                            echo "<div class='flex justify-end mr-4 mb-2 break-words '>
                                <div class='bg-[#10375C] p-3 rounded-lg shadow-lg space-y-4 max-w-xs'>
                                    <p class='text-slate-100'>" . htmlspecialchars($row['message']) . "</p>
                                    <p class='text-slate-300 text-sm'>" . htmlspecialchars($row['timestamp']) . "</p>
                                </div>
                            </div>";
                        }
                    }
                } else {
                    echo "<p class='text-slate-100'>No messages found between you and $username.</p>";
                }
            ?>

               
            </div>
            <div class="">
                <form action="" method="post">
                    <div class="flex flex-row justify-between gap-2">
                    <input type="text" name="message" placeholder="Send Message" class="w-full p-3 bg-[#0e285c] text-slate-100 text-lg rounded" required>
                    <button type="submit" name="reply" class='py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600 transition ease-in-out duration-200'>
                            Send
                    </button>
                    </div>
                    
                </form>
            </div>
        </div>
        
    </div>
</div>

<script>
// Automatically scroll to the bottom of the message container
document.addEventListener("DOMContentLoaded", function() {
    var messageContainer = document.getElementById("messages-container");
    messageContainer.scrollTop = messageContainer.scrollHeight;
});
</script>

<?php include '../includes/footer.php';?>