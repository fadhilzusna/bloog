<?php
include 'koneksi.php';

$id = $_POST['id'];
$nama_depan = $_POST['nama_depan'];
$nama_belakang = $_POST['nama_belakang'];
$user_name = $_POST['user_name'];
$password = $_POST['password'];

// kalau password diisi → update
if(!empty($password)){
    $query = "UPDATE penulis SET 
        nama_depan='$nama_depan',
        nama_belakang='$nama_belakang',
        user_name='$user_name',
        password='$password'
        WHERE id=$id";
}else{
    $query = "UPDATE penulis SET 
        nama_depan='$nama_depan',
        nama_belakang='$nama_belakang',
        user_name='$user_name'
        WHERE id=$id";
}

$result = mysqli_query($conn, $query);

echo json_encode([
    "status" => $result ? "berhasil" : "gagal"
]);
