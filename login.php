<?php
session_start();
require_once 'connect.php';
$db = connectDB();
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $config_content = parse_ini_file('config.conf');
    $pepper = $config_content['pepper'];

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $pwd_pepper = hash_hmac("sha256", $password, $pepper);
    
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // timing-attack mitigation using dummy hash
        $dummy_hash = '$argon2id$v=19$m=65536,t=4,made$by$github$@sudojoao$ThisIsADummyHashDoNotTryToUseIt';
        
        // Always verify a hash regardless
        $real_hash = $user ? $user['password'] : $dummy_hash;
        $password_check = password_verify($pwd_pepper, $real_hash);
        
        $is_valid = $user && hash_equals('1', $password_check ? '1' : '0');
        
        if ($is_valid) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } catch (PDOException $e) {
        $error = "Database error occurred";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="login.php" class="login-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="submit-button">Login</button>
    </form>
    
    <p><a href="register.php">Register</a></p>
</body>
</html>