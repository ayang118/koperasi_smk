<?php
include('../../config/db.php'); // Koneksi ke database

// Pastikan ID produk tersedia
if (!isset($_POST['id'])) {
    die("ID produk tidak ditemukan.");
}

$id = intval($_POST['id']);
$name = $_POST['name'];
$description = $_POST['description'];
$price = floatval($_POST['price']);
$stock = intval($_POST['stock']);

// Cek apakah user mengupload gambar baru
if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $uploadDir = '../../image/';

    // Pindahkan file ke folder image
    if (move_uploaded_file($tmp, $uploadDir . $image)) {
        $update = "UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ssdssi", $name, $description, $price, $stock, $image, $id);
    } else {
        die("Gagal mengupload gambar.");
    }
} else {
    // Jika gambar tidak diubah
    $update = "UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $id);
}

if ($stmt->execute()) {
    echo "<script>alert('Produk berhasil diperbarui'); window.location.href='product_list.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui produk'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
