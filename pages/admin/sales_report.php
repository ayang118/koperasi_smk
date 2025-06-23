<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}
include('../../config/db.php');

// Mengambil data laporan penjualan
$sql = "SELECT products.name AS product_name, SUM(cart.quantity) AS total_quantity, SUM(products.price * cart.quantity) AS total_sales
        FROM cart 
        INNER JOIN products ON cart.product_id = products.id
        GROUP BY cart.product_id";
$result = $conn->query($sql);

// Data untuk Chart
$product_names = [];
$total_sales = [];
while ($row = $result->fetch_assoc()) {
    $product_names[] = $row['product_name'];
    $total_quantity[] = $row['total_quantity'];
    $total_sales[] = $row['total_sales'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Admin</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .sales-report-container {
            max-width: 1200px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin-bottom: 30px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #dfe6ef;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            color: #333;
        }

        .chart-container {
            margin-top: 50px;
            background: #f9fbfd;
            padding: 20px;
            border-radius: 10px;
        }

        canvas {
            max-height: 400px;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="sales-report-container">
    <h2>Laporan Penjualan Produk</h2>
    <p>Berikut adalah laporan penjualan berdasarkan transaksi:</p>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Penjualan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($product_names); $i++): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product_names[$i]); ?></td>
                    <td><?php echo $total_quantity[$i]; ?></td>
                    <td>Rp <?php echo number_format($total_sales[$i], 0, ',', '.'); ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div class="chart-container">
        <canvas id="salesChart"></canvas>
    </div>

    <div style="text-align: center;">
        <a href="dashboard.php" class="back-button">Kembali ke Dashboard</a>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($product_names); ?>,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: <?php echo json_encode($total_sales); ?>,
                backgroundColor: 'rgba(0, 123, 255, 0.6)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1,
                borderRadius: 5,
                hoverBackgroundColor: 'rgba(0, 123, 255, 0.8)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

</body>
</html>
