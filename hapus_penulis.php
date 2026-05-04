<?php
include "koneksi.php";

$id = $_POST['id'];
$foto = $_POST['foto'];

// cek apakah penulis masih punya artikel
$cek = $conn->query("SELECT * FROM artikel WHERE id_penulis=$id");

if ($cek->num_rows > 0) {
    echo json_encode(["status" => "gagal: masih punya artikel"]);
    exit;
}

// hapus dari database
$stmt = $conn->prepare("DELETE FROM penulis WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // hapus file foto kalau bukan default
    if ($foto != "default.png") {
        unlink("uploads_penulis/" . $foto);
    }

    echo json_encode(["status" => "sukses"]);
} else {
    echo json_encode(["status" => "gagal"]);
}
?>
