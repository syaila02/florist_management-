<?php
header("Content-Type: application/json");
include "../db.php";

$query = "SELECT * FROM flowers";
$result = mysqli_query($conn, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>