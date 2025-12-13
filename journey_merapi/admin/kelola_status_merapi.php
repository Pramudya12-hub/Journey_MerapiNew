<?php
session_start();
require "../include/db.php";

// Cek akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

// DELETE STATUS
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM status_merapi WHERE ID_status = $id");
    header("Location: kelola_status_merapi.php");
    exit;
}

// TAMBAH / EDIT STATUS
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id          = $_POST["id"] ?? null;
    $level       = mysqli_real_escape_string($conn, $_POST["level"]);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST["deskripsi"]);
    $rekomendasi = mysqli_real_escape_string($conn, $_POST["rekomendasi"]);

    if (empty($id)) {
        mysqli_query($conn,
            "INSERT INTO status_merapi (Level, Deskripsi, Rekomendasi, Admin_id)
             VALUES ('$level', '$deskripsi', '$rekomendasi', '$admin_id')"
        );
    } else {
        mysqli_query($conn,
            "UPDATE status_merapi SET 
                Level='$level', 
                Deskripsi='$deskripsi', 
                Rekomendasi='$rekomendasi',
                Admin_id='$admin_id',
                Update_time = CURRENT_TIMESTAMP
             WHERE ID_status=$id"
        );
    }

    header("Location: kelola_status_merapi.php");
    exit;
}

// AMBIL DATA STATUS
$data = mysqli_query($conn, 
    "SELECT s.*, a.Username 
     FROM status_merapi s 
     LEFT JOIN admin a ON s.Admin_id = a.ID_admin
     ORDER BY Update_time DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Status Merapi - JourneyMerapi</title>

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

.sidebar h2 {
    font-size: 22px;
    color: #f0393d;
    margin-bottom: 25px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin-bottom: 18px;
}

.sidebar ul li a {
    color: #ddd;
    text-decoration: none;
    font-size: 16px;
    transition: .25s;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
    color: #f0393d;
    font-weight: 600;
}

.logout-btn {
    display: block;
    margin-top: 30px;
    background: #f0393d;
    padding: 10px 15px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    color: white;
    font-weight: 600;
}

.logout-btn:hover { background:#d6282c; }

/* ---------------- CONTENT ---------------- */
.content {
    margin-left: 280px;
    padding: 40px 60px;
    font-family: Poppins, sans-serif;
}

h1 {
    font-size: 34px;
    font-weight: 700;
}

/* Buttons */
.btn-add, .btn-back {
    display: inline-block;
    padding: 12px 20px;
    background: #f0393d;
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 18px;
    transition: .25s;
}

.btn-add:hover, .btn-back:hover {
    background:#d6282c;
    transform: translateY(-3px);
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    background:#fff;
    border-radius: 12px;
    overflow:hidden;
    margin-top:10px;
    box-shadow:0 6px 18px rgba(0,0,0,.07);
}

th, td {
    padding: 14px;
    border-bottom: 1px solid #eee;
}

th {
    background:#f0393d;
    color:white;
    font-weight:600;
}

td { font-size:14px; }

/* Tombol Edit/Delete */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-edit {
    background:#3498db;
    color:white;
    padding:7px 14px;
    border-radius:8px;
    text-decoration:none;
}
.btn-edit:hover { background:#2b80bd; }

.btn-delete {
    background:#e74c3c;
    color:white;
    padding:7px 14px;
    border-radius:8px;
    text-decoration:none;
}
.btn-delete:hover { background:#c0392b; }

/* ---------------- MODAL ---------------- */
.modal {
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,.4);
    display:none;
    justify-content:center;
    align-items:center;
}

.modal.active { display:flex; }

.modal-content {
    background:#fff;
    padding:25px;
    width:450px;
    border-radius:15px;
    box-shadow:0 10px 28px rgba(0,0,0,.2);
    animation: pop .25s;
}

@keyframes pop {
    from { transform:scale(.85); opacity:0; }
    to { transform:scale(1); opacity:1; }
}

.modal-content input,
textarea,
select {
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
    margin-bottom:14px;
}

.btn-save {
    background:#f0393d;
    color:white;
    padding:12px;
    border:none;
    width:100%;
    border-radius:10px;
    cursor:pointer;
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
        <li><a class="active" href="kelola_status_merapi.php">Status Merapi</a></li>
        <li><a href="kelola_tour.php">Tour</a></li>
        <li><a href="kelola_order.php">Orders</a></li>
        <li><a href="kelola_contact.php">Contact</a></li>
        <li><a href="kelola_user.php">Users</a></li>
    </ul>

    <a href="../auth/logout.php" class="logout-btn">Logout</a>
</aside>


<!-- MAIN CONTENT -->
<main class="content">

    <h1>Status Merapi</h1>

    <a href="dashboard_admin.php" class="btn-back">Kembali</a>
    <a class="btn-add" onclick="openModal()">Tambah Status</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Level</th>
            <th>Deskripsi</th>
            <th>Rekomendasi</th>
            <th>Update Time</th>
            <th>Admin</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $row['ID_status'] ?></td>
            <td><?= $row['Level'] ?></td>
            <td><?= nl2br($row['Deskripsi']) ?></td>
            <td><?= nl2br($row['Rekomendasi']) ?></td>
            <td><?= $row['Update_time'] ?></td>
            <td><?= $row['Username'] ?></td>

            <td>
                <div class="action-buttons">
                    <a class="btn-edit" onclick='editStatus(<?= json_encode($row) ?>)'>Edit</a>
                    <a class="btn-delete" href="?delete=<?= $row['ID_status'] ?>"
                       onclick="return confirm('Yakin hapus status ini?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</main>

<!-- MODAL -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Tambah Status</h3>

        <form method="POST">

            <input type="hidden" id="statusId" name="id">

            <label>Level</label>
            <select name="level" id="level" required>
                <option value="Normal">Normal</option>
                <option value="Waspada">Waspada</option>
                <option value="Siaga">Siaga</option>
                <option value="Awas">Awas</option>
            </select>

            <label>Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" required></textarea>

            <label>Rekomendasi</label>
            <textarea name="rekomendasi" id="rekomendasi" required></textarea>

            <button class="btn-save">Simpan</button>

        </form>
    </div>
</div>


<script>
// BUKA MODAL TAMBAH
function openModal() {
    document.getElementById("modalTitle").innerText = "Tambah Status";
    document.getElementById("statusId").value = "";
    document.getElementById("level").value = "Normal";
    document.getElementById("deskripsi").value = "";
    document.getElementById("rekomendasi").value = "";

    document.getElementById("statusModal").classList.add("active");
}

// EDIT STATUS
function editStatus(data) {
    document.getElementById("modalTitle").innerText = "Edit Status";

    document.getElementById("statusId").value = data.ID_status;
    document.getElementById("level").value = data.Level;
    document.getElementById("deskripsi").value = data.Deskripsi;
    document.getElementById("rekomendasi").value = data.Rekomendasi;

    document.getElementById("statusModal").classList.add("active");
}

// CLOSE MODAL
window.onclick = function(event) {
    if (event.target.classList.contains("modal")) {
        document.getElementById("statusModal").classList.remove("active");
    }
}
</script>

</body>
</html>
