<?php
if ($action == 'create_user' && isAdmin()) {
    $config_content = parse_ini_file('config.conf');
    $pepper = $config_content['pepper'];
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $pwd_pepper = hash_hmac("sha256", $password, $pepper);

    $hashed_password = password_hash($pwd_pepper, PASSWORD_ARGON2ID);

    $stmt = $db->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $user_type]);

    header("Location: example.php");
    exit;
}