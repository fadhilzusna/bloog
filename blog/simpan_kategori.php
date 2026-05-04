<?php
include "koneksi.php";

$nama = $_POST['nama_kategori'];
$ket  = $_POST['keterangan'];

$stmt = $conn->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param("ss", $nama, $ket);

if ($stmt->execute()) {
    echo json_encode(["status" => "sukses"]);
} else {
    echo json_encode(["status" => "gagal"]);
}
?>
