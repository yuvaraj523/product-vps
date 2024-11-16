<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Body Styling */
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

/* Sidebar */
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

.sidebar h2 {
    font-size: 24px;
    margin: 0;
    color: #298d8f;
    text-align: center;
    animation: slideInLeft 0.5s ease-in-out;
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
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #e0e0e0;
}

.sidebar ul li i {
    margin-right: 15px;
    font-size: 20px;
    color: #298d8f;
}

/* Main Content */
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

/* Header */
.header {
    background-color: #298d8f;
    padding: 20px 50px;
    color: #ecf0f1;
    font-size: 20px;
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

/* Form Container */
.form-container {
    width: 100%;
    max-width: 600px;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Form Row */
.form-row {
    margin-bottom: 20px;
    position: relative;
}

.form-row label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-row input[type="text"],
.form-row textarea,
.form-row input[type="file"] {
    width: 100%;
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.form-row input[type="file"] {
    padding: 5px;
}

.form-row textarea {
    resize: vertical;
}

.form-row input[type="text"]:focus,
.form-row textarea:focus {
    border-color: #009688;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 150, 136, 0.4);
}

/* Button Styling */
.form-container button {
    width: 100%;
    padding: 12px 20px;
    background-color: #009688;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-container button:hover {
    background-color: #00796b;
}

/* Image Preview Styling */
.current-image-container img {
    margin-top: 10px;
    max-width: 100px;
    border-radius: 4px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Animation Effects */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
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

@keyframes slideInLeft {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(0); }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
    .form-container {
        padding: 20px;
    }
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

        <form action="addcategory_submit.php" method="post" enctype="multipart/form-data">
    <div class="form-row">
        <label for="categoryimage">
            <i class="fa fa-file-image" aria-hidden="true"></i> Category Image
        </label>
        <input type="file" id="categoryimage" name="categoryimage" onchange="previewImage(event)"><br><br>
        <div id="imagePreviewContainer" class="current-image-container">
            <?php if (!empty($currentcategoryImage)) { ?>
                <img src="uploads/<?php echo htmlspecialchars($currentcategoryImage); ?>" alt="Category Image" width="100">
            <?php } ?>
        </div>
    </div>

    <div class="form-row">
        <label for="categoryName"><i class="fas fa-tag"></i> Category Name</label>
        <input type="text" id="categoryName" name="categoryname" required>
    </div>

    <div class="form-row">
        <label for="categoryDescription"><i class="fas fa-pencil-alt"></i> Category Description</label>
        <textarea id="categoryDescription" name="categorydescription" rows="4" required></textarea>
    </div>

    <div class="form-row">
        <button type="submit">Submit</button>
    </div>
</form>

<script>
function previewImage(event) {
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    imagePreviewContainer.innerHTML = ''; // Clear previous images
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.width = 100; // Set the width for preview
            imagePreviewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
}
</script>

</body>
</html>
