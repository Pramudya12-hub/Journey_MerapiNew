<?php
session_start();
require "include/db.php"; // koneksi database
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour - JourneyMerapi</title>

    <!-- CSS -->
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/navbar.css">
    <link rel="stylesheet" href="Css/tour.css">
    <link rel="stylesheet" href="Css/modal.css">
    <link rel="stylesheet" href="Css/darkmode.css">
    <link rel="stylesheet" href="Css/animations.css">
</head>

<body>

<header>
    <nav class="navbar">
        <div class="logo"><span>Journey</span><b>Merapi</b></div>

        <ul class="menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="about.php">Tentang</a></li>
            <li><a href="tour.php" class="active">Tour</a></li>
            <li><a href="order.php">Order Now</a></li>
            <li><a href="contact.php">Kontak</a></li>
            <li><a href="status.php">Status Merapi</a></li>
        </ul>

        <div class="nav-btns">

            <?php if (!isset($_SESSION['user_id'])): ?>
                <button id="openLogin" class="btn login">Login</button>
            <?php else: ?>
                <span id="userName">Halo, <?= $_SESSION['username']; ?></span>
                <a href="auth/logout.php" class="btn logout">Logout</a>
            <?php endif; ?>

            <button id="darkModeToggle" class="btn dark">Dark Mode</button>
        </div>
    </nav>
</header>


<div class="main-tour-bg">
    <section class="hero-tour">
        <h2>Pilih <span>Destinasi Wisata</span> Terbaikmu</h2>
    </section>

    <section class="card-container-tour">

        <?php
        // ===============================
        // BACA DATA TOUR DARI DATABASE
        // ===============================

        $query = mysqli_query($conn, "SELECT * FROM tour ORDER BY ID_Tour ASC");

        if (mysqli_num_rows($query) === 0):
        ?>
            <p style="color:white; text-align:center; width:100%; margin-top:50px;">
                Belum ada destinasi wisata yang tersedia.
            </p>
        <?php
        endif;

        while($t = mysqli_fetch_assoc($query)):
        ?>

        <div class="card-tour">
            <img src="<?= $t['Gambar'] ?>" alt="<?= $t['Nama'] ?>">
            <h3><?= $t['Nama'] ?></h3>

            <p>Mulai Dari</p>
            <span class="price">IDR <?= number_format($t['Harga_mulai'], 0, ',', '.') ?> / Orang</span>

            <div class="info">
                <p>⭐ Rating: <?= $t['Rating'] ?>/5</p>
            </div>

            <!-- Kirim ID_Tour ke halaman order -->
            <a href="order.php?tour=<?= $t['ID_Tour'] ?>" class="btn pesan">Pesan</a>
        </div>

        <?php endwhile; ?>

    </section>
</div>


<!-- LOGIN / REGISTER MODAL -->
<?php include "include/modal.php"; ?>

<footer>
    © 2025 JourneyMerapi. All rights reserved.
</footer>

<script src="auth.js"></script>
</body>
</html>
