<!-- Caleb Yabrborough -->
<?php
require_once '../includes/regular_conn.php';
session_start();

if (isset($_SESSION['full_name']) && isset($_SESSION['email'])) {
    $full_name = $_SESSION['full_name'];
    $_SESSION = array();
    session_destroy();
    setcookie('PHPSESSID', '', time() - 3600, '/');
    // direct to home page after logout
    header('Location: ../index.php');
    exit;
} else {
    // If already logged out, show error page
    require('../includes/header.php');
    echo '<h2>You have reached this page in error</h2>';
    echo '<h3>Please use the menu above</h3>';
    include('../includes/footer.php');
}
?>