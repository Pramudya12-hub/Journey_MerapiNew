<?php
session_start();
require "../include/db.php";

// Cek akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// DELETE TOUR
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM tour WHERE ID_Tour = $id");
    header("Location: kelola_tour.php");
    exit;
}

// TAMBAH / EDIT TOUR
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id         = $_POST["id"] ?? null;
    $nama       = mysqli_real_escape_string($conn, $_POST["nama"]);
    $harga      = mysqli_real_escape_string($conn, $_POST["harga"]);
    $rating     = mysqli_real_escape_string($conn, $_POST["rating"]);
    $gambar     = mysqli_real_escape_string($conn, $_POST["gambar"]);
    $deskripsi  = mysqli_real_escape_string($conn, $_POST["deskripsi"]);

    if (empty($id)) {
        mysqli_query($conn,
            "INSERT INTO tour (Nama, Harga_mulai, Rating, Gambar, Deskripsi)
             VALUES ('$nama', '$harga', '$rating', '$gambar', '$deskripsi')"
        );
    } else {
        mysqli_query($conn,
            "UPDATE tour SET 
                Nama='$nama',
                Harga_mulai='$harga',
                Rating='$rating',
                Gambar='$gambar',
                Deskripsi='$deskripsi'
             WHERE ID_Tour=$id"
        );
    }

    header("Location: kelola_tour.php");
    exit;
}

