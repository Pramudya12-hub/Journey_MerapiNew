<?php
session_start();
require "../include/db.php";

// Hanya admin yang boleh masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// HAPUS PESAN
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM contact WHERE ID_Contact = $id");
    header("Location: kelola_contact.php");
    exit;
}

// AMBIL DATA (PERBAIKAN LINE 20)
$data = mysqli_query($conn, "SELECT * FROM contact ORDER BY Created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesan Masuk - JourneyMerapi</title>

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

.sidebar ul { list-style: none; padding: 0; }
.sidebar ul li { margin-bottom: 18px; }

.sidebar ul li a {
    text-decoration: none;
    color: #ddd;
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
    text-align: center;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
}
.logout-btn:hover { background: #d6282c; }

/* ---------------- CONTENT ---------------- */
.content {
    margin-left: 280px;
    padding: 40px 60px;
    font-family: Poppins, sans-serif;
}

h1 {
    font-size: 34px;
    margin-bottom: 10px;
}

/* TABLE DESIGN */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,.08);
}

th, td {
    padding: 14px;
    border-bottom: 1px solid #eee;
}

th {
    background: #f0393d;
    color: white;
    font-weight: 600;
}

tr:hover {
    background: #fafafa;
}

/* DELETE BUTTON */
.btn-delete {
    background: #e74c3c;
    padding: 7px 14px;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    transition: .25s;
}
.btn-delete:hover {
    background: #c0392b;
    transform: translateY(-2px);
}
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
        <li><a href="kelola_order.php">Orders</a></li>
        <li><a class="active" href="kelola_contact.php">Contact</a></li>
        <li><a href="kelola_user.php">Users</a></li>
    </ul>
    <a href="../auth/logout.php" class="logout-btn">Logout</a>
</aside>

<!-- CONTENT -->
<main class="content">
    <h1>Pesan Masuk</h1>
    <p>Lihat pesan yang dikirim oleh pengunjung website.</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Pesan</th>
            <th>Waktu</th>
            <th>Aksi</th>
        </tr>

        <?php if (mysqli_num_rows($data) === 0): ?>
        <tr>
            <td colspan="6" style="text-align:center; padding:20px;">Belum ada pesan masuk.</td>
        </tr>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $row['ID_Contact'] ?></td>
            <td><?= htmlspecialchars($row['Name']) ?></td>
            <td><?= htmlspecialchars($row['Email']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['Message'])) ?></td>
            <td><?= $row['Created_at'] ?></td>

            <td>
                <a 
                   class="btn-delete"
                   onclick="return confirm('Hapus pesan dari <?= $row['Name'] ?>?')"
                   href="?delete=<?= $row['ID_Contact'] ?>"
                >Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</main>

</body>
</html>
