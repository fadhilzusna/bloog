<?php
include "koneksi.php";

$result = $conn->query("SELECT * FROM kategori_artikel");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