// Ambil data tour
$data = mysqli_query($conn, "SELECT * FROM tour ORDER BY ID_Tour ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tour - Admin JourneyMerapi</title>

    <link rel="stylesheet" href="../Css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ---------------- SIDEBAR ---------------- */
.sidebar {
    position: fixed;
    width: 260px;
    height: 100%;
    background: #111;
    color: #fff;
    padding: 22px;
}
.sidebar h2 { color:#f0393d; margin-bottom:22px; }
.sidebar ul { list-style:none; padding:0; }
.sidebar ul li { margin-bottom:18px; }
.sidebar ul li a {
    color:#ddd;
    text-decoration:none;
    transition:.25s;
}
.sidebar ul li a:hover,
.sidebar ul li a.active { color:#f0393d; font-weight:600; }

.logout-btn {
    display:block; background:#f0393d; padding:10px 14px;
    text-align:center; border-radius:8px; color:#fff; margin-top:25px;
}

/* ---------------- CONTENT ---------------- */
.content {
    margin-left:280px;
    padding:40px 60px;
    font-family:Poppins, sans-serif;
}

h1 { font-size:32px; margin-bottom:10px; }

/* Buttons */
.btn-add, .btn-back {
    display:inline-block;
    padding:12px 20px;
    background:#f0393d;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    margin-right:10px;
    margin-bottom:20px;
    transition:.25s;
}
.btn-add:hover, .btn-back:hover {
    background:#d6282c;
    transform:translateY(-3px);
}

/* TABLE */
table {
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 6px 20px rgba(0,0,0,.1);
}
th, td {
    padding:14px;
    border-bottom:1px solid #eee;
}
th {
    background:#f0393d;
    color:#fff;
}
td img {
    width:110px;
    height:75px;
    object-fit:cover;
    border-radius:10px;
}

/* ACTION BUTTONS */
.btn-edit {
    background:#3498db; color:#fff; padding:7px 14px;
    border-radius:6px; text-decoration:none;
}
.btn-edit:hover { background:#2779b6; }

.btn-delete {
    background:#e74c3c; color:#fff; padding:7px 14px;
    border-radius:6px; text-decoration:none;
}
.btn-delete:hover { background:#c0392b; }

/* ---------------- MODAL ---------------- */
.modal {
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,.45);
    display:none;
    justify-content:center;
    align-items:center;
}
.modal.active { display:flex; animation:fade .25s; }
@keyframes fade { from{opacity:0;} to{opacity:1;} }

.modal-content {
    background:#fff;
    padding:25px;
    width:480px;
    border-radius:15px;
    animation:pop .25s;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
}
@keyframes pop { from{transform:scale(.85);} to{transform:scale(1);} }

.modal-content input,
.modal-content textarea {
    width:100%;
    padding:12px;
    margin-bottom:14px;
    border:1px solid #ccc;
    border-radius:8px;
}

textarea { height:110px; resize:none; }

.btn-save {
    width:100%; padding:12px;
    background:#f0393d; color:#fff; border:none;
    border-radius:10px; cursor:pointer; font-size:16px;
}
.btn-save:hover { background:#d6282c; }

</style>

</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h2>Admin Panel</h2>

    <ul>
        <li><a href="dashboard_admin.php">Dashboard</a></li>
        <li><a href="kelola_status_merapi.php">Status Merapi</a></li>
        <li><a class="active" href="kelola_tour.php">Tour</a></li>
        <li><a href="kelola_order.php">Orders</a></li>
        <li><a href="kelola_contact.php">Contact</a></li>
        <li><a href="kelola_user.php">Users</a></li>
    </ul>

    <a href="../auth/logout.php" class="logout-btn">Logout</a>
</aside>

<!-- MAIN CONTENT -->
<main class="content">

    <h1>Kelola Tour</h1>

    <a href="dashboard_admin.php" class="btn-back">Kembali</a>
    <a class="btn-add" onclick="openModal()">Tambah Tour</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Harga Mulai</th>
            <th>Rating</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($data)): ?>

        <?php 
            $gambar = $row['Gambar'];

            if (preg_match('/^https?:\/\//', $gambar)) {
                $imgSrc = $gambar;
            } else {
                $imgSrc = "/journey_merapi/" . $gambar;
                $localPath = $_SERVER['DOCUMENT_ROOT'] . "/journey_merapi/" . $gambar;

                if (!file_exists($localPath)) {
                    $imgSrc = "/journey_merapi/img/no-image.png";
                }
            }
        ?>

        <tr>
            <td><?= $row['ID_Tour'] ?></td>
            <td><img src="<?= $imgSrc ?>"></td>
            <td><?= $row['Nama'] ?></td>
            <td>IDR <?= number_format($row['Harga_mulai'],0,",",".") ?></td>
            <td><?= $row['Rating'] ?></td>

            <td>
                <a class="btn-edit" onclick='editTour(<?= json_encode($row) ?>)'>Edit</a>
                <a class="btn-delete" onclick="return confirm('Hapus tour ini?')" 
                   href="?delete=<?= $row['ID_Tour'] ?>">Delete</a>
            </td>
        </tr>

        <?php endwhile; ?>
    </table>

</main>


<!-- MODAL -->
<div id="tourModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Tambah Tour</h3>

        <form method="POST">
            <input type="hidden" id="tourId" name="id">

            <label>Nama Tour</label>
            <input type="text" name="nama" id="nama" required>

            <label>Harga Mulai</label>
            <input type="number" name="harga" id="harga" required>

            <label>Rating (0 - 5)</label>
            <input type="number" step="0.1" max="5" name="rating" id="rating" required>

            <label>URL Gambar</label>
            <input type="text" name="gambar" id="gambar" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" required></textarea>

            <button class="btn-save">Simpan</button>
        </form>
    </div>
</div>


<script>
function openModal() {
    document.getElementById("modalTitle").innerText = "Tambah Tour";
    document.getElementById("tourId").value = "";
    document.getElementById("nama").value = "";
    document.getElementById("harga").value = "";
    document.getElementById("rating").value = "";
    document.getElementById("gambar").value = "";
    document.getElementById("deskripsi").value = "";

    document.getElementById("tourModal").classList.add("active");
}

function editTour(data) {
    document.getElementById("modalTitle").innerText = "Edit Tour";

    document.getElementById("tourId").value = data.ID_Tour;
    document.getElementById("nama").value = data.Nama;
    document.getElementById("harga").value = data.Harga_mulai;
    document.getElementById("rating").value = data.Rating;
    document.getElementById("gambar").value = data.Gambar;
    document.getElementById("deskripsi").value = data.Deskripsi;

    document.getElementById("tourModal").classList.add("active");
}

window.onclick = function(e) {
    if (e.target.classList.contains("modal")) {
        document.getElementById("tourModal").classList.remove("active");
    }
}
</script>

</body>
</html>
