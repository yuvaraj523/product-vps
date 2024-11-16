<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('db.php');

if (isset($_GET['id'])) {
    $id = $con->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM list WHERE id='$id'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentProductName = $row['product_name'];
        $currentProductDescription = $row['product_description'];
        $currentProductCategory = $row['product_category'];
        $currentKeyword = $row['keyword'];
        $currentPriceAmount = $row['price_amount'];
        $currentProductImage = $row['product_image'];
    } else {
        echo "<p>No record found with ID: $id</p>";
        exit();
    }
} else {
    echo "<p>No ID provided!</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $con->real_escape_string($_POST['productName']);
    $productDescription = $con->real_escape_string($_POST['productDescription']);
    $productCategory = $con->real_escape_string($_POST['productCategory']);
    $keyword = $con->real_escape_string($_POST['keyword']);
    $priceAmount = $con->real_escape_string($_POST['priceAmount']);

    $target_dir = "uploads/";
    $productImage = $currentProductImage;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0) {
        $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
        $productImage = basename($_FILES["productImage"]["name"]);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
                echo "<p>The file " . htmlspecialchars($productImage) . " has been uploaded.</p>";
            } else {
                echo "<p>Sorry, there was an error uploading your file.</p>";
            }
        } else {
            echo "<p>Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.</p>";
        }
    }


    $sql = "UPDATE list SET
                product_image='$productImage',
                product_name='$productName',
                product_description='$productDescription',
                product_category='$productCategory',
                keyword='$keyword',
                price_amount='$priceAmount'
                WHERE id='$id'";

    if ($con->query($sql) === TRUE) {
        header('Location: list.php?message=update_success');
        exit;
    } else {
        echo "<p>Error updating record: " . $con->error . "</p>";
    }
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

.form-row i {
    padding-left: 1px;
    color: #298d8f;
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
            Welcome to Your Product World
            <div class="admin-info">
                <img src="https://cdn.pixabay.com/photo/2019/08/11/11/28/man-4398724_1280.jpg" alt="Admin Image" class="admin-image">
                <div class="admin-name">Admin</div>
            </div>
        </div>

        <div class="form-container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="form-row">
                        <label for="productImage"><i class="fa fa-file-image" aria-hidden="true"></i> Product Image</label>
                        <input type="file" id="productImage" name="productImage"><br><br>
                        <?php if (!empty($currentProductImage)) { ?>
                            <div class="current-image-container">
                                <img src="uploads/<?php echo $currentProductImage; ?>" alt="Product Image" width="100">
                            </div>
                        <?php } ?>
              
                
                
                    <div class="form-row">
                        <label for="productName"><i class="fas fa-tag"></i> Product Name</label>
                        <input type="text" id="productName" name="productName" value="<?php echo $currentProductName; ?>" required><br><br>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-row">
                        <label for="productDescription"><i class="fas fa-pencil-alt"></i> Product Description</label>
                        <textarea id="productDescription" name="productDescription" required><?php echo $currentProductDescription; ?></textarea><br><br>
                    </div>
                </div>

                <div class="form-row">
    <label for="productCategory"><i class="fas fa-cube"></i> Product Category</label>
    <div class="custom-select">
        <div class="selected" id="selectedOption"><?php echo htmlspecialchars($currentProductCategory); ?></div>
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
        <input type="hidden" id="productCategory" name="productCategory" value="<?php echo htmlspecialchars($currentProductCategory); ?>" required>
    </div>
</div>

<div class="form-row">
                    <div class="form-row">
                        <label for="keyword"><i class="fas fa-key"></i> Keyword</label>
                        <input type="text" id="keyword" name="keyword" value="<?php echo $currentKeyword; ?>" required><br><br>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-row">
                        <label for="priceAmount"><i class="fa fa-inr" aria-hidden="true"></i> Price Amount</label>
                        <input type="text" id="priceAmount" name="priceAmount" value="<?php echo $currentPriceAmount; ?>" required><br><br>
                    </div>
                </div>
                <div class="form-row">
    <label for="productPDF">
        <i class="fa fa-file-pdf" aria-hidden="true"></i> Product PDF
    </label>
    <input type="file" id="productPDF" name="productPDF" accept=".pdf"><br><br>
    
    <?php if (!empty($currentProductPDF) && file_exists('uploads/' . $currentProductPDF)) : ?>
        <div class="current-pdf-container">
            <p>Current PDF: 
                <a href="<?php echo 'uploads/' . htmlspecialchars($currentProductPDF); ?>" target="_blank" rel="noopener noreferrer">
                    View PDF
                </a>
            </p>
        </div>
    <?php endif; ?>
</div>
<div class="form-row">
                    <div class="form-row">
                        <input type="submit" value="Update Product">
                    </div>
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

const customSelect = document.querySelector('.custom-select');
const selected = document.getElementById('selectedOption');
const optionsList = document.getElementById('optionsList');
const hiddenInput = document.getElementById('productCategory');


selected.addEventListener('click', () => {
    optionsList.classList.toggle('show');
});


optionsList.addEventListener('click', (e) => {
    if (e.target.tagName === 'LI') {
        selected.textContent = e.target.textContent;
        hiddenInput.value = e.target.getAttribute('data-value'); 
        optionsList.classList.remove('show');
    }
});


document.addEventListener('click', (e) => {
    if (!customSelect.contains(e.target)) {
        optionsList.classList.remove('show');
    }
});
</script>
</body>
</html>

