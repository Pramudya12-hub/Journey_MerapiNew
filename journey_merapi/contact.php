<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JourneyMerapi - Kontak Merapi</title>

    <link rel="stylesheet" href="Css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo-about"><span>Journey</span><b>Merapi</b></div>

        <ul class="menu-about">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="about.php">Tentang</a></li>
            <li><a href="tour.php">Tour</a></li>
            <li><a href="order.php">Order Now</a></li>
            <li><a href="contact.php" class="active">Kontak</a></li>
            <li><a href="status.php">Status Merapi</a></li>
        </ul>

        <div class="nav-btns">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span id="userName">Halo, <?= $_SESSION['username'] ?></span>
                <a href="auth/logout.php" class="btn logout">Logout</a>
            <?php else: ?>
                <button id="openLogin" class="btn login">Login</button>
            <?php endif; ?>

            <button id="darkModeToggle" class="btn dark">Dark Mode</button>
        </div>
    </nav>
</header>

<section class="kontak-section"> 
    <h1>Kontak <span>Kami:</span></h1>
    <p>
        Jika Anda ingin bertanya seputar informasi Gunung Merapi atau ingin memberikan kritik dan saran, 
        silakan hubungi kami melalui formulir di bawah ini.
    </p>

    <div class="contact-card">
        <form class="contact-form" id="contactForm">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="contactEmail" name="email" required>

            <label for="message">Pesan:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Kirim</button>
        </form>
    </div>
</section>

<?php include "include/modal.php"; ?>

<footer class="footer-merapi">
    <div class="footer-container">
        <div class="footer-column">
            <h3>JourneyMerapi</h3>
            <p>
                Media informasi dan wisata Gunung Merapi.<br>
                Menyajikan edukasi, sejarah, pesona alam dan pemesanan tiket wisata Merapi.
            </p>
        </div>

        <div class="footer-column">
            <h3>Kontak</h3>
            <p><a href="mailto:jrnymerapi.id@gmail.com">jrnymerapi.id@gmail.com</a></p>
            <p>+62 888 0490 1667</p>
            <p class="copyright">&copy; 2025 JourneyMerapi. All rights reserved.</p>
        </div>

        <div class="footer-column">
            <h3>Alamat</h3>
            <p>Dusun 2, Suroteleng, Kec. Selo, Kab. Boyolali, Jawa Tengah</p>
        </div>
    </div>
</footer>

<script src="auth.js"></script>

<script>
// =============== HANDLE CONTACT FORM SUBMIT ===============
document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("contactEmail").value;
    const message = document.getElementById("message").value;

    fetch("contact_process.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            name: name,
            email: email,
            message: message
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.getElementById("contactForm").reset();
        }
    })
    .catch(err => console.error(err));
});
</script>

</body>
</html>
