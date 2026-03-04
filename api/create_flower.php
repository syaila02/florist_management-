<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['category']) &&
    isset($data['flower_name']) &&
    isset($data['product_type']) &&
    isset($data['price']) &&
    isset($data['stock'])
) {
    $category = $data['category'];
    $flower_name = $data['flower_name'];
    $product_type = $data['product_type'];
    $target_gender = $data['target_gender'] ?? null;
    $price = $data['price'];
    $stock = $data['stock'];

    $query = "INSERT INTO flowers 
              (category, flower_name, product_type, target_gender, price, stock) 
              VALUES 
              ('$category', '$flower_name', '$product_type', '$target_gender', '$price', '$stock')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["message" => "Data berhasil ditambahkan"]);
    } else {
        echo json_encode(["message" => "Gagal menambahkan data"]);
    }
} else {
    echo json_encode(["message" => "Data tidak lengkap"]);
}
?>