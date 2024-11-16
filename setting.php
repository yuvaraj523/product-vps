<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siteTitle = htmlspecialchars($_POST['siteTitle']);
    $adminEmail = htmlspecialchars($_POST['adminEmail']);

    $sql = "UPDATE setting SET site_title = ?, admin_email = ? WHERE id = 1";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ss', $siteTitle, $adminEmail);

    if ($stmt->execute()) {
        $message = "Settings updated successfully!";
    } else {
        $message = "Error updating settings. Please try again.";
    }
}

$sql = "SELECT * FROM setting WHERE id = 1";
$result = $con->query($sql);
$settings = $result->fetch_assoc();

$siteTitle = isset($settings['site_title']) ? htmlspecialchars($settings['site_title']) : '';
$adminEmail = isset($settings['admin_email']) ? htmlspecialchars($settings['admin_email']) : '';
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
    padding: 10px;
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
.form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus {
            border-color: #298d8f;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #298d8f;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #3498db;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            margin: auto;
        }
        .container div{
            width: 300px;
        }

        .message {
            background-color: #298d8f;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }


</style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Product</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
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
            Welcome to Your Product World
            <div class="admin-info">
                <img src="https://cdn.pixabay.com/photo/2019/08/11/11/28/man-4398724_1280.jpg" alt="Admin Image" class="admin-image">
                <div class="admin-name">Admin</div>
            </div>
        </div>

        <div class="container">
            <h2>Settings</h2>
            <?php if (isset($message)) : ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="settings.php" method="post">
                <div class="form-group">
                    <label for="siteTitle">Site Title:</label>
                    <input type="text" id="siteTitle" name="siteTitle" value="<?php echo $siteTitle; ?>">
                </div>
                <div class="form-group">
                    <label for="adminEmail">Admin Email:</label>
                    <input type="email" id="adminEmail" name="adminEmail" value="<?php echo $adminEmail; ?>">
                </div>
                <div class="form-group">
                    <button type="submit">Save Settings</button>
                </div>
            </form>
        </div>
    </div>    <script>
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
