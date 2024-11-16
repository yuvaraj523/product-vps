<?php
session_start();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'items' exists in POST data and is an array
    if (isset($_POST['items']) && is_array($_POST['items'])) {
        foreach ($_POST['items'] as $item) {
            // Use isset() to avoid undefined key warnings
            $name = isset($item['name']) ? $item['name'] : 'Unnamed Product';
            $price = isset($item['price']) ? (float)$item['price'] : 0; // Ensure price is a float
            $image = isset($item['image']) && !empty($item['image']) ? 'uploads/' . $item['image'] : 'uploads/default.jpg'; 
            $_SESSION['cart'][] = [
                'name' => $name,
                'price' => $price,
                'image' => $image, // Store the correct image path
                'quantity' => 1 // Initialize quantity
            ];
        }
    }
}

// Update quantity via AJAX
if (isset($_POST['update_quantity'])) {
    $index = (int)$_POST['index'];
    $quantity = (int)$_POST['quantity'];

    // Ensure the index is valid and quantity is at least 1
    if (isset($_SESSION['cart'][$index]) && $quantity >= 1) {
        $_SESSION['cart'][$index]['quantity'] = $quantity; // Update the quantity in the session
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}

if (isset($_GET['remove_all'])) {
    unset($_SESSION['cart']); // Clear the cart session
    echo json_encode(['status' => 'success']); // Return success response
    exit;
}

// Remove an item from the cart via AJAX
if (isset($_POST['remove_index'])) {
    $index = (int)$_POST['remove_index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove item from cart
        // Re-index the cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
   body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        .cart-item {
            background: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between; /* Adjust for spacing */
        }
        .cart-item img {
            width: 100px;
            height: auto;
            margin-right: 15px;
            border-radius: 5px;
        }
        .cart-item h3, .cart-item p {
            margin: 0;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        .quantity-controls button {
            margin: 0 5px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
        .remove-icon {
            color: red; /* Color for remove icon */
            cursor: pointer; /* Cursor changes to pointer on hover */
        }
        .remove-all {
            color: red; /* Style for remove all button */
            display: inline-block;
            margin-top: 20px;
            cursor: pointer; /* Cursor changes to pointer on hover */
        }
        .cart-totals {
            margin-top: 20px;
        }
        .checkout-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    </style>
</head>
<body>
<?php if (!empty($_SESSION['cart'])): ?>
    <h2>Your Cart</h2>
    <?php foreach ($_SESSION['cart'] as $index => $cartItem): ?>
        <div class='cart-item' id="item-<?php echo $index; ?>">
            <img src="<?php echo htmlspecialchars($cartItem['image']); ?>" alt="Product Image">
            
            <div>
                <strong><?php echo htmlspecialchars($cartItem['name']); ?></strong>
                <p>Price: ₹ <?php echo number_format($cartItem['price'], 2); ?> x 
                <span class="quantity" data-index="<?php echo $index; ?>"><?php echo $cartItem['quantity'] ?? 1; ?></span>
                <input type="hidden" class="quantity-input" data-index="<?php echo $index; ?>" data-price="<?php echo $cartItem['price']; ?>" value="<?php echo $cartItem['quantity'] ?? 1; ?>">
                </p>
            </div>

            <div class="item-total">
                <strong>Total: ₹ <span class="item-total-amount"><?php echo number_format($cartItem['price'] * $cartItem['quantity'], 2); ?></span></strong>
            </div>
            
            <div class="quantity-controls">
                <button class="decrement" data-index="<?php echo $index; ?>">-</button>
                <button class="increment" data-index="<?php echo $index; ?>">+</button>
            </div>
            
            <a href="#" class="remove-icon" data-index="<?php echo $index; ?>" title="Remove Item">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>
    <?php endforeach; ?>

    <a href="" class="remove-all" id="remove-all" title="Remove All Items">
        <i class="fas fa-trash-alt"></i> Remove All Items
    </a>

    <div class="cart-totals">
        <div><strong>Subtotal:</strong> ₹ <span id="cartTotal">0.00</span></div>
        <div><strong>SGST (9%):</strong> ₹ <span id="sgstTotal">0.00</span></div>
        <div><strong>GST (9%):</strong> ₹ <span id="gstTotal">0.00</span></div>
        <div><strong>Total Tax:</strong> ₹ <span id="totalTax">0.00</span></div>
        <div><strong>Grand Total:</strong> ₹ <span id="grandTotal">0.00</span></div>
    </div>

    <form action="checkout.php" method="POST" id="checkoutForm">
        <button class="checkout-button">Proceed to Checkout</button>
    </form>

<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

<a href="category.php?category=<?php echo urlencode($_POST['category']); ?>">Continue Shopping</a>

<script>
    const SGST_RATE = 0.09;
    const GST_RATE = 0.09;

    // Update cart totals (subtotal, tax, grand total)
    function updateCartTotals() {
        let total = 0;

        document.querySelectorAll('.cart-item').forEach(function(cartItem) {
            const quantityInput = cartItem.querySelector('.quantity-input');
            const price = parseFloat(quantityInput.getAttribute('data-price'));
            const quantity = parseInt(quantityInput.value);
            const itemTotal = price * quantity;

            // Update the total for this item
            cartItem.querySelector('.item-total-amount').textContent = itemTotal.toFixed(2);
            total += itemTotal;

            // Update the displayed quantity next to price
            cartItem.querySelector('.quantity').textContent = quantity;

            // Update quantity in session via AJAX
            const index = quantityInput.getAttribute('data-index');
            $.post('', { update_quantity: true, index: index, quantity: quantity });
        });

        // Calculate tax and grand total
        const sgst = total * SGST_RATE;
        const gst = total * GST_RATE;
        const totalTax = sgst + gst;
        const grandTotal = total + totalTax;

        // Update totals in the DOM
        document.getElementById('cartTotal').textContent = total.toFixed(2);
        document.getElementById('sgstTotal').textContent = sgst.toFixed(2);
        document.getElementById('gstTotal').textContent = gst.toFixed(2);
        document.getElementById('totalTax').textContent = totalTax.toFixed(2);
        document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
    }

    // Increment quantity
    document.querySelectorAll('.increment').forEach(function(button) {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const quantityInput = document.querySelector('#item-' + index + ' .quantity-input');

            quantityInput.value = parseInt(quantityInput.value) + 1; // Increment quantity
            updateCartTotals(); // Update totals
        });
    });

    // Decrement quantity
    document.querySelectorAll('.decrement').forEach(function(button) {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const quantityInput = document.querySelector('#item-' + index + ' .quantity-input');

            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1; // Decrement quantity
                updateCartTotals(); // Update totals
            }
        });
    });

   
    document.querySelectorAll('.remove-icon').forEach(function(button) {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            $.post('', { remove_index: index }, function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    document.getElementById('item-' + index).remove(); // Remove item from DOM
                    updateCartTotals(); // Update totals
                }
            });
        });
    });

    // Remove all items
    document.getElementById('remove-all').addEventListener('click', function() {
        $.get('', { remove_all: true }, function(response) {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                location.reload(); // Reload the page to clear the cart
            }
        });
    });

    // Initialize cart totals on page load
    updateCartTotals();
</script>
</body>
</html>
