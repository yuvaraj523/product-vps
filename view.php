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

.content-card {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.content-card img {
    max-width: 30%;
    border-radius: 8px;
    margin-bottom: 20px;
    display: block;
}

.content-card table {
    width: 100%;
    border-collapse: collapse;
}

.content-card table th, .content-card table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.content-card table th {
    text-align: left;
    background-color: #f4f4f4;
    font-weight: bold;
}

    </style>
</head>

<body>
    <div class="sidebar">
        <h2><i class="fa fa-eye" aria-hidden="true"></i> view product</h2>
        <ul>
            <li><a href="add_product.php"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Products</a></li>
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
            Welcome to Your product details
            <div class="admin-info">
                <img src="https://cdn.pixabay.com/photo/2019/08/11/11/28/man-4398724_1280.jpg" alt="Admin Image" class="admin-image">
                <div class="admin-name">Admin</div>
            </div>
        </div>
        <br>
        
        <?php
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
        ?>

        <div class="content-card">
            <h1>Product Details</h1><br><br>
            <?php if (!empty($currentProductImage)) { ?>
                <img src="uploads/<?php echo htmlspecialchars($currentProductImage); ?>" alt="Product Image">
            <?php } ?>
            
            <table>
                <tr>
                    <th>Product Name</th>
                    <td><?php echo htmlspecialchars($currentProductName); ?></td>
                </tr>
                <tr>
                    <th>Product Description</th>
                    <td><?php echo htmlspecialchars($currentProductDescription); ?></td>
                </tr>
                <tr>
                    <th>Product Category</th>
                    <td><?php echo htmlspecialchars($currentProductCategory); ?></td>
                </tr>
                <tr>
                    <th>Keyword</th>
                    <td><?php echo htmlspecialchars($currentKeyword); ?></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>$<?php echo htmlspecialchars($currentPriceAmount); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <script>
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
</body>
</html>
