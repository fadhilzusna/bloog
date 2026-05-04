<?php
include "koneksi.php";

$id_penulis = $_POST['id_penulis'];
$id_kategori = $_POST['id_kategori'];
$judul = $_POST['judul'];
$isi = $_POST['isi'];

// tanggal otomatis
date_default_timezone_set('Asia/Jakarta');
$hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];

$now = new DateTime();
$hari_tanggal = $hari[$now->format('w')] . ", " .
    $now->format('j') . " " .
    $bulan[(int)$now->format('n')] . " " .
    $now->format('Y') . " | " .
    $now->format('H:i');

// upload gambar (WAJIB)
if (empty($_FILES['gambar']['name'])) {
    echo json_encode(["status" => "gambar wajib"]);
    exit;
}

$nama_file = time() . "_" . $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];

// validasi ukuran dulu
if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
    echo json_encode(["status" => "maksimal 2MB"]);
    exit;
}

// validasi ekstensi sederhana (AMAN untuk sekarang)
$ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png'];

if (!in_array($ext, $allowed)) {
    echo json_encode(["status" => "file harus jpg/png"]);
    exit;
}

move_uploaded_file($tmp, "uploads_artikel/" . $nama_file);

$stmt = $conn->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $nama_file, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(["status" => "sukses"]);
} else {
    echo json_encode([
        "status" => "gagal",
        "error" => $stmt->error
    ]);
}
?>
