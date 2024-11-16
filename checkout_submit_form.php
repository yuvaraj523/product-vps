<?php
include('db.php');

// Check if form was submitted with necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form inputs for security
    $billing_full_name = $con->real_escape_string($_POST['billing_full_name']);
    $billing_email = $con->real_escape_string($_POST['billing_email']);
    $billing_phone = $con->real_escape_string($_POST['billing_phone']);
    $billing_address = $con->real_escape_string($_POST['billing_address']);
    $billing_city = $con->real_escape_string($_POST['billing_city']);
    $billing_zip_code = $con->real_escape_string($_POST['billing_zip_code']);
    $billing_country = $con->real_escape_string($_POST['billing_country']);

    // Check if shipping billing is checked
    $shipping_billing = isset($_POST['shipping_billing']) ? 1 : 0;

    // Set shipping details
    if ($shipping_billing) {
        // If checked, copy billing details to shipping details
        $shipping_full_name = $billing_full_name;
        $shipping_email = $billing_email;
        $shipping_phone = $billing_phone;
        $shipping_address = $billing_address;
        $shipping_city = $billing_city;
        $shipping_zip_code = $billing_zip_code;
        $shipping_country = $billing_country;
    } else {
        // Get shipping details from form if not checked
        $shipping_full_name = $con->real_escape_string($_POST['shipping_full_name']);
        $shipping_email = $con->real_escape_string($_POST['shipping_email']);
        $shipping_phone = $con->real_escape_string($_POST['shipping_phone']);
        $shipping_address = $con->real_escape_string($_POST['shipping_address']);
        $shipping_city = $con->real_escape_string($_POST['shipping_city']);
        $shipping_zip_code = $con->real_escape_string($_POST['shipping_zip_code']);
        $shipping_country = $con->real_escape_string($_POST['shipping_country']);
    }

    // Sanitize cart data
    $cart_items = json_decode($_POST['cart_items'], true); // Decode the cart data from JSON
    $subtotal = (float) $_POST['subtotal'];
    $sgst = (float) $_POST['sgst'];
    $gst = (float) $_POST['gst'];
    $total_tax = (float) $_POST['total_tax'];
    $grand_total = (float) $_POST['grand_total'];

    // Convert the cart items array to JSON for storage
    $cart_items_json = json_encode($cart_items);

    // Insert order details into the `orders` table for each item in the cart
    foreach ($cart_items as $item) {
        $product_name = $con->real_escape_string($item['name']);
        $product_image = $con->real_escape_string($item['image']);
        $price = (float) $item['price'];
        
        // Get the current value of quantity
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 0;  // Default to 0 if not set

        // Calculate total price for the item
        $total_price = $price * $quantity;

        // Insert into orders table
        $insert_order_query = "
            INSERT INTO orders (billing_full_name, billing_email, billing_phone, billing_address, billing_city, billing_zip_code, billing_country,
                                shipping_full_name, shipping_email, shipping_phone, shipping_address, shipping_city, shipping_zip_code, shipping_country,
                                cart_items, subtotal, sgst, gst, total_tax, grand_total, product_name, product_image, price, quantity, total_price)
            VALUES ('$billing_full_name', '$billing_email', '$billing_phone', '$billing_address', '$billing_city', '$billing_zip_code', '$billing_country',
                    '$shipping_full_name', '$shipping_email', '$shipping_phone', '$shipping_address', '$shipping_city', '$shipping_zip_code', '$shipping_country',
                    '$cart_items_json', '$subtotal', '$sgst', '$gst', '$total_tax', '$grand_total', '$product_name', '$product_image', '$price', '$quantity', '$total_price')";

        // Execute order insert query
        if (!$con->query($insert_order_query)) {
            throw new Exception("Error placing order: " . $con->error);
        }
    }

    echo "Order placed successfully!";
}
?>
