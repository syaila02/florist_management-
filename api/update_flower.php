<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {

    $id = $data['id'];
    $category = $data['category'];
    $flower_name = $data['flower_name'];
    $product_type = $data['product_type'];
    $target_gender = $data['target_gender'];
    $price = $data['price'];
    $stock = $data['stock'];

    $query = "UPDATE flowers SET
                category='$category',
                flower_name='$flower_name',
                product_type='$product_type',
                target_gender='$target_gender',
                price='$price',
                stock='$stock'
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["message" => "Data berhasil diupdate"]);
    } else {
        echo json_encode(["message" => "Gagal update data"]);
    }

} else {
    echo json_encode(["message" => "ID tidak ditemukan"]);
}
?>