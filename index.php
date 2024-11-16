<?php
session_start();
$con = new mysqli('localhost', 'root', '', 'product_db');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $username = $con->real_escape_string($username);
    $password = $con->real_escape_string($password);

    // Fetch user details including role
    $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Fetch user data
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];  // Store role in session
        
        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header("Location: list.php");
        } else {
            header("Location: add_product.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }
}

$con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="styles.css">
<style>
 

body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: rgb(41, 141, 143);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background-color: lightblue;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 40px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    position: relative;
    animation: fadeIn 1s ease-out;
}

.login-container h2 {
    margin-bottom: 20px;
    font-size: 28px;
    color: lightseagreen;
}

.login-container label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    color: #666;
    text-align: left;
}

.login-container input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
    position: relative;
}

.login-container input:focus {
    border-color: #4b6cb7;
    outline: none;
}

.login-container button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    background-color: #298d8f;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.login-container button:hover {
    background-color: #298d8f;
}

.login-container a {
    display: block;
    margin-top: 15px;
    color: #298d8f;
    text-decoration: none;
    font-size: 14px;
}

.login-container a:hover {
    text-decoration: underline;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 480px) {
    .login-container {
        padding: 20px;
        max-width: 90%;
    }

    .login-container h2 {
        font-size: 24px;
    }

    .login-container input,
    .login-container button {
        font-size: 14px;
    }
}

.form-group {
    position: relative;
    margin-bottom: 20px;
}

.form-group i {
    position: absolute;
    right: 15px;
    top: 60px;
    transform: translateY(-50%);
    color: #298d8f;
    font-size: 20px;
    cursor: pointer;
}

.form-group input {
    padding-right: 40px;
}

</style>
</head>
<body>
    <div class="login-container">
<h2>Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>

