<?php
session_start();
require "include/db.php";
require "dompdf/autoload.inc.php";

use Dompdf\Dompdf;

// Pastikan param id ada
if (!isset($_GET['id'])) {
    die("Invoice tidak valid.");
}

$ID_Order = intval($_GET['id']);

// Ambil data order
$order = mysqli_query($conn, "
    SELECT o.*, u.Username, u.Email
    FROM orders o
    LEFT JOIN users u ON o.ID_User = u.ID_User
    WHERE o.ID_Order = $ID_Order
");

$d = mysqli_fetch_assoc($order);

// Cek apakah order ada
if (!$d) {
    die("Order tidak ditemukan.");
}

// Cek apakah invoice adalah milik user yg login
if ($d['ID_User'] != $_SESSION['user_id']) {
    die("Anda tidak memiliki akses ke invoice ini.");
}

// CEK STATUS → hanya boleh print jika sudah disetujui admin
if ($d['Status'] == "Pending") {
    die("<h2 style='text-align:center;color:red;'>Invoice belum bisa dicetak karena pesanan masih PENDING.</h2>");
}

$items = mysqli_query($conn, "SELECT * FROM order_items WHERE ID_Order=$ID_Order");

// =================== TEMPLATE HTML ===================
$html = "
<style>
body { font-family: DejaVu Sans, sans-serif; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { border:1px solid #ddd; padding:8px; }
th { background:#f0393d; color:white; }
h2, h3 { text-align:center; }
</style>

<h2>Invoice Pemesanan JourneyMerapi</h2>

<p><b>ID Order:</b> {$d['ID_Order']}</p>
<p><b>Nama:</b> {$d['Username']}</p>
<p><b>Email:</b> {$d['Email']}</p>
<p><b>Pickup:</b> {$d['pickup_date']} - {$d['pickup_time']}</p>
<p><b>Status:</b> {$d['Status']}</p>

<h3>Detail Destinasi</h3>

<table>
<tr>
<th>Destinasi</th>
<th>Harga</th>
<th>Qty</th>
<th>Subtotal</th>
</tr>";

while ($i = mysqli_fetch_assoc($items)) {
    $html .= "
    <tr>
        <td>{$i['Tour_Name']}</td>
        <td>IDR " . number_format($i['Price'],0,",",".") . "</td>
        <td>{$i['Quantity']}</td>
        <td>IDR " . number_format($i['Subtotal'],0,",",".") . "</td>
    </tr>";
}

$html .= "
</table>

<h3>Total: IDR " . number_format($d['total_price'],0,",",".") . "</h3>
";

// =================== GENERATE PDF ===================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();

// Download
$dompdf->stream("Invoice_{$ID_Order}.pdf", ["Attachment" => true]);
?>
