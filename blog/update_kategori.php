<?php
include 'koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama_kategori'];
$keterangan = $_POST['keterangan'];

$query = "UPDATE kategori SET 
    nama_kategori='$nama',
    keterangan='$keterangan'
    WHERE id=$id";

$result = mysqli_query($conn, $query);

echo json_encode([
    "status" => $result ? "berhasil" : "gagal"
]);
