<!-- Caleb Yabrborough -->
<?php 
$current_page = 'cart/order.php'; 
require_once '../includes/secure_conn.php'; 
session_start();
require('../includes/header.php');
$_SESSION['cart'] = [];?>

                
                <h2> Order Confirmed! </h2>
                <p> Thank you for purchasing some of our products!</p>

<?php include('../includes/footer.php');?>