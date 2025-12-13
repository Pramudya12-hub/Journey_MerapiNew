<?php
session_start();
require "include/db.php";

// Ambil semua tour dari database
$tourQuery = mysqli_query($conn, "SELECT * FROM tour ORDER BY ID_Tour ASC");
$tours = [];
while ($row = mysqli_fetch_assoc($tourQuery)) {
    $tours[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JourneyMerapi - Pemesanan Tiket Tour</title>

    <link rel="stylesheet" href="Css/style.css" />
    <link rel="stylesheet" href="Css/darkmode.css" />
    <link rel="stylesheet" href="Css/navbar.css" />
    <link rel="stylesheet" href="Css/animations.css" />
    <link rel="stylesheet" href="Css/modal.css" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Tombol My Orders (hanya di halaman order) */
        .order-top-btn {
            display: flex;
            justify-content: flex-end;
            margin: 10px 10px 25px 10px;
        }
        .btn-myorders {
            background: #db0000ff;
            color: #fff !important;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-myorders:hover {
            background: #ffffffff;
            transform: translateY(-3px);
            color: #db0000ff !important;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body class="fade-in">

<!-- ================= NAVBAR ================= -->
<header>
    <nav class="navbar">
        <div class="logo-about"><span>Journey</span><b>Merapi</b></div>

        <ul class="menu-about">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="about.php">Tentang</a></li>
            <li><a href="tour.php">Tour</a></li>
            <li><a href="order.php" class="active">Order Now</a></li>
            <li><a href="contact.php">Kontak</a></li>
            <li><a href="status.php">Status Merapi</a></li>
        </ul>

        <div class="nav-btns">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <button id="openLogin" class="btn login">Login</button>
            <?php else: ?>
                <span style="margin-right:10px;">Halo, <?= $_SESSION['username'] ?></span>
                <a href="auth/logout.php" class="btn logout">Logout</a>
            <?php endif; ?>

            <button id="darkModeToggle" class="btn dark">Dark Mode</button>
        </div>
    </nav>
</header>

<!-- ================= FORM ORDER ================= -->
<section class="order-section">
    <h1>Pesan Tiket Tour <span>Online Sekarang Juga!!</span></h1>
    <p>Isi form dibawah untuk memesan tiket online Tour Wisata Merapi</p>

    <!-- ========== TOMBOL MY ORDERS DI SINI ========== -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="order-top-btn">
        <a href="my_order.php" class="btn-myorders">📦 Riwayat Pesanan Saya</a>
    </div>
    <?php endif; ?>
    <!-- =============================================== -->

    <div class="container">

        <!-- PILIH DESTINASI -->
        <div class="form-section">
            <div class="section-title">Pilih Destinasi (Bisa memilih lebih dari satu):</div>

            <?php foreach ($tours as $t): ?>
                <div class="tour-option">
                    <input type="checkbox"
                        name="tour"
                        id="tour-<?= $t['ID_Tour'] ?>"
                        value="<?= $t['Nama'] ?>"
                        data-id="<?= $t['ID_Tour'] ?>"
                        data-price="<?= $t['Harga_mulai'] ?>">

                    <label for="tour-<?= $t['ID_Tour'] ?>"><?= $t['Nama'] ?></label>
                    <span class="tour-price">IDR <?= number_format($t['Harga_mulai'], 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>

            <div class="selected-tours" id="selected-tours-info">
                <h3>Destinasi yang Dipilih:</h3>

                <div id="selected-tours-list">
                    <p class="no-selection">Belum ada destinasi yang dipilih</p>
                </div>

                <div class="total-price">
                    <span>Total:</span>
                    <span id="total-price">IDR 0</span>
                </div>
            </div>
        </div>

        <hr>

        <!-- PICKUP -->
        <div class="form-section">
            <div class="section-title">Pickup Schedule:</div>
            <div class="form-row">
                <div class="form-group">
                    <label>Date Pickup:</label>
                    <input type="date" id="pickup-date" required>
                </div>

                <div class="form-group">
                    <label>Time Pickup:</label>
                    <select id="pickup-time" required>
                        <option value="">Pilih waktu pickup</option>
                        <option>08:00</option>
                        <option>09:00</option>
                        <option>10:00</option>
                        <option>11:00</option>
                        <option>13:00</option>
                        <option>14:00</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- PERSONAL INFO -->
        <div class="form-section">
            <div class="section-title">Personal Info</div>

            <div class="form-row">
                <input type="number" id="jumlah-orang" min="1" placeholder="Jumlah Orang" required>
            </div>

            <div class="form-row">
                <input type="text" id="full-name" placeholder="Full Name" required>
                <input type="text" id="country" placeholder="Country" required>
            </div>

            <div class="form-row">
                <input type="email" id="email" placeholder="Email" required>
                <input type="tel" id="phone" placeholder="Phone" required>
            </div>
        </div>

        <!-- SPECIAL REQUEST -->
        <div class="form-section">
            <div class="section-title">Special Request</div>
            <textarea id="special-request" placeholder="Masukkan detail tambahan..."></textarea>
        </div>

        <!-- FORM HIDDEN -->
        <form id="orderForm" method="POST" action="order_process.php">
            <input type="hidden" name="tours" id="toursInput">
            <input type="hidden" name="total_price" id="totalPriceInput">
            <input type="hidden" name="pickup_date" id="pickupDateInput">
            <input type="hidden" name="pickup_time" id="pickupTimeInput">
            <input type="hidden" name="jumlah_orang" id="jumlahOrangInput">
            <input type="hidden" name="full_name" id="fullNameInput">
            <input type="hidden" name="country" id="countryInput">
            <input type="hidden" name="email" id="emailInput">
            <input type="hidden" name="phone" id="phoneInput">
            <input type="hidden" name="special_request" id="specialRequestInput">
        </form>

        <button class="btn-reserve" id="reserve-btn">Reservasi Sekarang</button>

    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© 2025 JourneyMerapi. All rights reserved.</p>
</footer>

<?php include "include/modal.php"; ?>
<script src="auth.js"></script>

<script>
// Format Rupiah
function rupiah(v){ return "IDR " + v.toLocaleString("id-ID"); }

// Update pilihan destinasi
function updateSelected() {
    const list = document.getElementById("selected-tours-list");
    const totalEl = document.getElementById("total-price");
    const selected = [...document.querySelectorAll("input[name='tour']:checked")];

    list.innerHTML = "";
    let total = 0;

    if (selected.length === 0) {
        list.innerHTML = `<p class='no-selection'>Belum ada destinasi yang dipilih</p>`;
    } else {
        selected.forEach(c => {
            const price = parseInt(c.dataset.price);
            total += price;

            list.innerHTML += `
                <div class="selected-tour-item">
                    <span>${c.value}</span>
                    <span>${rupiah(price)}</span>
                </div>`;
        });
    }

    totalEl.innerText = rupiah(total);
}

document.querySelectorAll("input[name='tour']").forEach(c =>
    c.addEventListener("change", updateSelected)
);

// Submit Order
document.getElementById("reserve-btn").addEventListener("click", function () {

    // Harus login
    <?php if (!isset($_SESSION['user_id'])): ?>
        showLoginRequired("Silakan login terlebih dahulu.");
        return;
    <?php endif; ?>

    const selected = [...document.querySelectorAll("input[name='tour']:checked")];
    if (selected.length === 0) { alert("Pilih minimal 1 destinasi!"); return; }

    const jumlah = document.getElementById("jumlah-orang").value;
    const date = document.getElementById("pickup-date").value;
    const time = document.getElementById("pickup-time").value;

    if (!jumlah || !date || !time) {
        alert("Lengkapi semua data!");
        return;
    }

    let toursData = selected.map(c => ({
        id: c.dataset.id,
        name: c.value,
        price: parseInt(c.dataset.price),
        quantity: jumlah,
        subtotal: parseInt(c.dataset.price) * jumlah
    }));

    document.getElementById("toursInput").value = JSON.stringify(toursData);
    document.getElementById("totalPriceInput").value = toursData.reduce((a,b)=>a+b.subtotal,0);
    document.getElementById("pickupDateInput").value = date;
    document.getElementById("pickupTimeInput").value = time;
    document.getElementById("jumlahOrangInput").value = jumlah;
    document.getElementById("fullNameInput").value = document.getElementById("full-name").value;
    document.getElementById("countryInput").value = document.getElementById("country").value;
    document.getElementById("emailInput").value = document.getElementById("email").value;
    document.getElementById("phoneInput").value = document.getElementById("phone").value;
    document.getElementById("specialRequestInput").value = document.getElementById("special-request").value;

    document.getElementById("orderForm").submit();
});
</script>

</body>
</html>
