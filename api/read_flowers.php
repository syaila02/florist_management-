<?php
header('Content-Type: application/json');
include_once '../objects/database.php';
include_once '../objects/flower.php';

$database = new Database();
$db = $database->getConnection();

$flower = new Flower($db);
$stmt = $flower->read(); 
$num = $stmt->rowCount();

if ($num > 0) {
    $flowers_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $flower_item = array(
            "id" => $row['id'],
            "category" => $row['category'],
            "flower_name" => $row['flower_name'],
            "product_type" => $row['product_type'],
            "target_gender" => $row['target_gender'],
            "price" => $row['price'],
            "stock" => $row['stock'],
            "created_at" => $row['created_at']
        );
        array_push($flowers_arr, $flower_item);
    }
    echo json_encode(array("success"=>true, "data"=>$flowers_arr));
} else {
    echo json_encode(array("success"=>false, "message"=>"Tidak ada data bunga"));
}
?>