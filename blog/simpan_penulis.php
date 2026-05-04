<?php
include "koneksi.php";

$nama_depan = $_POST['nama_depan'];
$nama_belakang = $_POST['nama_belakang'];
$username = $_POST['user_name'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// upload foto
$foto = "default.png";

if (!empty($_FILES['foto']['name'])) {
    $nama_file = time() . "_" . $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];

    move_uploaded_file($tmp, "uploads_penulis/" . $nama_file);
    $foto = $nama_file;
}

$stmt = $conn->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama_depan, $nama_belakang, $username, $password, $foto);

if ($stmt->execute()) {
    echo json_encode(["status" => "sukses"]);
} else {
    echo json_encode(["status" => "gagal"]);
}
?>
