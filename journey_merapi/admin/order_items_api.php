<?php
require "../include/db.php";

$id = intval($_GET['id']);

$q = mysqli_query($conn, "
    SELECT * FROM order_items 
    WHERE ID_Order = $id
");

echo "
<table style='width:100%; border-collapse:collapse; margin-top:10px;'>
<tr>
    <th style='padding:10px; background:#222; color:white;'>Destinasi</th>
    <th style='padding:10px; background:#222; color:white;'>Harga</th>
    <th style='padding:10px; background:#222; color:white;'>Qty</th>
    <th style='padding:10px; background:#222; color:white;'>Subtotal</th>
</tr>
";

if (mysqli_num_rows($q) === 0) {
    echo "
    <tr>
        <td colspan='4' style='text-align:center; padding:15px; border:1px solid #ddd;'>
            Tidak ada destinasi pada pesanan ini.
        </td>
    </tr>
    ";
}

while ($d = mysqli_fetch_assoc($q)) {

    $price = number_format($d['Price'], 0, ",", ".");
    $subtotal = number_format($d['Subtotal'], 0, ",", ".");

    echo "
    <tr>
        <td style='padding:10px; border-bottom:1px solid #ddd;'>{$d['Tour_Name']}</td>
        <td style='padding:10px; border-bottom:1px solid #ddd;'>IDR {$price}</td>
        <td style='padding:10px; border-bottom:1px solid #ddd;'>{$d['Quantity']}</td>
        <td style='padding:10px; border-bottom:1px solid #ddd;'>IDR {$subtotal}</td>
    </tr>
    ";
}

echo "</table>";
?>
