<?php
session_start();
require "include/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$ID_User = $_SESSION['user_id'];

$q = mysqli_query($conn,"
    SELECT * FROM orders 
    WHERE ID_User=$ID_User
    ORDER BY Created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Riwayat Pemesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: Poppins, sans-serif;
    background: linear-gradient(135deg, #f8f9fc 0%, #eceffe 100%);
    margin: 0;
    padding: 40px 60px;
}

/* JUDUL */
h1 {
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 5px;

    background: linear-gradient(#ff2e2e, #bf0000);
    background-clip: text;
    color: transparent;

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}


/* BACK BUTTON */
.back-btn {
    display: inline-block;
    font-size: 18px;
    color: #ff2e2e;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 25px;
}
.back-btn:hover {
    text-decoration: underline;
}

/* CARD ORDER */
.order-card {
    background: white;
    border-radius: 18px;
    padding: 25px 30px;
    margin-bottom: 25px;
    border: 1px solid #e2e2e2;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: 0.25s ease;
}
.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.12);
}

/* DESTINATION TITLE */
.order-header {
    font-size: 22px;
    font-weight: 600;
    color: #111;
    margin-bottom: 12px;
}

/* INFO TEXT */
.order-info {
    font-size: 16px;
    line-height: 1.7;
    color: #333;
}

/* STATUS CHIP */
.status {
    padding: 7px 14px;
    border-radius: 10px;
    font-weight: 600;
    display: inline-block;
    font-size: 14px;
    margin-top: 10px;
}

.pending {
    background:#ffe1b3;
    color:#9f5f00;
}
.process {
    background:#cfe4ff;
    color:#005a9c;
}
.done {
    background:#c8f7c5;
    color:#117a0f;
}

/* BUTTONS */
.card-buttons {
    margin-top: 18px;
}

.btn {
    padding: 11px 18px;
    border-radius: 12px;
    text-decoration: none;
    color: white !important;
    font-weight: 600;
    margin-right: 10px;
    display: inline-block;
    font-size: 14px;
}

.btn-detail {
    background: #007bff;
}
.btn-detail:hover {
    background: #005fcc;
}

.btn-invoice {
    background: #28c76f;
}
.btn-invoice:hover {
    background: #1fa85a;
}

.btn-disabled {
    background:#a0a0a0;
    cursor:not-allowed;
}

/* MODAL */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    display: none;
    justify-content: center;
    align-items: center;
}
.modal.active {
    display: flex;
}

.modal-box {
    background:white;
    width: 450px;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.close-btn {
    background:#e74c3c;
    color:white;
    padding:10px;
    width:100%;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
}
.close-btn:hover {
    background:#c0392b;
}
</style>
</head>

<body>

<h1>Riwayat Pemesanan Saya</h1>
<a class="back-btn" href="order.php">Kembali ke Order</a>

<?php if (mysqli_num_rows($q) == 0): ?>
    <p style="opacity:.6; font-size:18px;">Belum ada pemesanan.</p>
<?php endif; ?>

<?php while ($o = mysqli_fetch_assoc($q)): ?>

<?php
$first = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM order_items WHERE ID_Order={$o['ID_Order']} LIMIT 1
"));
$count = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) AS total FROM order_items WHERE ID_Order={$o['ID_Order']}
"))['total'];

$statusClass =
    $o['Status']=="Pending" ? "pending" :
    ($o['Status']=="Diproses" ? "process" : "done");
?>

<div class="order-card">
    
    <div class="order-header">
        <?= $first['Tour_Name'] ?>
        <?php if ($count > 1): ?>
            + <?= $count - 1 ?> destinasi lain
        <?php endif; ?>
    </div>

    <div class="order-info">
        <b>Pickup:</b> <?= $o['pickup_date'] ?> (<?= $o['pickup_time'] ?>) <br>
        <b>Jumlah Orang:</b> <?= $o['Jumlah_orang'] ?> <br>
        <b>Total:</b> IDR <?= number_format($o['total_price'],0,",",".") ?> <br>

        <span class="status <?= $statusClass ?>">
            <?= $o['Status'] ?>
        </span>
    </div>

    <div class="card-buttons">
        <a href="#" class="btn btn-detail" onclick="showDetail(<?= $o['ID_Order'] ?>)">Detail</a>

        <?php if ($o['Status'] != 'Pending'): ?>
            <a href="invoice.php?id=<?= $o['ID_Order'] ?>" class="btn btn-invoice">Invoice</a>
        <?php else: ?>
            <span class="btn btn-disabled">Pending</span>
        <?php endif; ?>
    </div>
</div>

<?php endwhile; ?>


<!-- MODAL DETAIL -->
<div id="detailModal" class="modal">
    <div class="modal-box">
        <h3 style="margin-top:0; margin-bottom:10px;">Detail Pemesanan</h3>
        <div id="detailBody" style="font-size:15px;">Memuat...</div>
        <button class="close-btn" onclick="closeDetail()">Tutup</button>
    </div>
</div>

<script>
function showDetail(id){
    const modal = document.getElementById("detailModal");
    const body  = document.getElementById("detailBody");

    modal.classList.add("active");
    body.innerHTML = "Memuat...";

    fetch("admin/order_items_api.php?id="+id)
        .then(r=>r.text())
        .then(html=>body.innerHTML=html);
}

function closeDetail(){
    document.getElementById("detailModal").classList.remove("active");
}
</script>

</body>
</html>
