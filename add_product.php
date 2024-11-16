<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
include('db.php');
$sql = "SELECT * FROM addcategory";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f2f4f7;
            color: #333;
            display: flex;
            height: 100vh;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .sidebar {
            width: 190px;
            background-color: whitesmoke;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100%;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .main-content {
            flex: 1;
            margin-left: 190px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: margin-left 0.3s ease-in-out;
            animation: fadeInUp 0.7s ease-in-out;
        }

        .main-content.shifted {
            margin-left: 0;
            width: 100%;
        }

        .sidebar i {
            color: #298d8f;
        }

        .sidebar h2 {
            font-size: 24px;
            margin: 0;
            color: #298d8f;
            text-align: center;
            animation: slideInLeft 0.5s ease-in-out;
        }

        @keyframes slideInLeft {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(0); }
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-top: 30px;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 18px;
            padding: 12px;
            border-radius: 8px;
        }

        .sidebar ul li a:hover {
            color: #333;
            background-color: white;
        }

        .sidebar ul li i {
            margin-right: 15px;
            font-size: 20px;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background-color: #298d8f;
            padding: 20px 50px;
            color: #ecf0f1;
            font-size: 20px;
            border-bottom: 2px solid #298d8f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .admin-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .admin-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .admin-name {
            font-size: 14px;
            margin-top: 5px;
            color: #ecf0f1;
        }

        .dashboard-cards {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background-color: white;
            color: #3498db;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 220px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            margin-top: 0;
            font-size: 22px;
            color: #3498db;
        }

        .card p {
            font-size: 18px;
            color: #555;
            margin: 10px 0 0;
        }


        .form-container {
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .form-row {
            margin-bottom: 15px;
        }
        .form-row i{
            padding-left: 1px;
            color:#298d8f;
        }

        .form-row label {
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .form-row input, .form-row textarea, .form-row select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-row input[type="submit"] {
            background-color: #298d8f;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 15px;
            border-radius: 4px;
        }

        .form-row input[type="submit"]:hover {
            background-color: #1f6d6d;
        }
        .custom-select {
    position: relative;
    width: 100%; 
}

.selected {
    padding: 10px;
    border: 1px solid #ddd; 
    border-radius: 4px; 
    cursor: pointer; 
}

.options {
    position: absolute; 
    width: 100%; 
    max-height: 150px;
    overflow-y: auto; 
    border: 1px solid #ddd; 
    border-radius: 4px; 
    background: white;
    z-index: 10; 
    display: none;
}

.options li {
    padding: 10px;
    cursor: pointer; 
    list-style-type: none;
}

.options li:hover {
    background-color: #f1f1f1; 
}

.disabled {
    color: gray; 
}

.options.show {
    display: block;
}

    </style>
</head>

<body>
    <div class="sidebar">
        <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Product</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="list.php"><i class="fa fa-list" aria-hidden="true"></i> List Products</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="setting.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Overview</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <button class="toggle-btn" id="toggleBtn"><i class="fas fa-bars"></i></button>
            Welcome to Your product world
            <div class="admin-info">
                <img src="https://cdn.pixabay.com/photo/2019/08/11/11/28/man-4398724_1280.jpg" alt="Admin Image" class="admin-image">
                <div class="admin-name">Admin</div>
            </div>
        </div>
        <br>
        <div class="form-container">
            <form action="submit_form.php" method="post" enctype="multipart/form-data">
                <div class="form-row">
                <label for="productImage">
        <i class="fa fa-file-image" aria-hidden="true"></i> Product Image
    </label>
<input type="file" id="productImage" name="productImage" onchange="previewImage(event)"><br><br>
   <div id="imagePreviewContainer" class="current-image-container">
        <?php if (!empty($currentProductImage)) { ?>
            <img src="uploads/<?php echo $currentProductImage; ?>" alt="Product Image" width="100">
        <?php } ?>
    </div>
</div>
<div class="form-row">
                    <label for="productName"><i class="fas fa-tag"></i> Product Name</label>
                    <input type="text" id="productName" name="productName" required>
                </div>

                <div class="form-row">
                    <label for="productDescription"><i class="fas fa-pencil-alt"></i> Product Description</label>
                    <textarea id="productDescription" name="productDescription" rows="4" required></textarea>
                </div>
                <div class="form-row">
                <label for="productCategory"><i class="fas fa-cube"></i> Product Category</label>
<div class="custom-select">
    <div class="selected" id="selectedOption">Select one</div>
    <ul class="options" id="optionsList">
        <li data-value="" class="disabled" selected>Select one</li>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li data-value="<?php echo htmlspecialchars($row['categoryname']); ?>">
                    <?php echo htmlspecialchars($row['categoryname']); ?>
                </li>
            <?php endwhile; ?>
        <?php endif; ?>
    </ul>
    <input type="hidden" id="productCategory" name="productCategory" required>
</div>
<div class="form-row">
                    <label for="keyword"><i class="fas fa-key"></i> Keyword</label>
                    <input type="text" id="keyword" name="keyword" required>
                </div>

                <div class="form-row">
                    <label for="priceAmount"><i class="fa fa-inr" aria-hidden="true"></i> Price Amount</label>
                    <input type="text" id="priceAmount" name="priceAmount" required>
                </div>
                <div class="form-row">
            <label for="productPDF"><i class="fas fa-file-pdf"></i> Upload PDF</label>
            <input type="file" id="productPDF" name="productPDF" accept=".pdf" required>
        </div>
                <div class="form-row">
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.createElement('img');
        output.src = reader.result;
        output.width = 100;
        var container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        container.appendChild(output);
    }
    reader.readAsDataURL(event.target.files[0]);
}
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('closed');
                mainContent.classList.toggle('shifted');
            });
        });
    </script>
    <script>
// Get elements
const customSelect = document.querySelector('.custom-select');
const selected = document.getElementById('selectedOption');
const optionsList = document.getElementById('optionsList');
const hiddenInput = document.getElementById('productCategory');

// Toggle dropdown
selected.addEventListener('click', () => {
    optionsList.classList.toggle('show');
});

// Set selected value and close dropdown
optionsList.addEventListener('click', (e) => {
    if (e.target.tagName === 'LI') {
        selected.textContent = e.target.textContent;
        hiddenInput.value = e.target.getAttribute('data-value'); // Set the hidden input value
        optionsList.classList.remove('show'); // Close the dropdown
    }
});

// Close the dropdown if clicking outside of it
document.addEventListener('click', (e) => {
    if (!customSelect.contains(e.target)) {
        optionsList.classList.remove('show');
    }
});
</script>
</body>
</html>
