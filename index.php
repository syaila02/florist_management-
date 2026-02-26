<?php 
include 'db.php'; 
$query = "SELECT * FROM flowers ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Florist Admin - Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fff5f7; font-family: 'Poppins', sans-serif; }
        .navbar { background: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%); }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-primary { background-color: #ff85a2; border: none; }
        .btn-primary:hover { background-color: #ff6b8e; }
        .badge-stock { font-size: 0.8rem; }
        .table thead { background-color: #fff0f3; }
        .pink-gradient-text { 
            background: -webkit-linear-gradient(#ff758c, #ff7eb3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🌸 BloomAdmin</a>
    </div>
</nav>

<div class="container">
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <?= $_GET['msg']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold pink-gradient-text">Daftar Koleksi Bunga</h2>
        <a href="create.php" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">+ Tambah Produk</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="text-secondary">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Bunga</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Target</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if(mysqli_num_rows($result) > 0):
                            while($row = mysqli_fetch_assoc($result)): 
                                $stock_class = $row['stock'] > 10 ? 'bg-success' : ($row['stock'] > 0 ? 'bg-warning text-dark' : 'bg-danger');
                        ?>
                        <tr>
                            <td class="ps-4"><?= $no++; ?></td>
                            <td class="fw-semibold"><?= $row['flower_name']; ?></td>
                            <td><span class="badge bg-light text-dark border"><?= $row['category']; ?></span></td>
                            <td><?= $row['product_type']; ?></td>
                            <td><?= $row['target_gender'] ?: '-'; ?></td>
                            <td class="fw-bold text-secondary"><?= formatRupiah($row['price']); ?></td>
                            <td>
                                <span class="badge <?= $stock_class; ?> p-2 rounded-pill badge-stock">
                                    <?= $row['stock']; ?> Pcs
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-info rounded-pill px-3">Edit</a>
                                <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data produk bunga.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 mb-4 text-center text-muted">
    <small>&copy; 2026 BloomAdmin Florist Management</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>