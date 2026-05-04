<?php
include 'koneksi.php';

$id = $_POST['id'];
$judul = $_POST['judul'];
$isi = $_POST['isi'];

$query = "UPDATE artikel SET 
    judul='$judul',
    isi='$isi'
    WHERE id=$id";

$result = mysqli_query($conn, $query);

echo json_encode([
    "status" => $result ? "berhasil" : "gagal"
]);
