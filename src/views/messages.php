<?php


require '../database/db.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 session_start();


$userIdSession = $_SESSION['id'];
$stmtMessages = $conn->prepare("
    SELECT 
        m.id AS message_id,
        m.message,
        m.timestamp,
        m.sender_id,
        u.username,
        u.firstName,
        u.lastName
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.receiver_id = ?
    AND m.timestamp = (
        SELECT MAX(timestamp)
        FROM messages
        WHERE sender_id = m.sender_id
        AND receiver_id = ?
    )
    ORDER BY m.timestamp DESC"
);

$stmtMessages->bind_param("ii", $userIdSession, $userIdSession);
$stmtMessages->execute();
$messagesResult = $stmtMessages->get_result();
?>

<?php include '../includes/header.php';?>

<div class=" flex justify-center items-center container mx-auto px-4 py-8">
    <div class=" w-[80%] grid grid-cols-1 gap-8">

        <!-- Your Messages Section (Right) -->
        <div class="bg-[#0e285c] p-6 rounded-lg shadow-lg space-y-4">
            <h3 class="text-2xl font-bold text-slate-100 mb-6">Your Messages</h3>
            <div class="flex flex-col space-y-4">
                <?php
                if (isset($messagesResult) && $messagesResult->num_rows > 0) {
                    while ($row = $messagesResult->fetch_assoc()) {
                        echo "<div class='flex flex-col md:flex-row md:justify-between md:items-center bg-[#0b1936] p-4 rounded-lg'>
                            <div>
                                <p class='text-[#edeff5]'><strong>" . htmlspecialchars($row['username']) . "</strong></p>
                                <p class='text-[#edeff5]'><strong>Sent a new Message</strong></p>
                            </div>
                            <div>
                                <a href='see-message.php?sender_id=" . $row['sender_id'] . "'>
                                    <button class= 'w-full md:w-auto py-2 px-4 bg-teal-500 text-white rounded hover:bg-teal-600 transition ease-in-out duration-200'>
                                        See Message
                                    </button>
                                </a>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p class='text-slate-300'>No messages found.</p>";
                }

                if (isset($stmtMessages)) {
                    $stmtMessages->close();
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';?>

