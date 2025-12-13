<?php
session_start();
require "../include/db.php";

// Cek akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// DELETE USER
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE ID_User = $id");
    header("Location: kelola_user.php");
    exit;
}

// EDIT USER (USERNAME & EMAIL)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id       = $_POST["id"];
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email    = mysqli_real_escape_string($conn, $_POST["email"]);

    mysqli_query($conn,
        "UPDATE users 
         SET Username='$username', Email='$email'
         WHERE ID_User=$id"
    );

    header("Location: kelola_user.php");
    exit;
}

// Ambil data users
$data = mysqli_query($conn, "SELECT * FROM users ORDER BY ID_User ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - JourneyMerapi</title>

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
    top: 0;
    left: 0;
}

.sidebar h2 {
    font-size: 22px;
    color: #f0393d;
    margin-bottom: 25px;
    font-weight: 700;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin-bottom: 18px;
}

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
    transition: .25s;
}

.logout-btn:hover {
    background: #d6282c;
}

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
    background: #f0393d;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 18px;
    transition: .25s;
}

.btn-add:hover, .btn-back:hover {
    background: #d6282c;
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(240,57,61,0.35);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
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

td {
    font-size: 14px;
}

/* Action buttons */
.btn-edit, .btn-delete {
    padding: 7px 14px;
    border-radius: 8px;
    text-decoration: none;
    color: #fff;
    transition: .25s;
}

.btn-edit { background: #3498db; }
.btn-edit:hover { background: #2c80b4; }

.btn-delete { background: #e74c3c; }
.btn-delete:hover { background: #c0392b; }

/* ---------------- MODAL ---------------- */
.modal {
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,.4);
    display:none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity .25s ease;
}

.modal.active {
    display:flex;
    opacity: 1;
}

.modal-content {
    background:#fff;
    padding:25px;
    width:420px;
    border-radius:15px;
    box-shadow:0 10px 28px rgba(0,0,0,.25);
    animation: pop .25s ease;
}

@keyframes pop {
    from { transform: scale(.85); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.modal-content input {
    width:100%;
    padding:12px;
    margin-bottom:14px;
    border-radius:8px;
    border:1px solid #ccc;
}

.btn-save {
    width:100%;
    padding:12px;
    background:#f0393d;
    color:#fff;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-size:16px;
    transition:.25s;
}

.btn-save:hover {
    background:#d6282c;
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
        <li><a href="kelola_contact.php">Contact</a></li>
        <li><a class="active" href="kelola_user.php">Users</a></li>
    </ul>

    <a href="../auth/logout.php" class="logout-btn">Logout</a>
</aside>

<!-- MAIN CONTENT -->
<main class="content">

    <h1>Kelola Users</h1>

    <a href="dashboard_admin.php" class="btn-back">Kembali ke Dashboard</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $row['ID_User'] ?></td>
            <td><?= $row['Username'] ?></td>
            <td><?= $row['Email'] ?></td>
            <td><?= $row['Created_at'] ?></td>

            <td>
                <a class="btn-edit" onclick='editUser(<?= json_encode($row) ?>)'>Edit</a>
                <a class="btn-delete" 
                   onclick="return confirm('Hapus user ini?')" 
                   href="?delete=<?= $row['ID_User'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</main>

<!-- MODAL -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Edit User</h3>

        <form method="POST">
            <input type="hidden" id="userId" name="id">

            <label>Username</label>
            <input type="text" name="username" id="username" required>

            <label>Email</label>
            <input type="email" name="email" id="email" required>

            <button class="btn-save">Simpan</button>
        </form>
    </div>
</div>

<script>
function editUser(data) {
    document.getElementById("modalTitle").innerText = "Edit User";

    document.getElementById("userId").value = data.ID_User;
    document.getElementById("username").value = data.Username;
    document.getElementById("email").value = data.Email;

    document.getElementById("userModal").classList.add("active");
}

window.onclick = function(e) {
    if (e.target.id === "userModal") {
        document.getElementById("userModal").classList.remove("active");
    }
}
</script>

</body>
</html>
