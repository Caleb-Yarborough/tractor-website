<!-- Caleb Yarborough -->
<?php

require_once '../includes/regular_conn.php';
session_start();

// Set current page for header/footer inclusion
$current_page = 'products/index.php';

require('../includes/header.php');
require_once('../includes/pdo_connect.php');

// Initialize cart if not set
// Ensures $_SESSION['cart'] is an array to store cart 
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get product data from database
try {
    $stmt = $pdo->query("SELECT id, name, price, category FROM TractorProducts");
    $products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // associative array $items indexed by id
        $items[$row['id']] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'category' => $row['category']
        ];
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    // stops JS script and displays message
    die("Sorry, we couldn't load products. Please try again later.");
}

// image mapping from name to file path
$image_map = [
    '9R_Tractor' => 'TractorHeavy1.avif',
    '6R_Tractor' => 'TractorHeavy2.avif',
    '8Series_Tractor' => 'TractorHeavy3.avif',
    'X_Combine' => 'TractorHeavy4.avif',
    '1_Series' => 'TractorCompact2.avif',
    '2_Series' => 'TractorCompact4.avif',
    '3_Series' => 'TractorCompact1.avif',
    '4_Series' => 'TractorCompact3.avif',
    'Flex_Wing_Rotary_Cutter' => 'AccessoriesFlexWingRotaryCutter.avif',
    'Loader_Attachment' => 'AccessoriesLoader.avif',
    'Planter_Attachment' => 'AccessoriesPlanter.avif',
    'Rotary_Cutter_Attachment' => 'AccessoriesRotaryCutter.avif'
];

// Check for alert message from action
if (isset($_SESSION['cart_alert'])) {
    // listens for add to cart button click then shows alert
    //  waits until the page is fully loaded, then calls JS function showPopup() with a message ($_SESSION['cart_alert'])
    echo "<script>window.addEventListener('load', function() { showPopup('" . htmlspecialchars($_SESSION['cart_alert']) . "'); });</script>";
    // clear the message from the session after it's been used
    unset($_SESSION['cart_alert']);
}

// Uses POST action or defaults to showing products
$action = $_POST['action'] ?? 'show_products';

// cart actions
switch ($action) {
    // add to cart action
    case 'add':
        // Check if user is logged in
        if (!isset($_SESSION['full_name']) || !isset($_SESSION['email'])) {
            // Set message for JS alert (if not logged in)
            $_SESSION['cart_alert'] = 'Please log in to add to cart';
            header("Location: index.php?category=" . ($_GET['category'] ?? 'heavy-duty'));
            exit;
        }
        // Sanitize input 
        $item_id = filter_var($_POST['item_id'], FILTER_SANITIZE_STRING);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);
        // updates cart and sets alert if logged in
        // Add item to cart if it exists in $items
        if (isset($items[$item_id])) {
            if (isset($_SESSION['cart'][$item_id])) {
                // Increment quantity if item already in cart
                $_SESSION['cart'][$item_id]['quantity'] += $qty;
            } else {
                // Add new item to cart with name, price, and quantity
                $_SESSION['cart'][$item_id] = [
                    'name' => $items[$item_id]['name'],
                    'price' => $items[$item_id]['price'],
                    'quantity' => $qty,
                ];
            }
        }
        // direct to same category to keep category selection same
        header("Location: index.php?category=" . ($_GET['category'] ?? 'heavy-duty'));
        exit;
    // show product categories action
    case 'show_products':
    default:
        // Determine active category
        // Uses GET parameter or defaults to heavy-duty
        $category = $_GET['category'] ?? 'heavy-duty';
        // Set display for category sections
        $display_styles = [
            'heavy-duty' => 'display: block;',
            'compact' => 'display: none;',
            'attachments' => 'display: none;',
        ];
        // update display based on selected category
        if ($category === 'compact') {
            $display_styles['heavy-duty'] = 'display: none;';
            $display_styles['compact'] = 'display: block;';
        } elseif ($category === 'attachments') {
            $display_styles['heavy-duty'] = 'display: none;';
            $display_styles['attachments'] = 'display: block;';
        }
}
?>

<!-- Caleb Yarborough -->
<h2>Product Categories</h2>

