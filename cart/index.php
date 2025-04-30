<!-- Caleb Yarborough -->
<?php
require_once '../includes/secure_conn.php';
session_start();

$current_page = 'cart/index.php';
$page_title = 'Your Cart';

require('../includes/header.php');

// Initialize cart if not set
// $_SESSION['cart'] is an array to store cart items
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Uses POST if not null otherwise GET if not null otherwise defaults to showing cart (update cart, empty cart, or show cart)
$action = $_POST['action'] ?? $_GET['action'] ?? 'show_cart';

// cart actions
switch ($action) {
    // update cart action
    case 'update':
        // Update cart quantities based on form input
        $new_qty_list = filter_var($_POST['newqty'], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        // Loops through each item quantity and converts to int
        foreach ($new_qty_list as $item_id => $qty) {
            $quantity = (int) $qty;
            // if item in cart
            if (isset($_SESSION['cart'][$item_id])) {
                if ($quantity <= 0) {
                    // Remove item if quantity is 0
                    unset($_SESSION['cart'][$item_id]);
                } else {
                    // Update item quantity
                    $_SESSION['cart'][$item_id]['quantity'] = $quantity;
                }
            }
        }
        // direct to refresh cart display
        header("Location: index.php");
        exit;
    // empty cart action
    case 'empty_cart':
        // Clear all items from cart
        $_SESSION['cart'] = [];
        // direct to refresh cart display
        header("Location: index.php");
        exit;
    // display cart cation
    case 'show_cart':
    default:
        // Display cart contents from html below
}
?>

        <h2>Your Cart</h2>

        <!-- Caleb Yarborough -->
        <section class="CartDisplay"> 
            <!-- Checks if the cart is empty -->
            <?php if (empty($_SESSION['cart'])) { ?>
                <h3>Your cart is empty.</h3>
                <p>Please select products to add to your cart.</p>
            <?php } else { ?>
                <!-- Form to update cart quantities -->
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="update">
                    <h4>To remove an item, set its quantity to 0 and click update.</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through each item in the cart and get individual item and total item costs
                            $total = 0;
                            foreach ($_SESSION['cart'] as $item_id => $item) {
                                // Compute subtotal for each item
                                $subtotal = $item['price'] * $item['quantity'];
                                // Calculate total cost of items
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>$<?php echo number_format($item['price']); ?></td>
                                    <td>
                                        <!-- Input to update item quantity -->
                                        <input type="number" name="newqty[<?php echo htmlspecialchars($item_id); ?>]" value="<?php echo $item['quantity']; ?>" min="0">
                                    </td>
                                    <!-- item cost -->
                                    <td>$<?php echo number_format($subtotal); ?></td>
                                </tr>
                            <?php } ?>
                                <!-- Display total cost -->
                                <tr>
                                    <td colspan="3"><strong>Total:</strong></td>
                                    <td><strong>$<?php echo number_format($total); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Submit button to update cart -->
                        <input type="submit" value="Update Cart" class="addCart">
                    </form>

                        <!-- Links to continue shopping, empty cart, or checkout -->
                        <p><a href="order.php" class="addCart">Proceed to Checkout</a></p>
                        <p><a href="../products/index.php" class="addCart">Add More Items</a></p>
                        <p><a href="index.php?action=empty_cart" class="addCart">Empty Cart</a></p>

                    
                <?php } ?>
        </section>

<!-- Caleb Yarborough -->
<?php include('../includes/footer.php'); ?>