<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_koperasismk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 2, ',', '.');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyerName = trim($_POST['buyer_name'] ?? '');
    $className = trim($_POST['class_name'] ?? '');
    $buyerEmail = trim($_POST['buyer_email'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? '';
    $transferMethod = $_POST['transfer_method'] ?? null;
    $cart = json_decode($_POST['cart'] ?? '[]', true);

    if (!$buyerName || !$className || !$buyerEmail || !$paymentMethod) {
        $errors[] = "Mohon isi semua data dengan lengkap.";
    }

    if ($paymentMethod === 'Transfer Bank') {
        if (!$transferMethod) $errors[] = "Mohon pilih metode transfer.";
        if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Mohon upload bukti transaksi.";
        } else {
            $allowed = ['image/jpeg', 'image/png', 'application/pdf'];
            $type = $_FILES['payment_proof']['type'];
            if (!in_array($type, $allowed)) $errors[] = "File harus JPG, PNG, atau PDF.";
            if ($_FILES['payment_proof']['size'] > 2 * 1024 * 1024) $errors[] = "Ukuran file maksimal 2MB.";
        }
    }

    if (empty($cart)) $errors[] = "Keranjang masih kosong.";

    if (empty($errors)) {
        $paymentProofPath = null;
        if ($paymentMethod === 'Transfer Bank') {
            $uploadDir = 'uploads/payment_proofs/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
            $fileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $filePath)) {
                $paymentProofPath = $filePath;
            } else {
                $errors[] = "Gagal mengunggah bukti pembayaran.";
            }
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO orders (buyer_name, class_name, buyer_email, payment_method, transfer_method, payment_proof) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $buyerName, $className, $buyerEmail, $paymentMethod, $transferMethod, $paymentProofPath);
            if ($stmt->execute()) {
                $orderId = $stmt->insert_id;
                $stmt->close();

                $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, product_price) VALUES (?, ?, ?)");
                foreach ($cart as $item) {
                    $stmtItem->bind_param("isd", $orderId, $item['name'], $item['price']);
                    $stmtItem->execute();
                }
                $stmtItem->close();

                $_SESSION['last_order_id'] = $orderId;
                header("Location: payment_summary.php");
                exit;
            } else {
                $errors[] = "Gagal menyimpan data pesanan.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Koperasi Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #007bff, #0056b3);
      font-family: 'Segoe UI', sans-serif;
      padding-top: 60px;
      min-height: 100vh;
    }
    .container {
      max-width: 700px;
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      margin: 60px auto;
    }
    h2 {
      text-align: center;
      color: #0056b3;
      margin-bottom: 30px;
    }
    .cart-item {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 12px 0;
    }
    .cart-item img {
      width: 70px;
      height: 70px;
      object-fit: contain;
      margin-right: 15px;
    }
    .cart-item-details h5 {
      margin: 0;
      color: #007bff;
    }
    .total-price {
      text-align: right;
      font-weight: bold;
      font-size: 1.3rem;
      color: #0056b3;
      margin-top: 20px;
    }
    .btn-pay {
      background-color: #007bff;
      color: white;
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      margin-top: 30px;
      font-size: 1.1rem;
    }
    .btn-pay:hover {
      background-color: #0056b3;
    }
    .transfer-info {
      background: #f8f9fa;
      border-left: 4px solid #007bff;
      padding: 10px 15px;
      margin-top: 10px;
      border-radius: 8px;
      font-size: 0.95rem;
    }
    .transfer-info strong {
      display: inline-block;
      width: 90px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2><i class="fas fa-credit-card"></i> Checkout</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div id="checkout-items"></div>
  <div class="total-price">Total: <span id="total-price">Rp 0</span></div>

  <form method="POST" enctype="multipart/form-data" id="payment-form">
    <div class="mb-3">
      <label for="buyer-name" class="form-label">Nama Pembeli</label>
      <input type="text" name="buyer_name" class="form-control" required value="<?= htmlspecialchars($_POST['buyer_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label for="class-name" class="form-label">Kelas</label>
      <input type="text" name="class_name" class="form-control" required value="<?= htmlspecialchars($_POST['class_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label for="buyer-email" class="form-label">Email</label>
      <input type="email" name="buyer_email" class="form-control" required value="<?= htmlspecialchars($_POST['buyer_email'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Metode Pembayaran</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="payment_method" id="tunai" value="Tunai" required>
        <label class="form-check-label" for="tunai">Tunai</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="Transfer Bank" required>
        <label class="form-check-label" for="transfer">Transfer Bank</label>
      </div>
    </div>

    <div id="transfer-options" style="display: none;">
      <div class="mb-2">
        <label class="form-label">Pilih Transfer</label><br>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="transfer_method" value="Dana" id="tf-dana">
          <label class="form-check-label" for="tf-dana">Dana</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="transfer_method" value="Ovo" id="tf-ovo">
          <label class="form-check-label" for="tf-ovo">Ovo</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="transfer_method" value="Bank BSI" id="tf-bsi">
          <label class="form-check-label" for="tf-bsi">Bank BSI</label>
        </div>
      </div>

      <div class="transfer-info">
        <p><strong>Dana/Ovo:</strong> 0821 1234 5672<br><strong>Atas Nama:</strong> SMK Muhammadiyah Kota Bogor</p>
        <p><strong>BSI:</strong> 8889 9099 7<br><strong>Atas Nama:</strong> SMK Muhammadiyah Kota Bogor</p>
      </div>

      <div class="mb-3 mt-3">
        <label for="payment-proof" class="form-label">Upload Bukti Transfer</label>
        <input type="file" name="payment_proof" id="payment-proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
      </div>
    </div>

    <input type="hidden" name="cart" id="cart-input" value="[]">
    <button type="submit" class="btn-pay"><i class="fas fa-check-circle"></i> Bayar Sekarang</button>
  </form>
</div>

<script>
  const cart = JSON.parse(localStorage.getItem('cart')) || [];

  function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID', {minimumFractionDigits: 2});
  }

  function loadCart() {
    const container = document.getElementById('checkout-items');
    const totalPrice = document.getElementById('total-price');
    const inputCart = document.getElementById('cart-input');

    container.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
      container.innerHTML = '<p class="text-center text-muted">Keranjang kosong.</p>';
      document.getElementById('payment-form').style.display = 'none';
      return;
    }

    cart.forEach(item => {
      total += parseFloat(item.price);
      container.innerHTML += `
        <div class="cart-item">
          <img src="../../image/${item.image}" alt="${item.name}">
          <div class="cart-item-details">
            <h5>${item.name}</h5>
            <p>Harga: ${formatRupiah(item.price)}</p>
          </div>
        </div>`;
    });

    totalPrice.textContent = formatRupiah(total);
    inputCart.value = JSON.stringify(cart);
  }

  function toggleTransfer() {
    const isTransfer = document.getElementById('transfer').checked;
    document.getElementById('transfer-options').style.display = isTransfer ? 'block' : 'none';
  }

  document.querySelectorAll('input[name="payment_method"]').forEach(input => {
    input.addEventListener('change', toggleTransfer);
  });

  window.onload = () => {
    loadCart();
    toggleTransfer();
  };
</script>

</body>
</html>
