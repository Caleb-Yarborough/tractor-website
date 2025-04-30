<!-- Caleb Yarborough -->
<?php
// Map pages to their javascript
$scripts = [
    'index.php' => '<script src="js/home.js" defer></script>',
    'products/index.php' => '<script src="../js/cart_popup.js" defer></script>', 
    'cart/index.php' => '',
    'cart/order.php' => '',
    'about/index.php' => '',
    'signup/index.php' => '',
    'signup/account.php' => '',
    'login/index.php' => '',
    'loggedin/index.php' => '',
    'logout/index.php' => ''
];

// Set the script based on the current page
if (!isset($current_page)) {
    $current_page = basename($_SERVER['SCRIPT_FILENAME']); // Default if not set
}
// This line checks if a script is defined for the current page in the $scripts array and assigns it to $script. 
// If no JS script is defined, it assigns an empty string.
// condition ? value_if_true : value_if_false //
$script = isset($scripts[$current_page]) ? $scripts[$current_page] : '';

// default footer text for all pages
$footer_text = '©2024 Tractor Co. | Contact us: info@tractorco.com | Phone: (555) 123-4567';

// Append additional text for products/index.php
if ($current_page == 'products/index.php') {
    $footer_text .= ' | images from John Deere';
}
?>

        </section>
    </main>

    <?php
    // Output JS script if defined for the current page
    if (!empty($script)) {
        echo $script;
    }
    ?>

    <footer>
        <?php echo $footer_text; ?>
    </footer>

</body>
</html>