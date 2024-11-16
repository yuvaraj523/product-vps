<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 5px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #007bff;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .styled-table th, .styled-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .styled-table td img {
            border-radius: 5px;
        }

        .edit-btn {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .edit-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .no-orders {
            text-align: center;
            font-size: 1.2em;
            color: #888;
        }
    </style>
</head>
<body>

<?php
include('db.php');

// Fetch orders from the database
$query = "SELECT * FROM orders ORDER BY order_id";
$result = $con->query($query);

// Check if there are any orders
if ($result && $result->num_rows > 0) {
    echo '<table id="ordersTable" class="styled-table">';
    echo '<thead>
            <tr>
                <th>Order ID</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Subtotal</th>
                <th>SGST</th>
                <th>GST</th>
                <th>Total Tax</th>
                <th>Grand Total</th>
                <th>Billing Full Name</th>
                <th>Billing Email</th>
                <th>Billing Phone</th>
                <th>Billing Address</th>
                <th>Billing City</th>
                <th>Billing ZIP Code</th>
                <th>Billing Country</th>
                <th>Shipping/Billing</th>
                <th>Shipping Full Name</th>
                <th>Shipping Email</th>
                <th>Shipping Phone</th>
                <th>Shipping Address</th>
                <th>Shipping City</th>
                <th>Shipping ZIP Code</th>
                <th>Shipping Country</th>
                <th>Action</th>
            </tr>
          </thead>';
    echo '<tbody>';
    
    // Display each order in a row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['order_id']) . '</td>';
        echo '<td><img src="' . htmlspecialchars($row['product_image']) . '" alt="Product Image" style="width: 80px; height: auto; border-radius: 5px;"></td>';
        echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
        echo '<td>' . number_format((float)$row['price'], 2) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . number_format((float)$row['total_price'], 2) . '</td>';
        echo '<td>' . number_format((float)$row['subtotal'], 2) . '</td>';
        echo '<td>' . number_format((float)$row['sgst'], 2) . '</td>';
        echo '<td>' . number_format((float)$row['gst'], 2) . '</td>';
        echo '<td>' . number_format((float)$row['total_tax'], 2) . '</td>';
        echo '<td>' . number_format((float)$row['grand_total'], 2) . '</td>';
        echo '<td>' . htmlspecialchars($row['billing_full_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['billing_email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['billing_phone']) . '</td>';
        echo '<td>' . htmlspecialchars($row['billing_address']) . '</td>';
        echo '<td>' . htmlspecialchars($row['billing_city']) . '</td>'; 
        echo '<td>' . htmlspecialchars($row['billing_zip_code']) . '</td>'; 
        echo '<td>' . htmlspecialchars($row['billing_country']) . '</td>'; 
        echo '<td>' . htmlspecialchars($row['shipping_billing']) . '</td>';
        echo '<td>' . htmlspecialchars($row['shipping_full_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['shipping_email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['shipping_phone']) . '</td>';
        echo '<td>' . htmlspecialchars($row['shipping_address']) . '</td>';
        echo '<td>' . htmlspecialchars($row['shipping_city']) . '</td>'; 
        echo '<td>' . htmlspecialchars($row['shipping_zip_code']) . '</td>'; 
        echo '<td>' . htmlspecialchars($row['shipping_country']) . '</td>'; 
        echo '<td>
       
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p class="no-orders">No orders found.</p>';
}

// Close the database connection
$con->close();
?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    // Initialize DataTables
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            "pageLength": 10,  // Show 10 entries per page by default
            "ordering": true,  // Enable sorting
            "searching": true, // Enable search functionality
            "info": true       // Display table info (e.g., "Showing 1 to 10 of 50 entries")
        });
    });
</script>

</body>
</html>
