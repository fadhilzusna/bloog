# bloog<?php
include "koneksi.php";

$query = "SELECT a.*, 
          p.nama_depan, p.nama_belakang,
          k.nama_kategori
          FROM artikel a
          JOIN penulis p ON a.id_penulis = p.id
          JOIN kategori_artikel k ON a.id_kategori = k.id";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
