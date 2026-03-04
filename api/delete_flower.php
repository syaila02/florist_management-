<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {

    $id = $data['id'];

    $query = "DELETE FROM flowers WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["message" => "Gagal menghapus data"]);
    }

} else {
    echo json_encode(["message" => "ID tidak ditemukan"]);
}
?>