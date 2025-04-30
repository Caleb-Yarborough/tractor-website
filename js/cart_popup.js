// Caleb Yarborough
// Displays a JavaScript alert with the given message
function showPopup(message) {
    window.alert(message);
}

// Validates add-to-cart action and shows alert
// Returns true to allow form submission, false to block it
function validateAddToCart(product_name, logged_in) {
    if (logged_in) {
        showPopup(product_name + " added to cart");
        return true; // Allow form submission
    } else {
        showPopup("Please log in to shop");
        return false; // Block form submission
    }
}