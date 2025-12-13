<?php
session_start();
require "../include/db.php";

// Cegah user non-admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - JourneyMerapi</title>

    <link rel="stylesheet" href="../Css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">Admin <span>Panel</span></h2>

        <ul class="menu">
            <li><a href="dashboard_admin.php" class="active">📊 Dashboard</a></li>
            <li><a href="kelola_status_merapi.php">🌋 Status Merapi</a></li>
            <li><a href="kelola_tour.php">🗺️ Tour</a></li>
            <li><a href="kelola_order.php">📝 Orders</a></li>
            <li><a href="kelola_contact.php">📩 Contact</a></li>
            <li><a href="kelola_user.php">👤 Users</a></li>
        </ul>

        <div class="logout-box">
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['username']); ?></b> 👋</p>

        <div class="cards">

            <div class="card">
                <h3>Status Merapi</h3>
                <p>Kelola status terbaru Merapi.</p>
                <a href="kelola_status_merapi.php" class="btn">Kelola</a>
            </div>

            <div class="card">
                <h3>Data Tour</h3>
                <p>Tambah / edit destinasi wisata.</p>
                <a href="kelola_tour.php" class="btn">Kelola</a>
            </div>

            <div class="card">
                <h3>Pemesanan</h3>
                <p>Daftar order wisata pengunjung.</p>
                <a href="kelola_order.php" class="btn">Kelola</a>
            </div>

            <div class="card">
                <h3>Pesan Masuk</h3>
                <p>Lihat pesan dari halaman kontak.</p>
                <a href="kelola_contact.php" class="btn">Kelola</a>
            </div>

            <div class="card">
                <h3>Users</h3>
                <p>Kelola akun pengguna & admin.</p>
                <a href="kelola_user.php" class="btn">Kelola</a>
            </div>

        </div>
    </main>

</div>

</body>
</html>
