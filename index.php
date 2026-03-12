<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Florist Admin - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome untuk Ikon Search -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fdf2f4; font-family: 'Poppins', sans-serif; }
        .navbar { background: linear-gradient(45deg, #ff758c, #ff7eb3); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card { border: none; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
        .pink-btn { background: linear-gradient(45deg, #ff758c, #ff7eb3); border: none; color: white; padding: 10px 25px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; }
        .pink-btn:hover { background: linear-gradient(45deg, #ff6b8e, #ff758c); color: white; transform: translateY(-2px); transition: 0.3s; }
        .flower-img { width: 60px; height: 60px; object-fit: cover; border-radius: 12px; }
        .badge-pill { padding: 6px 15px; border-radius: 50px; }
        /* Styling Search & Filter */
        .filter-section { background: white; border-radius: 20px; padding: 20px; margin-bottom: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .search-input { border-radius: 50px 0 0 50px; border-right: none; padding-left: 25px; }
        .search-btn { border-radius: 0 50px 50px 0; background: #ff758c; color: white; border: none; padding: 0 25px; }
        .form-select-pill { border-radius: 50px; padding-left: 20px; }
    </style>
</head>
<body>

<script>
    if (!localStorage.getItem('jwt_token')) { window.location.href = 'login.php'; }
</script>

<nav class="navbar navbar-expand-lg navbar-dark mb-4 py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php">🌸 BloomAdmin</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-4 d-none d-md-block">Halo, <b id="user-display-name">Admin</b></span>
            <button onclick="logout()" class="btn btn-light btn-sm rounded-pill px-4 fw-bold text-danger shadow-sm">Logout</button>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold" style="color: #ff758c;">Manajemen Katalog</h2>
            <p class="text-muted">Kelola produk bunga toko Anda dengan mudah.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="create.php" class="pink-btn shadow-sm">+ Produk Baru</a>
            <a href="katalog.php" class="btn btn-outline-info rounded-pill px-4 fw-bold ms-2">Lihat Toko</a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="filter-section">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari nama bunga atau tipe produk...">
                    <button class="btn search-btn" type="cd button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select id="categoryFilter" class="form-select form-select-pill shadow-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Fresh Flower">Fresh Flower</option>
                    <option value="Pipe Cleaner Flower">Pipe Cleaner Flower</option>
                    <option value="Artificial Flower">Artificial Flower</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button onclick="fetchFlowers()" class="btn btn-outline-secondary rounded-pill w-100"><i class="fas fa-sync"></i> Reset</button>
            </div>
        </div>
    </div>

    <div class="card bg-white p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="flower-table-body">
                    <tr><td colspan="6" class="text-center py-5">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('user-display-name').innerText = localStorage.getItem('user_name') || 'Admin';

    let allFlowers = []; // Menyimpan data asli dari API

    async function fetchFlowers() {
        const tableBody = document.getElementById('flower-table-body');
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        
        try {
            const response = await fetch('api/get_flowers.php');
            allFlowers = await response.json();
            renderTable(allFlowers);
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-5">Gagal mengambil data dari API.</td></tr>';
        }
    }

    function renderTable(data) {
        const tableBody = document.getElementById('flower-table-body');
        
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>';
            return;
        }

        tableBody.innerHTML = data.map(flower => `
            <tr>
                <td><img src="${flower.image_url}" class="flower-img shadow-sm" onerror="this.src='https://via.placeholder.com/60?text=Flower'"></td>
                <td>
                    <div class="fw-bold">${flower.flower_name}</div>
                    <small class="text-muted">${flower.product_type} ${flower.target_gender ? '(' + flower.target_gender + ')' : ''}</small>
                </td>
                <td><span class="badge bg-light text-dark border badge-pill">${flower.category}</span></td>
                <td class="fw-bold text-secondary">Rp ${parseInt(flower.price).toLocaleString('id-ID')}</td>
                <td><span class="badge ${flower.stock > 5 ? 'bg-success' : 'bg-warning'} badge-pill">${flower.stock} Pcs</span></td>
                <td class="text-center">
                    <a href="edit.php?id=${flower.id}" class="btn btn-sm btn-outline-info rounded-pill px-3">Edit</a>
                    <button onclick="deleteFlower(${flower.id})" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1">Hapus</button>
                </td>
            </tr>
        `).join('');
    }

    // Fungsi Pencarian & Filter
    function applyFilter() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;

        const filteredData = allFlowers.filter(flower => {
            const matchKeyword = flower.flower_name.toLowerCase().includes(keyword) || 
                               flower.product_type.toLowerCase().includes(keyword);
            const matchCategory = category === "" || flower.category === category;
            
            return matchKeyword && matchCategory;
        });

        renderTable(filteredData);
    }

    // Event Listener untuk Search & Category
    document.getElementById('searchInput').addEventListener('input', applyFilter);
    document.getElementById('categoryFilter').addEventListener('change', applyFilter);

    async function deleteFlower(id) {
        const result = await Swal.fire({
            title: 'Hapus Produk?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff758c',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '20px'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('api/delete_flower.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('jwt_token')
                    },
                    body: JSON.stringify({ id: id })
                });
                
                const resData = await response.json();
                
                if (resData.status === 'success') {
                    Swal.fire('Terhapus!', resData.message, 'success');
                    fetchFlowers(); // Reload data asli
                } else {
                    Swal.fire('Gagal!', resData.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
            }
        }
    }

    function logout() {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('user_name');
        window.location.href = 'login.php';
    }

    // Ambil data pertama kali
    fetchFlowers();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>