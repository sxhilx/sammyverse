<?php
require '../database/db.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];

if(!isset($_SESSION['id'])) {
    header('Location: /public/login.php');
    exit();
}   

$result = null;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['value'])) {
        $search = '%' . $_POST['value'] . '%';
        $stmt = $conn->prepare("SELECT * FROM users WHERE firstName LIKE ? OR lastName LIKE ?");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}


    
?>

<?php include '../includes/header.php';?>

    <h3 class="text-2xl font-bold  mb-6">Search Users</h3>

    <div>
        <form action="" method="post">
            <div class="flex flex-col md:flex-row justify-center items-center gap-2 mb-8">
                <input type="text" name="value" placeholder="Search User" 
                    class="w-full md:w-[70%] p-3  bg-[#0e285c] 
                            text-slate-100 text-lg rounded mb-2 md:mb-0 focus:outline-none 
                            focus:ring-2 focus:ring-teal-500 transition">
                <button type="submit" name="search" 
                        class="w-full md:w-[29%] py-3 bg-teal-500 text-white rounded 
                            hover:bg-teal-600 transition ease-in-out duration-200">
                    Search
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-8 mb-8">
        <?php
        if (isset($result) && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<div class='  bg-[#0b1936] rounded-lg shadow-lg p-6 space-y-4 flex flex-col'>
                        <div class='flex flex-col md:flex-row justify-between items-start md:items-center'>
                        <div class='post-content md:flex-grow'>
                            <p class='text-[#edeff5]'><strong>" . htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) . "</strong></p>
                            <p class='text-[#edeff5]'>" . htmlspecialchars($row['email']) . "</p>
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
        }
        ?>
    </div>


<?php include '../includes/footer.php';?>