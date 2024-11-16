<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown Menu Example</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

     
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

        .dropdown:hover .dropbtn {
            background-color: #4b86b4;
        }

        .navbar .login {
            float: right;
        }

 
        .custom-select {
            position: relative;
            display: inline-block;
            margin-top: 20px;
        }

        .selected {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .options {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ddd;
            z-index: 1;
            width: 100%;
        }

        .options li {
            padding: 10px;
            cursor: pointer;
        }

        .options li:hover {
            background-color: #f1f1f1;
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
        // Fetch categories from the database
        include('db.php');
        $sql = "SELECT * FROM addcategory";
        $result = $con->query($sql);
        
        // Check if there are results and display them
        if ($result->num_rows > 0): 
            while ($row = $result->fetch_assoc()): ?>
                <!-- Add data-value to store categoryname and a click event to handle redirection -->
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
   
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all category options
        const categoryOptions = document.querySelectorAll('.category-option');

        // Add click event listener to each option
        categoryOptions.forEach(option => {
            option.addEventListener('click', function(event) {
                event.preventDefault();  // Prevent the default anchor behavior
                const selectedCategory = this.getAttribute('data-value');  // Get the selected value

                // Redirect to another page, passing the selected category via URL
                window.location.href = 'category.php?category=' + encodeURIComponent(selectedCategory);
            });
        });
    });
</script>

<script>
    // Add event listeners to category options
    document.querySelectorAll('.category-option').forEach(option => {
        option.addEventListener('click', function(event) {
            event.preventDefault();  // Prevent the default anchor behavior
            const selectedCategory = this.getAttribute('data-value');  // Get the selected value
            
            // Redirect to category.php, passing the selected category via URL
            window.location.href = 'category.php?category=' + encodeURIComponent(selectedCategory);
        });
    });
</script>
</body>
</html>
