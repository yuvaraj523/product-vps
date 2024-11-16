<?php
session_start();

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Fetch cart details from the session
$cart = $_SESSION['cart'];

// Constants for SGST and GST rates
$SGST_RATE = 0.09; // 9% SGST
$GST_RATE = 0.09;  // 9% GST

// Calculate totals
$total = 0;
foreach ($cart as $item) {
    // Use null coalescing operator to avoid "undefined array key" warning
    $quantity = $item['quantity'] ?? 0; // Default to 0 if not set
    $price = $item['price'] ?? 0; // Default to 0 if price is not set

    // Accumulate the total
    $total += $price * $quantity;
}
$sgst = $total * $SGST_RATE;
$gst = $total * $GST_RATE;
$grandTotal = $total + $sgst + $gst;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
     body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .checkout-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            align-items: center;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .totals {
            margin-top: 20px;
            text-align: right;
        }

        .totals div {
            margin-bottom: 8px;
        }


.checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    font-size: 16px;
}


.checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 10px;
    cursor: pointer;
    border-radius: 4px;
    accent-color: #28a745; 
}


.checkbox label {
    cursor: pointer;
    color: #333;
    font-weight: 500;
    transition: color 0.3s ease;
}


.checkbox label:hover {
    color: #28a745;
}

.billing-shipping-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .billing-section, .shipping-section {
            flex: 1;
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 45px;
            border-radius: 5px;
        }

        .billing-section input, .billing-section textarea, 
        .shipping-section input, .shipping-section textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .billing-section label, .shipping-section label {
            font-weight: bold;
        }

        .checkbox {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <!-- Display cart items -->
    <div class="cart-items">
    <?php foreach ($cart as $item): ?>
        <div class="cart-item">
            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
            <div class="cart-item-details">
                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                <p>Price: ₹<?php echo number_format($item['price'] ?? 0, 2); ?> x <?php echo $item['quantity'] ?? 0; ?></p>
            </div>
            <div class="item-total">
                <strong>Total: ₹<?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0); ?></strong>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    <!-- Totals Section -->
    <div class="totals">
        <div><strong>Subtotal:</strong> ₹<?php echo number_format($total, 2); ?></div>
        <div><strong>SGST (9%):</strong> ₹<?php echo number_format($sgst, 2); ?></div>
        <div><strong>GST (9%):</strong> ₹<?php echo number_format($gst, 2); ?></div>
        <div><strong>Total Tax:</strong> ₹<?php echo number_format($sgst + $gst, 2); ?></div>
        <div><strong>Grand Total:</strong> ₹<?php echo number_format($grandTotal, 2); ?></div>
    </div>

    <!-- Billing and Shipping Details -->
    <div class="billing-shipping-container">
        <div class="billing-section">
            <h3>Billing Details</h3>
            <form action="checkout_submit_form.php" method="POST">
                <label for="billing-name">Full Name</label>
                <input type="text" id="billing-name" name="billing_full_name" placeholder="Enter your full name" required>

                <label for="billing-email">Email</label>
                <input type="email" id="billing-email" name="billing_email" placeholder="Enter your email address" required>

                <label for="billing-phone">Phone</label>
                <input type="text" id="billing-phone" name="billing_phone" placeholder="Enter your phone number" required>

                <label for="billing-address">Address</label>
                <textarea id="billing-address" name="billing_address" rows="3" placeholder="Enter your billing address" required></textarea>

                <label for="billing-city">City</label>
                <input type="text" id="billing-city" name="billing_city" placeholder="Enter your city" required>

                <label for="billing-zip">ZIP Code</label>
                <input type="text" id="billing-zip" name="billing_zip_code" placeholder="Enter your ZIP code" required>

                <label for="billing-country">Country</label>
                <input type="text" id="billing-country" name="billing_country" placeholder="Enter your country" required>

                <!-- Same as Billing Checkbox -->
                <div class="checkbox">
                    <input type="checkbox" id="sameAsBilling" name="shipping_billing" onclick="copyBillingDetails()">
                    <label for="sameAsBilling">Shipping details same as billing</label>
                </div>
        </div>

        <div class="shipping-section">
            <h3>Shipping Details</h3>
            <label for="shipping-name">Full Name</label>
            <input type="text" id="shipping-name" name="shipping_full_name" placeholder="Enter your full name" required>

            <label for="shipping-email">Email</label>
            <input type="email" id="shipping-email" name="shipping_email" placeholder="Enter your email address" required>

            <label for="shipping-phone">Phone</label>
            <input type="text" id="shipping-phone" name="shipping_phone" placeholder="Enter your phone number" required>

            <label for="shipping-address">Address</label>
            <textarea id="shipping-address" name="shipping_address" rows="3" placeholder="Enter your shipping address" required></textarea>

            <label for="shipping-city">City</label>
            <input type="text" id="shipping-city" name="shipping_city" placeholder="Enter your city" required>

            <label for="shipping-zip">ZIP Code</label>
            <input type="text" id="shipping-zip" name="shipping_zip_code" placeholder="Enter your ZIP code" required>

            <label for="shipping-country">Country</label>
            <input type="text" id="shipping-country" name="shipping_country" placeholder="Enter your country" required>
        </div>
    </div>

    <!-- Hidden Fields for Cart Data -->
    <input type="hidden" name="cart_items" value='<?php echo htmlspecialchars(json_encode($cart)); ?>'>
    <input type="hidden" name="subtotal" value="<?php echo htmlspecialchars($total); ?>">
    <input type="hidden" name="sgst" value="<?php echo htmlspecialchars($sgst); ?>">
    <input type="hidden" name="gst" value="<?php echo htmlspecialchars($gst); ?>">
    <input type="hidden" name="grand_total" value="<?php echo htmlspecialchars($grandTotal); ?>">
    <input type="hidden" name="total_tax" value="<?php echo htmlspecialchars($sgst + $gst); ?>">

    <!-- Checkout Button -->
    <button class="checkout-button" type="submit">Confirm Order</button>
    </form>
</div>

<script>
function copyBillingDetails() {
    if (document.getElementById("sameAsBilling").checked) {
        document.getElementById("shipping-name").value = document.getElementById("billing-name").value;
        document.getElementById("shipping-email").value = document.getElementById("billing-email").value;
        document.getElementById("shipping-phone").value = document.getElementById("billing-phone").value;
        document.getElementById("shipping-address").value = document.getElementById("billing-address").value;
        document.getElementById("shipping-city").value = document.getElementById("billing-city").value;
        document.getElementById("shipping-zip").value = document.getElementById("billing-zip").value;
        document.getElementById("shipping-country").value = document.getElementById("billing-country").value;
    } else {
        document.getElementById("shipping-name").value = "";
        document.getElementById("shipping-email").value = "";
        document.getElementById("shipping-phone").value = "";
        document.getElementById("shipping-address").value = "";
        document.getElementById("shipping-city").value = "";
        document.getElementById("shipping-zip").value = "";
        document.getElementById("shipping-country").value = "";
    }
}
</script>

</body>
</html>
