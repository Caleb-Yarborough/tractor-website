<!-- Caleb Yabrborough -->
<?php
$current_page = 'loggedin/index.php';
require_once '../includes/regular_conn.php';
session_start();

// gets user and displays message
if (isset($_SESSION['full_name']) && isset($_SESSION['email'])) {
    $full_name = $_SESSION['full_name'];
    $message = "Welcome back, $full_name";
    $message2 = "You are now logged in";
} else {
    $message = 'You have reached this page in error';
    $message2 = 'Please use the menu above';
}

require('../includes/header.php');

echo '<h2>'.$message.'</h2>';
echo '<h3>'.$message2.'</h3>';

include('../includes/footer.php');
?>