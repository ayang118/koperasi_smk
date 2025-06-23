<?php
// Koneksi database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_koperasismk';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil nama file gambar (jika ada)
$getImage = $conn->query("SELECT image FROM products WHERE id = $id LIMIT 1");
$imageFile = '';
if ($getImage && $getImage->num_rows > 0) {
    $imageRow = $getImage->fetch_assoc();
    $imageFile = $imageRow['image'];
}

// Hapus data dari database
$delete = $conn->query("DELETE FROM products WHERE id = $id");

if ($delete) {
    // Hapus gambar dari folder jika ada
    if (!empty($imageFile) && file_exists("../../image/$imageFile")) {
        unlink("../../image/$imageFile");
    }

    echo "<script>
        alert('Produk berhasil dihapus.');
        window.location.href = 'product_list.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus produk.');
        window.location.href = 'product_list.php';
    </script>";
}

$conn->close();
?>
