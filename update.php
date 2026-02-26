<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id       = mysqli_real_escape_string($conn, $_POST['id']);
    $name     = mysqli_real_escape_string($conn, $_POST['flower_name']);
    $cat      = mysqli_real_escape_string($conn, $_POST['category']);
    $type     = mysqli_real_escape_string($conn, $_POST['product_type']);
    $gender   = ($type == 'Buket' && !empty($_POST['target_gender'])) ? "'".mysqli_real_escape_string($conn, $_POST['target_gender'])."'" : "NULL";
    $price    = (float)$_POST['price'];
    $stock    = (int)$_POST['stock'];

    $sql = "UPDATE flowers SET 
            flower_name = '$name', 
            category = '$cat', 
            product_type = '$type', 
            target_gender = $gender, 
            price = '$price', 
            stock = '$stock' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Data berhasil diperbarui!");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit;
}
?>