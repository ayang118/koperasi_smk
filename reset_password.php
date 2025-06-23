<?php
session_start();
include('config/db.php');

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $email);
        if ($update_stmt->execute()) {
            $message = "🎉 Password berhasil direset!";
        } else {
            $message = "❌ Gagal mereset password!";
        }
    } else {
        $message = "📧 Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Koperasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            background: white;
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
            text-align: center;
            width: 350px;
            animation: popUp 0.8s ease-in-out;
        }

        .icon-wrapper {
            margin-bottom: 10px;
        }

        .icon-wrapper span {
            font-size: 50px;
            background-color: #fff;
            border-radius: 50%;
            padding: 12px;
            border: 3px solid #74ebd5;
            display: inline-block;
        }

        h2 {
            margin: 15px 0 20px 0;
            color: #333;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: all 0.3s ease;
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
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #56c4ba;
        }

        .message-box {
            margin: 10px 0 20px;
            padding: 12px;
            border-left: 5px solid;
            font-size: 14px;
            border-radius: 5px;
            animation: fadeIn 0.5s ease;
            transition: opacity 0.5s ease;
        }

        .message-success {
            background: #e6fff2;
            border-color: #1abc9c;
            color: #1e7e6e;
        }

        .message-error {
            background: #f8f8f8;
            border-color: #74ebd5;
            color: #444;
        }

        p a {
            color: #74ebd5;
            text-decoration: none;
            font-size: 14px;
        }

        p a:hover {
            text-decoration: underline;
        }

        @keyframes popUp {
            from { transform: scale(0.9); opacity: 0; }
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
        <div class="icon-wrapper">
            <span>🔐</span>
        </div>
        <h2>Reset Password</h2>
        <?php if ($message): ?>
            <div id="message-box" class="message-box <?php echo (strpos($message, 'berhasil') !== false) ? 'message-success' : 'message-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Anda" required>
            <input type="password" name="new_password" placeholder="Password Baru" required>
            <button type="submit">Reset Password</button>
        </form>
        <p><a href="login.php">⬅️ Kembali ke Login</a></p>
    </div>

    <!-- Pesan otomatis hilang -->
    <script>
        setTimeout(() => {
            const box = document.getElementById('message-box');
            if (box) {
                box.style.opacity = '0';
                setTimeout(() => box.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>
