<?php
if (isset($_POST['submit'])) {
    include 'db.php';
    $name     = mysqli_real_escape_string($conn, $_POST['flower_name']);
    $cat      = mysqli_real_escape_string($conn, $_POST['category']);
    $type     = mysqli_real_escape_string($conn, $_POST['product_type']);
    $gender   = ($type == 'Buket' && !empty($_POST['target_gender'])) ? "'".mysqli_real_escape_string($conn, $_POST['target_gender'])."'" : "NULL";
    $price    = (float)$_POST['price'];
    $stock    = (int)$_POST['stock'];

    $sql = "INSERT INTO flowers (flower_name, category, product_type, target_gender, price, stock) 
            VALUES ('$name', '$cat', '$type', $gender, '$price', '$stock')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Produk berhasil ditambahkan!");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Bunga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fff5f7; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 20px; border: none; }
        .btn-save { background: linear-gradient(45deg, #ff758c, #ff7eb3); color: white; border: none; }
        .btn-save:hover { background: linear-gradient(45deg, #ff6b8e, #ff758c); color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="mb-4 fw-bold" style="color: #ff758c;">Tambah Produk Baru</h3>
                    <form action="create.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Nama Bunga</label>
                                <input type="text" name="flower_name" class="form-control" required placeholder="Contoh: Mawar Merah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="Fresh Flower">Fresh Flower</option>
                                    <option value="Pipe Cleaner Flower">Pipe Cleaner Flower</option>
                                    <option value="Artificial Flower">Artificial Flower</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Tipe Produk</label>
                                <select name="product_type" id="product_type" class="form-select" required onchange="toggleGender()">
                                    <option value="Satuan">Satuan</option>
                                    <option value="Buket">Buket</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="gender_box">
                                <label class="form-label text-secondary">Target Gender</label>
                                <select name="target_gender" id="target_gender" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="Cowok">Cowok</option>
                                    <option value="Cewek">Cewek</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Harga (IDR)</label>
                                <input type="number" name="price" class="form-control" required placeholder="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Stok</label>
                                <input type="number" name="stock" class="form-control" required placeholder="0">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="submit" class="btn btn-save px-5 rounded-pill shadow-sm">Simpan Produk</button>
                            <a href="index.php" class="btn btn-light px-4 rounded-pill border ms-2">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleGender() {
    const type = document.getElementById('product_type').value;
    const genderBox = document.getElementById('gender_box');
    const genderSelect = document.getElementById('target_gender');
    
    if(type === 'Satuan') {
        genderSelect.value = "";
        genderSelect.disabled = true;
        genderBox.style.opacity = '0.5';
    } else {
        genderSelect.disabled = false;
        genderBox.style.opacity = '1';
    }
}
// Run on load to set initial state
window.onload = toggleGender;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>