<?php
include "koneksi.php";

$id = $_POST['id'];
$gambar = $_POST['gambar'];

// hapus dari database
$stmt = $conn->prepare("DELETE FROM artikel WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // hapus file gambar
    if (file_exists("uploads_artikel/" . $gambar)) {
        unlink("uploads_artikel/" . $gambar);
    }

    echo json_encode(["status" => "sukses"]);
} else {
    echo json_encode(["status" => "gagal"]);
}
?>
