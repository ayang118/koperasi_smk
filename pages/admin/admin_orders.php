<?php
include('../../config/db.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Data Pembelian - Admin Koperasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h2>Data Pembelian</h2>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID Order</th>
        <th>Nama Pembeli</th>
        <th>Email</th>
        <th>Kelas</th>
        <th>Metode Pembayaran</th>
        <th>Tanggal</th>
        <th>Detail</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = "SELECT * FROM orders ORDER BY order_date DESC";
      $result = $conn->query($query);
      while ($order = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['buyer_name']) ?></td>
        <td><?= htmlspecialchars($order['buyer_email']) ?></td>
        <td><?= htmlspecialchars($order['class_name']) ?></td>
        <td><?= htmlspecialchars($order['payment_method']) ?></td>
        <td><?= $order['order_date'] ?></td>
        <td><a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Lihat</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
