<?php
session_start();
require_once 'connect.php';
$db = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = 'create_user';
    
    if ($action == 'create_user') {
        $config_content = parse_ini_file('config.conf');
        $pepper = $config_content['pepper'];
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];

        $pwd_pepper = hash_hmac("sha256", $password, $pepper);
        
        $options = [
            'cost' => 13,
        ];

        $hashed_password = password_hash($pwd_pepper, PASSWORD_ARGON2ID, $options);

        $stmt = $db->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $user_type]);

        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    
    <form method="POST" action="register.php" class="register-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        
        <button type="submit" class="submit-button">Register</button>
    </form>
    
    <p><a href="login.php">Login</a></p>
</body>
</html>