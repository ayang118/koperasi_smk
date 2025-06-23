<?php
session_start();
include('config/db.php');

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] == 'admin') {
                header("Location: pages/admin/dashboard.php");
            } else {
                header("Location: pages/user/dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Koperasi Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1s ease-in;
        }

        .login-container {
            background: white;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
            text-align: center;
            width: 360px;
            animation: popUp 0.8s ease-in-out;
            position: relative;
        }

        .login-container img {
            width: 90px;
            height: 90px;
            margin-bottom: 15px;
            border-radius: 50%;
            border: 3px solid #74ebd5;
            object-fit: cover;
        }

        h2 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .error {
            background: #ffe6e6;
            color: #cc0000;
            padding: 10px;
            border-left: 4px solid #ff4d4d;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: 0.3s ease;
        }

        input:focus {
            border-color: #74ebd5;
            outline: none;
            box-shadow: 0 0 5px rgba(116,235,213,0.6);
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        p a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            display: inline-block;
        }

        p a:hover {
            text-decoration: underline;
        }

        @keyframes popUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="SMK.jpg" alt="Logo Sekolah">
        <h2>Login Admin/User</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p><a href="reset_password.php">Lupa password?</a></p>
    </div>
</body>
</html>
