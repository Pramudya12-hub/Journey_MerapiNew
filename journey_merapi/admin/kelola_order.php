<?php
session_start();
require "../include/db.php";

// Cek akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// DELETE ORDER
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM order_items WHERE ID_Order = $id");
    mysqli_query($conn, "DELETE FROM orders WHERE ID_Order = $id");

    header("Location: kelola_order.php");
    exit;
}

// UPDATE STATUS
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id     = intval($_POST["order_id"]);
    $status = mysqli_real_escape_string($conn, $_POST["status"]);

    mysqli_query($conn, "UPDATE orders SET Status='$status' WHERE ID_Order=$id");
    header("Location: kelola_order.php");
    exit;
}

// GET ALL ORDERS (latest first)
$query = "
    SELECT o.*, u.Username
    FROM orders o
    LEFT JOIN users u ON o.ID_User = u.ID_User
    ORDER BY o.Created_at DESC
";
$data = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Pemesanan - Admin JourneyMerapi</title>

<link rel="stylesheet" href="../Css/admin.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* SIDEBAR */
.sidebar {
    position: fixed;
    width: 260px;
    height: 100%;
    background: #111;
    color: white;
    padding: 22px;
}
.sidebar h2 { color:#f0393d; margin-bottom:25px; }
.sidebar ul { list-style:none; padding:0; }
.sidebar ul li { margin-bottom:18px; }
.sidebar ul li a {
    color:#ddd; text-decoration:none;
    transition:.25s;
}
.sidebar ul li a:hover, .sidebar ul li a.active {
    color:#f0393d; font-weight:600;
}
.logout-btn {
    display:block; margin-top:20px;
    padding:10px 15px;
    background:#f0393d; border-radius:8px;
    text-align:center; color:white;
}

/* CONTENT */
.content {
    margin-left:280px;
    padding:40px 60px;
    font-family:Poppins, sans-serif;
}

table {
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    margin-top:20px;
    box-shadow:0 6px 20px rgba(0,0,0,.1);
}
th, td {
    padding:14px;
    border-bottom:1px solid #eee;
}
th {
    background:#f0393d; color:white; font-size:14px;
}

/* ACTION BUTTONS */
.btn-detail {
    background:#2ecc71; color:white; padding:7px 12px;
    border-radius:6px; text-decoration:none; cursor:pointer;
}
.btn-detail:hover { background:#27ae60; }

.btn-status {
    background:#3498db; color:white; padding:7px 12px;
    border-radius:6px; text-decoration:none; cursor:pointer;
}
.btn-status:hover { background:#2c80b4; }

.btn-delete {
    background:#e74c3c; color:white; padding:7px 12px;
    border-radius:6px; text-decoration:none; cursor:pointer;
}
.btn-delete:hover { background:#c0392b; }

/* MODAL */
.modal {
    position:fixed; top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,.5);
    display:none; justify-content:center; align-items:center;
}
.modal.active { display:flex; }

.modal-content {
    width:500px; background:white;
    padding:25px; border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
    animation:pop .25s ease;
}
@keyframes pop {
    from { transform:scale(.85); opacity:0; }
    to   { transform:scale(1); opacity:1; }
}

.close-btn {
    background:#e74c3c; color:white;
    padding:10px; border:none;
    border-radius:6px;
    cursor:pointer; width:100%; 
}
.close-btn:hover { background:#c0392b; }
</style>

</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h2>Admin Panel</h2>

    <ul>
        <li><a href="dashboard_admin.php">Dashboard</a></li>
        <li><a href="kelola_status_merapi.php">Status Merapi</a></li>
        <li><a href="kelola_tour.php">Tour</a></li>
        <li><a class="active" href="kelola_order.php">Orders</a></li>
        <li><a href="kelola_contact.php">Contact</a></li>
        <li><a href="kelola_user.php">Users</a></li>
    </ul>

    <a href="../auth/logout.php" class="logout-btn">Logout</a>
</aside>


<!-- MAIN CONTENT -->
<main class="content">

<h1>Kelola Pemesanan</h1>
<p>Daftar seluruh pemesanan wisata oleh pengguna.</p>

<table>
<tr>
    <th>ID</th>
    <th>User Login</th>
    <th>Nama Lengkap</th>
    <th>Email</th>
    <th>HP</th>
    <th>Negara</th>
    <th>Jumlah Orang</th>
    <th>Total Harga</th>
    <th>Pickup</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($data)): ?>
<tr>
    <td><?= $row['ID_Order'] ?></td>
    <td><?= $row['Username'] ?: "-" ?></td>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td><?= $row['country'] ?></td>
    <td><?= $row['Jumlah_orang'] ?></td>
    <td>IDR <?= number_format($row['total_price'],0,",",".") ?></td>
    <td><?= $row['pickup_date'] ?> (<?= $row['pickup_time'] ?>)</td>
    <td><b><?= $row['Status'] ?></b></td>

    <td>
        <button class="btn-detail" onclick="showDetail(<?= $row['ID_Order'] ?>)">Detail</button>

        <button class="btn-status" onclick='openStatus(<?= json_encode($row) ?>)'>Status</button>

        <a class="btn-delete" href="?delete=<?= $row['ID_Order'] ?>"
           onclick="return confirm('Hapus pemesanan ini?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

</main>


<!-- MODAL DETAIL -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <h3>Detail Destinasi</h3>
        <div id="detailBody">Memuat...</div>
        <button class="close-btn" onclick="closeDetail()">Tutup</button>
    </div>
</div>

<!-- MODAL STATUS -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <h3>Ubah Status Pemesanan</h3>

        <form method="POST">
            <input type="hidden" name="order_id" id="orderID">

            <label>Status Baru:</label>
            <select name="status" id="statusInput">
                <option value="Pending">Pending</option>
                <option value="Diproses">Diproses</option>
                <option value="Selesai">Selesai</option>
            </select>

            <button class="close-btn" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<script>
// ===== DETAIL ORDER (AJAX) =====
function showDetail(id) {
    const modal = document.getElementById("detailModal");
    const body  = document.getElementById("detailBody");

    modal.classList.add("active");
    body.innerHTML = "Memuat...";

    fetch("order_items_api.php?id=" + id)
        .then(r => r.text())
        .then(html => body.innerHTML = html)
        .catch(() => body.innerHTML = "Gagal memuat data!");
}

function closeDetail() {
    document.getElementById("detailModal").classList.remove("active");
}

// ===== STATUS ORDER =====
function openStatus(data) {
    document.getElementById("orderID").value = data.ID_Order;
    document.getElementById("statusInput").value = data.Status;
    document.getElementById("statusModal").classList.add("active");
}

window.onclick = function(e) {
    if (e.target.classList.contains("modal")) {
        e.target.classList.remove("active");
    }
};
</script>

</body>
</html>