<!-- Button selection to toggle between product categories -->
<div class="radio-inputs">
    <label class="radio">
        <!-- changes display to heavy-duty -->
        <input id="heavy-duty-radio" type="radio" name="radio" value="heavy-duty" <?php echo $category === 'heavy-duty' ? 'checked' : ''; ?> onchange="window.location.href='index.php?category=heavy-duty';">
        <span class="name">Heavy-Duty</span>
    </label>
    <label class="radio">
        <!-- changes display to compact -->
        <input id="compact-radio" type="radio" name="radio" value="compact" <?php echo $category === 'compact' ? 'checked' : ''; ?> onchange="window.location.href='index.php?category=compact';">
        <span class="name">Compact</span>
    </label>     
    <label class="radio">
        <!-- changes display to attachments -->
        <input id="attachments-radio" type="radio" name="radio" value="attachments" <?php echo $category === 'attachments' ? 'checked' : ''; ?> onchange="window.location.href='index.php?category=attachments';">
        <span class="name">Attachments & Accessories</span>
    </label>
</div>

<!-- All Products that are displayed -->
<div class="ProductDisplay">
    <!-- Heavy-duty products -->
    <div class="heavy-duty-display" style="<?php echo $display_styles['heavy-duty']; ?>">
        <!-- Loops through products and filters by category -->
        <?php foreach ($items as $id => $item) {
            // continue skips item if it's not in category
            if ($item['category'] !== 'heavy-duty') continue; ?>
            <div class="item">
                <!-- Displays product image and name -->
                <figure>
                    <img src="../images/<?php echo htmlspecialchars($image_map[$id]); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <figcaption><?php echo htmlspecialchars($item['name']); ?></figcaption>
                </figure>
                <!-- displays product price -->
                <div class="price">$<?php echo number_format($item['price']); ?></div>
                <!-- Form to add item to cart -->
                <!-- calls  JS validateAddToCart method with item name parameter and true if logged in; false if logged out-->
                <form method="post" onsubmit="return validateAddToCart('<?php echo htmlspecialchars($item['name']); ?>', <?php echo isset($_SESSION['full_name']) && isset($_SESSION['email']) ? 'true' : 'false'; ?>)">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="qty" value="1">
                    <input type="submit" value="Add to Cart" class="addCart">
                </form>
            </div>
        <?php } ?>
    </div>

    <!-- Compact products -->
    <div class="compact-display" style="<?php echo $display_styles['compact']; ?>">
        <!-- Loops through products and filters by category -->
        <?php foreach ($items as $id => $item) {
            // continue skips item if it's not in category
            if ($item['category'] !== 'compact') continue; ?>
            <div class="item">
                <!-- Displays product image and name -->
                <figure>
                    <img src="../images/<?php echo htmlspecialchars($image_map[$id]); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <figcaption><?php echo htmlspecialchars($item['name']); ?></figcaption>
                </figure>
                <!-- displays product price -->
                <div class="price">$<?php echo number_format($item['price']); ?></div>
                <!-- Form to add item to cart -->
                <!-- calls  JS validateAddToCart method with item name parameter and true if logged in; false if logged out-->
                <form method="post" onsubmit="return validateAddToCart('<?php echo htmlspecialchars($item['name']); ?>', <?php echo isset($_SESSION['full_name']) && isset($_SESSION['email']) ? 'true' : 'false'; ?>)">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="qty" value="1">
                    <input type="submit" value="Add to Cart" class="addCart">
                </form>
            </div>
        <?php } ?>
    </div>

    <!-- Attachment products -->
    <div class="attachments-display" style="<?php echo $display_styles['attachments']; ?>">
        <!-- Loops through products and filters by category -->
        <?php foreach ($items as $id => $item) {
            // continue skips item if it's not in category
            if ($item['category'] !== 'attachments') continue; ?>
            <div class="item">
                <!-- Displays product image and name -->
                <figure>
                    <img src="../images/<?php echo htmlspecialchars($image_map[$id]); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <figcaption><?php echo htmlspecialchars($item['name']); ?></figcaption>
                </figure>
                <!-- displays product price -->
                <div class="price">$<?php echo number_format($item['price']); ?></div>
                <!-- Form to add item to cart -->
                <!-- calls  JS validateAddToCart method with item name parameter and true if logged in; false if logged out-->
                <form method="post" onsubmit="return validateAddToCart('<?php echo htmlspecialchars($item['name']); ?>', <?php echo isset($_SESSION['full_name']) && isset($_SESSION['email']) ? 'true' : 'false'; ?>)">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="qty" value="1">
                    <input type="submit" value="Add to Cart" class="addCart">
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>