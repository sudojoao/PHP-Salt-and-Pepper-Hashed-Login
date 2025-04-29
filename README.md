# Secure PHP Authentication System using Hash and Pepper 

A simple yet secure PHP authentication system featuring peppered password hashing, Argon2id for secure password storage, and protection against common timing attacks.

## Features

- 🔒 Secure login system
- 👨‍💻 Admin user creation
- 🌶️ Peppered passwords (HMAC-SHA256 + Argon2id)
- ⏱️ Timing-attack protection
- 🔄 Session-based authentication

## How It Works

### Login System
1. Users submit credentials via POST request
2. Password gets peppered (using secret from `config.conf`) and hashed
3. System checks credentials securely with timing-attack protection
4. Successful login redirects to example page with valid session
5. Passwords are peppered and hashed before storage

## Setup

### 1. Pepper Setup
Setup `config.conf`:
```ini
pepper = YourSecretPepperValueHere
```
### 2. Setup User Table
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_type` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```
## Example working Login
> User: admin1

> Pass: admin1

> Hashed: $argon2id$v=19$m=65536,t=4,p=1$WWZoUC52V3lFTDZONUxweA$RAY1KpCNaGmmxjB6RYysq7HwprNVs0Oswspg5p5NEV0
