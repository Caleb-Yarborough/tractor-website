<!-- Caleb Yabrborough -->
<?php 
$current_page = 'index.php'; 
require_once 'includes/regular_conn.php';
session_start(); 
require('includes/header.php');?>

            <!-- Website description -->
            <h2> What We Do </h2>
            <p> Tractor Co. Store is an online tractor and tractor supply retailer for agricultural, commercial, and
                personal use. We offer wide variety of tractors from leading brands, ranging from compact models for
                small farms to heavy-duty models for larger operations. We also offer a selection of tractor attachments
                and accessories. We hope you find your farming needs! </p>

            <!-- explore products button -->
            <button class="ExploreButton" onclick="window.location.href='products/index.php';">Explore</button>

            <!-- print home page button -->
            <div class="PrintPage">
                <button id="PrintButton">Print</button>
            </div>

<?php include('includes/footer.php');?>