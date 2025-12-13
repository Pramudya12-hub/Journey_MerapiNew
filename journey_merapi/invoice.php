<?php
session_start();
require "include/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak! Anda harus login terlebih dahulu.");
}

$ID_User = $_SESSION['user_id'];

// Pastikan ada order_id
if (!isset($_GET['id'])) {
    die("Order tidak ditemukan.");
}

$ID_Order = intval($_GET['id']);

// Ambil order utama
$order = mysqli_query($conn, "
    SELECT o.*, u.Username 
    FROM orders o
    LEFT JOIN users u ON o.ID_User = u.ID_User
    WHERE o.ID_Order = $ID_Order AND o.ID_User = $ID_User
");

if (mysqli_num_rows($order) === 0) {
    die("Order tidak ditemukan atau bukan milik Anda.");
}

$order = mysqli_fetch_assoc($order);

// Ambil destinasi dari order_items
$items = mysqli_query($conn, "SELECT * FROM order_items WHERE ID_Order = $ID_Order");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice Pemesanan - JourneyMerapi</title>

<link rel="stylesheet" href="Css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: Poppins, sans-serif;
    background: #f5f5f5;
    padding: 30px;
}
.invoice-box {
    max-width: 800px;
    margin: auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,.1);
}
h1 {
    text-align: center;
    margin-bottom: 10px;
}
h3 {
    margin-top: 30px;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.table th {
    text-align: left;
    background: #f0393d;
    color: #fff;
    padding: 10px;
}
.table td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}
.status-box {
    padding: 10px;
    margin-top: 20px;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
}
.pending { background: #ffe2b5; color: #b07a00; }
.process { background: #bde5ff; color: #004f80; }
.done { background: #c3f7c3; color: #0a7d0a; }

.btn-download {
    display: block;
    margin: 25px auto;
    padding: 12px 20px;
    background: #f0393d;
    color: white;
    font-weight: 600;
    border-radius: 10px;
    text-align: center;
    width: 230px;
}
.btn-download:hover {
    background: #d6282c;
}
.back-btn {
    display: block;
    text-align:center;
    margin-top:20px;
    color:#444;
}
</style>

</head>
<body>

<div class="invoice-box">

    <h1>Invoice Pemesanan</h1>
    <p style="text-align:center;">JourneyMerapi • Bukti Reservasi Tour Wisata</p>

    <hr>

    <h3>Informasi Pemesan</h3>
    <p><strong>Nama:</strong> <?= $order['Username'] ?></p>
    <p><strong>Pickup:</strong> <?= $order['pickup_date'] ?> — <?= $order['pickup_time'] ?></p>
    <p><strong>Jumlah Orang:</strong> <?= $order['Jumlah_orang'] ?></p>
    <p><strong>Lokasi Pickup:</strong> <?= $order['pickup_location'] ?></p>

    <h3>Detail Destinasi</h3>
    <table class="table">
        <tr>
            <th>Destinasi</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>

        <?php while ($i = mysqli_fetch_assoc($items)): ?>
        <tr>
            <td><?= $i['Tour_Name'] ?></td>
            <td>IDR <?= number_format($i['Price'],0,",",".") ?></td>
            <td><?= $i['Quantity'] ?></td>
            <td>IDR <?= number_format($i['Subtotal'],0,",",".") ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3>Total Pembayaran</h3>
    <p style="font-size:20px; font-weight:bold;">
        IDR <?= number_format($order['total_price'],0,",",".") ?>
    </p>

    <h3>Status Pemesanan</h3>
    <?php 
        $status = $order['Status'];
        $class = ($status == "Pending" ? "pending" : ($status == "Diproses" ? "process" : "done"));
    ?>
    <div class="status-box <?= $class ?>">
        <?= $status ?>
    </div>

    <br>

    <?php if ($status === "Selesai" || $status === "Diproses"): ?>
        <a href="invoice_pdf.php?id=<?= $ID_Order ?>" class="btn-download">📄 Download Invoice (PDF)</a>
    <?php else: ?>
        <p style="text-align:center; color:#b07a00;">
            *Menunggu konfirmasi admin untuk mengaktifkan cetak invoice.
        </p>
    <?php endif; ?>

    <a href="order.php" class="back-btn">Kembali ke halaman pemesanan</a>

</div>

</body>
</html>
