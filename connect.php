<?php
if (!function_exists('connectDB')) {
    function connectDB() {
        try {
            $parse = parse_ini_file('db.env');
            $host = $parse['DB_HOST'];
            $database = $parse['DB_DATABASE'];
            $username = $parse['DB_USERNAME'];
            $password = $parse['DB_PASSWORD'];

            $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
            
        } catch(PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            return null;
        }
    }
}

?>