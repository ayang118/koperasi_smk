<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    // Validasi upload file
    if (move_uploaded_file($tmp, "../../image/$image")) {
        // Koneksi database
        $conn = new mysqli('localhost', 'root', '', 'db_koperasismk');
        
        // Cek koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Insert data
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $description, $price, $stock, $image);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    // Redirect setelah insert (mencegah double insert saat refresh)
    header("Location: dashboard.php");
    exit;
}
?>
