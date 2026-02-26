<?php
include 'db.php';
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM flowers WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk Bunga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fff5f7; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 20px; border: none; }
        .btn-update { background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); color: white; border: none; }
        .btn-update:hover { background: linear-gradient(45deg, #00c6fb 0%, #005bea 100%); color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="mb-4 fw-bold text-info">Edit Produk</h3>
                    <form action="update.php" method="POST">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Nama Bunga</label>
                                <input type="text" name="flower_name" class="form-control" value="<?= $data['flower_name']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="Fresh Flower" <?= $data['category'] == 'Fresh Flower' ? 'selected' : ''; ?>>Fresh Flower</option>
                                    <option value="Pipe Cleaner Flower" <?= $data['category'] == 'Pipe Cleaner Flower' ? 'selected' : ''; ?>>Pipe Cleaner Flower</option>
                                    <option value="Artificial Flower" <?= $data['category'] == 'Artificial Flower' ? 'selected' : ''; ?>>Artificial Flower</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Tipe Produk</label>
                                <select name="product_type" id="product_type" class="form-select" required onchange="toggleGender()">
                                    <option value="Satuan" <?= $data['product_type'] == 'Satuan' ? 'selected' : ''; ?>>Satuan</option>
                                    <option value="Buket" <?= $data['product_type'] == 'Buket' ? 'selected' : ''; ?>>Buket</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="gender_box">
                                <label class="form-label text-secondary">Target Gender</label>
                                <select name="target_gender" id="target_gender" class="form-select">
                                    <option value="" <?= is_null($data['target_gender']) ? 'selected' : ''; ?>>-- Pilih --</option>
                                    <option value="Cowok" <?= $data['target_gender'] == 'Cowok' ? 'selected' : ''; ?>>Cowok</option>
                                    <option value="Cewek" <?= $data['target_gender'] == 'Cewek' ? 'selected' : ''; ?>>Cewek</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Harga (IDR)</label>
                                <input type="number" name="price" class="form-control" value="<?= $data['price']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Stok</label>
                                <input type="number" name="stock" class="form-control" value="<?= $data['stock']; ?>" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-update px-5 rounded-pill shadow-sm">Update Produk</button>
                            <a href="index.php" class="btn btn-light px-4 rounded-pill border ms-2">Batal</a>
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
window.onload = toggleGender;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>