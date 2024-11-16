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
    <title>List Dashboard</title>
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


.sidebar.closed ~ .main-content {
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

        .table-container {
            width: 100%;
            overflow: auto;
            max-height: calc(100vh - 220px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #298d8f;
            color: white;
        }

        table td img {
            max-width: 100px;
            border-radius: 8px;
        }

        table tr:hover {
            background-color: #f2f4f7;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        .alert-popup {
            position: fixed;
            top: 10%;
            right: 10%;
            background-color: #4caf50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 2000;
            animation: fadeIn 0.5s ease;
        }

        .alert-popup .close {
            cursor: pointer;
            color: white;
            float: right;
            font-size: 20px;
            margin-left: 10px;
        }

        .actions a {
            margin-right: 10px;
            color: #298d8f;
            text-decoration: none;
            font-size: 16px;
        }

        .actions a:hover {
            color: #e74c3c;
        }
        .pdf-link {
    text-decoration: none; 
    color: #d50000; 
    display: flex; 
    align-items: center; 
}

.pdf-link:hover {
    color: #c20000; 
}

.pdf-link i {
    margin-right: 8px; 
    font-size: 1.2em; 
}

.link-text {
    display: none; 
}


.pdf-link:focus .link-text,
.pdf-link:hover .link-text {
    display: inline;
}

</style>
</head>
<body>
    
    <div class="sidebar" id="sidebar">
       <h2> <i class="fa fa-list" aria-hidden="true"></i> List Products</h2>
        <ul>
        <li><a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</li>
        <li><a href="add_product.php"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Products</a></li>
        <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="setting.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Overview</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <button class="toggle-btn" id="toggleBtn"><i class="fas fa-bars"></i></button>
            Welcome to Your product list page
            <div class="admin-info">
                <img src="https://cdn.pixabay.com/photo/2019/08/11/11/28/man-4398724_1280.jpg" alt="Admin Image" class="admin-image">
                <div class="admin-name">Admin</div>
            </div>
        </div>
        <br>
        <div class="table-container">
            <table>
        <?php if (isset($_GET['message'])): ?>
        <?php if ($_GET['message'] == 'delete_success'): ?>
            <div id="success-message" class="alert-popup">
                <span class="close" onclick="closeAlert()">&times;</span>
                Record deleted successfully.
            </div>
        <?php elseif ($_GET['message'] == 'update_success'): ?>
            <div id="success-message" class="alert-popup">
                <span class="close" onclick="closeAlert()">&times;</span>
                Record updated successfully.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    include('db.php');
    $sql = "SELECT * FROM list";
    $result = $con->query($sql);
    ?>

    <table>
        <thead>
            <tr>
                <th>S.no</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Keyword</th>
                <th>Price</th>
                <th>Pdf</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $i = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" style="width: 100px; height: auto;"></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_category']); ?></td>
                        <td><?php echo htmlspecialchars($row['keyword']); ?></td>
                        <td><?php echo htmlspecialchars($row['price_amount']); ?></td>
                        <td><a href="uploads/<?php echo htmlspecialchars($row['product_pdf']); ?>" target="_blank" class="pdf-link"> <i class="fas fa-file-pdf"></i> 
        <span class="link-text"></span> 
    </a>
</td>
 <td class="actions">
                            <a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit"></i></a>
                            <a href="view.php?id=<?php echo $row['id']; ?>"><i class="fas fa-eye"></i></a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>

                    <td colspan="8">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </table>
            </div>
    

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('closed');
            if (sidebar.classList.contains('closed')) {
                mainContent.style.marginLeft = '0';
            } else {
                mainContent.style.marginLeft = '190px';
            }
        });
     document.querySelectorAll('.dropdown-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
            var dropdown = this.nextElementSibling;
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
    });

    function closeAlert() {
        var alertPopup = document.getElementById('success-message');
        alertPopup.style.display = 'none';
    }

    window.onload = function() {
        var alertPopup = document.getElementById('success-message');
        if (alertPopup) {
            setTimeout(function() {
                alertPopup.style.display = 'none';
            }, 5000);
        }
    };
    </script>
</body>
</html>
