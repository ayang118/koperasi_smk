<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_koperasismk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['last_order_id'])) {
    header('Location: checkout.php');
    exit;
}

$orderId = $_SESSION['last_order_id'];

$stmt = $conn->prepare("SELECT buyer_name, class_name, buyer_email, payment_method, transfer_method, payment_proof, order_date FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$stmt->bind_result($buyerName, $className, $buyerEmail, $paymentMethod, $transferMethod, $paymentProof, $orderDate);
if (!$stmt->fetch()) {
    echo "Order tidak ditemukan.";
    exit;
}
$stmt->close();

$stmtItems = $conn->prepare("SELECT product_name, product_price FROM order_items WHERE order_id = ?");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$resultItems = $stmtItems->get_result();
$orderItems = $resultItems->fetch_all(MYSQLI_ASSOC);
$stmtItems->close();

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 2, ',', '.');
}

function formatTanggalIndonesia($tanggal) {
    $bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    $dt = new DateTime($tanggal);
    $tgl = $dt->format('d');
    $bln = $bulan[$dt->format('m')];
    $thn = $dt->format('Y');
    $jam = $dt->format('H:i');

    return "$tgl $bln $thn $jam WIB";
}

$totalPrice = 0;
foreach ($orderItems as $item) {
    $totalPrice += $item['product_price'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Struk Pembayaran - Koperasi Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #007bff, #0056b3);
      font-family: 'Segoe UI', sans-serif;
      color: #333;
      min-height: 100vh;
      padding-top: 60px;
    }

    body::before {
      content: "";
      background: url('assets/logo-maskot.png') no-repeat center center;
      background-size: 200px;
      opacity: 0.05;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .container {
      max-width: 700px;
      margin: 100px auto 30px;
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    h2 {
      color: #0056b3;
      text-align: center;
      margin-bottom: 30px;
    }

    .order-info p {
      margin: 0 0 8px;
    }

    .order-items {
      margin-top: 20px;
    }

    .order-items h4::before {
      content: "🛍️ ";
    }

    .order-items table {
      width: 100%;
    }

    .order-items th, .order-items td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    .total-price {
      text-align: right;
      font-weight: bold;
      font-size: 1.3rem;
      margin-top: 15px;
      color: #0056b3;
    }

    .thank-you {
      text-align: center;
      margin-top: 30px;
      font-size: 1.2rem;
      color: #007bff;
    }

    .thank-you-lucu {
      font-family: 'Comic Sans MS', cursive;
      color: #ff6600;
      font-size: 1.1rem;
    }

    .note {
      margin-top: 15px;
      background-color: #d9edf7;
      padding: 15px;
      border-radius: 10px;
      color: #31708f;
      font-style: italic;
    }

    .btn-back-small {
      margin-top: 30px;
      padding: 6px 20px;
      font-size: 0.9rem;
      border-radius: 8px;
    }

    .payment-proof {
      margin-top: 10px;
    }

    @media print {
      .btn-back-small,
      .d-print-none {
        display: none !important;
      }

      body {
        background: white !important;
      }

      .container {
        box-shadow: none !important;
        margin: 0 !important;
        border: none !important;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2><i class="fas fa-receipt"></i> Struk Pembayaran</h2>

  <div class="order-info">
    <p><strong>👤 Nama Pembeli:</strong> <?= htmlspecialchars($buyerName) ?></p>
    <p><strong>🏫 Kelas:</strong> <?= htmlspecialchars($className) ?></p>
    <p><strong>📧 Email:</strong> <?= htmlspecialchars($buyerEmail) ?></p>
    <p><strong>💳 Metode Pembayaran:</strong> <?= htmlspecialchars($paymentMethod) ?></p>
    <?php if ($paymentMethod === 'Transfer Bank'): ?>
      <p><strong>🏦 Metode Transfer:</strong> <?= htmlspecialchars($transferMethod) ?></p>
      <?php if ($paymentProof): ?>
        <p class="payment-proof"><strong>📎 Bukti Pembayaran:</strong><br />
          <?php 
            $ext = pathinfo($paymentProof, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg','jpeg','png'])): ?>
              <img src="<?= htmlspecialchars($paymentProof) ?>" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 300px; border-radius: 10px;">
          <?php elseif (strtolower($ext) === 'pdf'): ?>
              <a href="<?= htmlspecialchars($paymentProof) ?>" target="_blank">Lihat Bukti Pembayaran (PDF)</a>
          <?php endif; ?>
        </p>
      <?php endif; ?>
    <?php endif; ?>
    <p><strong>📅 Tanggal Pembelian:</strong> <?= formatTanggalIndonesia($orderDate) ?></p>
  </div>

  <div class="order-items">
    <h4>Daftar Produk</h4>
    <table>
      <thead>
        <tr>
          <th>🎁 Nama Produk</th>
          <th style="text-align: right;">💰 Harga</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orderItems as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td style="text-align: right;"><?= formatRupiah($item['product_price']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="total-price">Total Bayar: <?= formatRupiah($totalPrice) ?></div>
  </div>

  <div class="thank-you">
    <p>🙏 Terima kasih atas pembelian Anda!</p>
    <div class="thank-you-lucu">😊 Belanja hemat, hati senang! Sampai jumpa di koperasi! 🎉</div>
  </div>

  <div class="note">
    Produk dapat langsung diambil di koperasi sekolah. Silakan hubungi petugas koperasi untuk pengambilan produk Anda.
  </div>

  <div class="text-center d-print-none">
    <button onclick="window.location.href='dashboard.php'" class="btn btn-primary btn-sm btn-back-small">
      <i class="fas fa-home"></i> Kembali ke Beranda
    </button>
    <div class="mt-3">
      <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-print"></i> Cetak Struk
      </button>
    </div>
  </div>
</div>

<script>
  localStorage.removeItem('cart');
</script>

</body>
</html>
 