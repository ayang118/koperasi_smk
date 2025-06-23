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

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data produk
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    echo "<p>Produk tidak ditemukan.</p>";
    exit();
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .form-label {
      font-weight: bold;
    }
    .form-control {
      border-radius: 10px;
    }
    h2 {
      color: #004085;
      margin-bottom: 1rem;
    }
    img.preview {
      max-width: 120px;
      border-radius: 10px;
      margin-top: 10px;
      border: 1px solid #ccc;
    }
    .container {
      max-width: 700px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card p-4">
    <h2><i class="fas fa-edit"></i> Edit Produk</h2>
    <form action="update_product.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $product['id'] ?>">

      <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" name="price" step="0.01" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Stok</label>
          <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Gambar Produk (kosongkan jika tidak ingin ganti)</label>
        <input type="file" name="image" accept="image/jpeg" class="form-control">
        <img src="../../image/<?= htmlspecialchars($product['image']) ?>" alt="Gambar" class="preview">
      </div>

      <div class="d-flex justify-content-between">
        <a href="product_list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
