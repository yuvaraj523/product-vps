<?php
// Include database connection
include('db.php');

// Initialize category variable
$category = '';

// Check if category is set in the URL
if (isset($_GET['category'])) {
    $category = $con->real_escape_string($_GET['category']); // Escape input to prevent SQL injection

    // Prepare and execute the query
    $sql = "SELECT * FROM list WHERE product_category = '$category'";
    $result = $con->query($sql);
}

// Fetch categories from the database for the dropdown
$sql_categories = "SELECT * FROM addcategory";
$result_categories = $con->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products by Category</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #2a4d69;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #4b86b4;
        }

        /* Dropdown Container */
        .dropdown {
            float: left;
            overflow: hidden;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .navbar .login {
            float: right;
        }

        /* Product container */
        .product-container {
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px; 
            padding: 20px; /* Added padding for better spacing */
        }

        /* Individual product card */
        .product-card {
            background-color: #fff; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            width: calc(33.333% - 20px); 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
            transition: transform 0.2s; 
        }

        .product-card:hover {
            transform: scale(1.05); 
        }

        .card-content {
            padding: 15px; 
        }

        .product-image {
            max-width: 100%; 
            height: auto; 
            border-radius: 8px; 
        }

        .card-title {
            font-size: 1.25rem; 
            font-weight: bold; 
            color: #333; 
        }

        .card-text {
            color: #555; 
        }

        .card-text a {
            color: #007bff; 
            text-decoration: none; 
        }

        .card-text a:hover {
            text-decoration: underline; 
        }

        .add-to-cart-btn {
            background-color: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 15px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3; 
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="#home">Home</a>
        <div class="dropdown">
            <button class="dropbtn">Products</button>
            <div class="dropdown-content">
                <?php
                // Check if there are results and display them
                if ($result_categories && $result_categories->num_rows > 0): 
                    while ($row = $result_categories->fetch_assoc()): ?>
                        <a href="#" class="category-option" data-value="<?php echo htmlspecialchars($row['categoryname']); ?>">
                            <?php echo htmlspecialchars($row['categoryname']); ?>
                        </a>
                    <?php endwhile; 
                endif; ?>
            </div>
        </div>
        <a href="#services">Services</a>
        <a href="#contact">Contact Us</a>
        <a href="#about">About Us</a>
        <a class="login" href="#login">Login</a>
    </div>

    <?php if (isset($result) && $result->num_rows > 0): ?>
        <h2>Products in Category: <?php echo htmlspecialchars($category); ?></h2>
        <div class="product-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class='product-card'>
                    <div class='card-content'>
                        <h5 class='card-title'><i class='fas fa-box'></i> <?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p class='card-text'><strong>Description:</strong> <?php echo htmlspecialchars($row['product_description']); ?></p>
                        <p class='card-text'><strong>Price:</strong> $<?php echo htmlspecialchars($row['price_amount']); ?></p>

                        <?php if (!empty($row['product_image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" class="product-image">
                        <?php else: ?>
                            <p>No image available.</p>
                        <?php endif; ?>

                        <?php if (!empty($row['product_pdf'])): ?>
                            <p><strong>Product PDF:</strong> 
                                <a href="uploads/<?php echo htmlspecialchars($row['product_pdf']); ?>" target="_blank"><i class="fas fa-file-pdf"></i> View PDF</a>
                            </p>
                        <?php else: ?>
                            <p>No PDF available.</p>
                        <?php endif; ?>

                        <form action="addtocart.php" method="post">
                            <input type="hidden" name="items[<?php echo $row['id']; ?>][id]" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="items[<?php echo $row['id']; ?>][name]" value="<?php echo htmlspecialchars($row['product_name']); ?>">
                            <input type="hidden" name="items[<?php echo $row['id']; ?>][price]" value="<?php echo htmlspecialchars($row['price_amount']); ?>">
                            <input type="hidden" name="items[<?php echo $row['id']; ?>][image]" value="<?php echo htmlspecialchars($row['product_image']); ?>">
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>"> <!-- Add this line -->
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <h2>No products found in this category.</h2>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryOptions = document.querySelectorAll('.category-option');

            categoryOptions.forEach(option => {
                option.addEventListener('click', function(event) {
                    event.preventDefault();  
                    const selectedCategory = this.getAttribute('data-value');
                    window.location.href = 'category.php?category=' + encodeURIComponent(selectedCategory);
                });
            });
        });
    </script>

</body>
</html>
