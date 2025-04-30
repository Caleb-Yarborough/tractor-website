<!-- Caleb Yarborough -->
<?php
// Map pages to their css files
$stylesheets = [ 
    'index.php' => 'styles/home.css',
    'products/index.php' => '../styles/products.css',
    'cart/index.php' => '../styles/products.css', 
    'cart/order.php' => '', // No additional css for this page
    'about/index.php' => '../styles/about.css',
    'signup/index.php' => '../styles/signup.css',
    'signup/account.php' => '', // No additional css for this page
    'login/index.php' => '../styles/signup.css', 
    'loggedin/index.php' => '', // No additional css needed
    'logout/index.php' => '' // No additional css needed
];

// Set the additional css file if it has one
if (!isset($current_page)) {
    $current_page = basename($_SERVER['SCRIPT_FILENAME']); // Default if not set
}
// This line checks if a css file is defined for the current page in the $stylesheets array and assigns it to $extra_stylesheet. 
// If no css file is defined, it assigns an empty string.
// condition ? value_if_true : value_if_false //
$extra_stylesheet = isset($stylesheets[$current_page]) ? $stylesheets[$current_page] : '';

// the prefix for links based on whether it's the home page or not
// condition ? value_if_true : value_if_false //
$root_dir = '/home/acy3465/public_html/Project2';
$link_prefix = ($current_page == 'index.php' && dirname($_SERVER['SCRIPT_FILENAME']) == $root_dir) ? '' : '../';

// Current page directory 
$current_dir = dirname($_SERVER['SCRIPT_FILENAME']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Caleb Yarborough -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- checks for index.php and applies different style path  -->
    <?php
    if ($current_page != 'index.php' || dirname($_SERVER['SCRIPT_FILENAME']) != $root_dir) {
        echo "<link rel=\"stylesheet\" href=\"../styles/main.css\">";
    } else {
        echo "<link rel=\"stylesheet\" href=\"styles/main.css\">";
    }
    ?>
    <!-- gives extra stylesheet to files that have extra styling -->
    <?php
    if (!empty($extra_stylesheet)) {
        echo "<link rel=\"stylesheet\" href=\"$extra_stylesheet\">";
    }
    // header (same for all pages)
    ?>
    <link rel="stylesheet" href="/styles/print.css" media="print">
    <title>Tractor Co. Store</title>
    <link rel="icon" href="<?php echo $link_prefix; ?>images/TractorCo.ico"> <!-- Tab Logo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .error {color: red; font-weight: bold;}
        span {display:block;}
    </style>
</head>
<body>
    <header>
        <h1>Tractor Co. Store</h1>
    </header>

    <nav>
        <!-- determines to use path for home page or other pages -->
        <?php
        if ($current_page != 'index.php' || dirname($_SERVER['SCRIPT_FILENAME']) != $root_dir) {
            echo "<a href=\"{$link_prefix}index.php\"><img src=\"../images/TractorCo.png\" alt=\"Tractor image\"></a>"; 
        } else {
            echo "<a href=\"index.php\"><img src=\"images/TractorCo.png\" alt=\"Tractor image\"></a>"; 
        }
        ?>
        <ul>
            <!-- condition ? value_if_true : value_if_false -->
            <!-- This line outputs either id="current" or nothing ('') into the HTML, depending on whether the current page is index.php in the Project2 dir -->
            <li><a href="<?php echo $link_prefix; ?>index.php" <?php echo ($current_page == 'index.php' && basename($current_dir) == 'Project2' ? 'id="current"' : ''); ?>>Home</a></li>
            <li><a href="<?php echo $link_prefix; ?>products/index.php" <?php echo ($current_page == 'products/index.php' ? 'id="current"' : ''); ?>>Products</a></li>
            <li><a href="<?php echo $link_prefix; ?>about/index.php" <?php echo ($current_page == 'about/index.php' ? 'id="current"' : ''); ?>>About-Us</a></li>
            <!-- displays links based on if logged in or not -->
            <?php if (isset($_SESSION['full_name']) && isset($_SESSION['email'])) { ?>
                <li><a href="<?php echo $link_prefix; ?>cart/index.php" <?php echo ($current_page == 'cart/index.php' ? 'id="current"' : ''); ?>>Cart</a></li>
                <li><a href="<?php echo $link_prefix; ?>logout/index.php" <?php echo ($current_page == 'logout/index.php' ? 'id="current"' : ''); ?>>Logout</a></li>
            <?php } else { ?>
                <li><a href="<?php echo $link_prefix; ?>signup/index.php" <?php echo ($current_page == 'signup/index.php' ? 'id="current"' : ''); ?>>Sign-Up</a></li>
                <li><a href="<?php echo $link_prefix; ?>login/index.php" <?php echo ($current_page == 'login/index.php' ? 'id="current"' : ''); ?>>Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <main>
        <section>