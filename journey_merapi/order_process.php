<?php
session_start();
require "include/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Anda harus login!");
}

$ID_User = $_SESSION['user_id'];

// ===== Ambil data dari form =====
$tours           = json_decode($_POST['tours'], true);
$jumlah_orang    = intval($_POST['jumlah_orang']);
$pickup_date     = $_POST['pickup_date'];
$pickup_time     = $_POST['pickup_time'];
$total_price     = intval($_POST['total_price']);
$pickup_location = "Meeting Point Merapi";

// Data lengkap user
$full_name        = $_POST['full_name'];
$country          = $_POST['country'];
$email            = $_POST['email'];
$phone            = $_POST['phone'];
$special_request  = $_POST['special_request'];

// Validasi
if (!$tours || count($tours) == 0) {
    die("Tidak ada destinasi yang dipilih.");
}

// ===== INSERT ke tabel ORDERS =====
$stmt = $conn->prepare("
    INSERT INTO orders 
    (ID_User, full_name, country, email, phone, special_request,
    Jumlah_orang, pickup_date, pickup_time, pickup_location, total_price, Status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
");

$stmt->bind_param(
    "isssssisssi",
    $ID_User,
    $full_name,
    $country,
    $email,
    $phone,
    $special_request,
    $jumlah_orang,
    $pickup_date,
    $pickup_time,
    $pickup_location,
    $total_price
);

$stmt->execute();
$ID_Order = $stmt->insert_id;


// ===== INSERT setiap destinasi ke ORDER_ITEMS =====
$item = $conn->prepare("
    INSERT INTO order_items (ID_Order, Tour_ID, Tour_Name, Price, Quantity, Subtotal)
    VALUES (?, ?, ?, ?, ?, ?)
");

foreach ($tours as $t) {

    $tour_id  = intval($t['id']);
    $name     = $t['name'];
    $price    = intval($t['price']);

    $subtotal = $price * $jumlah_orang;

    $item->bind_param(
        "iisiii",
        $ID_Order,
        $tour_id,
        $name,
        $price,
        $jumlah_orang,
        $subtotal
    );

    $item->execute();
}


// ===== Sukses =====
echo "
<script>
    alert('Reservasi berhasil disimpan!');
    window.location.href='order.php';
</script>
";
?>
