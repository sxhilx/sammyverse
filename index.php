<?php

ini_set('display_errors', 1);   
ini_set('display_startup_errors', 1);   
error_reporting(E_ALL);

// Define the base path to the 'src' directory
define('BASE_PATH', '/opt/lampp/htdocs/sammyverse');

// Require the database file using the defined base path
require BASE_PATH . '/src/database/db.php';


// Start the session
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: public/login.php'); // Adjust this path if necessary
    exit();
}

// Include the homepage using the defined base path
include BASE_PATH . '/src/views/homepage.php';
?>
