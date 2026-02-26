<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "DELETE FROM flowers WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Produk berhasil dihapus!");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit;
}
?>