<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bloom Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fdf2f4; font-family: 'Poppins', sans-serif; }
        .sidebar { background: white; min-height: 100vh; border-right: 1px solid #eee; position: fixed; width: 250px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #555; font-weight: 500; border-radius: 10px; margin-bottom: 5px; cursor: pointer; }
        .nav-link.active { background: #ff758c; color: white !important; }
        .card { border: none; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; }
        .badge-status { padding: 5px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }
        .bg-pending { background: #ffeeba; color: #856404; }
        .bg-packing { background: #b8daff; color: #004085; }
        .bg-delivered { background: #c3e6cb; color: #155724; }
        .bg-selesai { background: #eee; color: #666; }
        .img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
    <script>
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login';
        }
    </script>
</head>
<body>

<div class="sidebar py-4 px-3">
    <h4 class="fw-bold mb-4" style="color: #ff758c;">🌸 BloomAdmin</h4>
    <nav class="nav flex-column">
        <a class="nav-link active" id="menu-dashboard" onclick="switchMainTab('dashboard')">📊 Dashboard</a>
        <a class="nav-link" id="menu-inventory" onclick="switchMainTab('inventory')">📦 Inventori</a>
        <a class="nav-link" id="menu-orders" onclick="switchMainTab('orders')">📋 Pesanan <span id="new-order-badge" class="badge bg-danger rounded-pill" style="display:none;">0</span></a>
        <a class="nav-link" id="menu-reviews" onclick="switchMainTab('reviews')">⭐ Ulasan <span id="new-review-badge" class="badge bg-primary rounded-pill" style="display:none;">0</span></a>
        <hr>
        <a class="nav-link" href="/">Lihat Website</a>
        <a class="nav-link text-danger" href="#" onclick="logout()">Logout</a>
    </nav>
</div>

<div class="main-content">
    
    <div id="section-dashboard">
        <h2 class="fw-bold mb-4">Ringkasan Bisnis</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card p-4 text-center border-0 shadow-sm" style="background: linear-gradient(135deg, #ff758c, #ff7eb3); color: white;">
                    <p class="small mb-1 opacity-75">Total Pendapatan</p>
                    <h3 class="fw-bold mb-0" id="stat-revenue">Rp 0</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white;">
                    <p class="small mb-1 opacity-75">Pesanan Selesai</p>
                    <h3 class="fw-bold mb-0" id="stat-orders">0</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <p class="small mb-1 opacity-75">Total Produk</p>
                    <h3 class="fw-bold mb-0" id="stat-products">0</h3>
                </div>
            </div>
        </div>

        <div id="low-stock-alert" class="alert alert-warning border-0 rounded-4 p-4 shadow-sm mb-5" style="display:none;">
            <h5 class="fw-bold mb-3">⚠️ Peringatan Stok Rendah (< 5)</h5>
            <div id="low-stock-list" class="row g-2"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-4">Produk Terlaris by Kategori</h5>
                    <div style="max-width: 300px; margin: 0 auto;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-4">Tips Hari Ini 💡</h5>
                    <ul class="small text-secondary">
                        <li class="mb-2">Pastikan stok bunga segar (Fresh Flower) selalu diperbarui setiap pagi.</li>
                        <li class="mb-2">Gunakan fitur "Cetak Nota" untuk mempermudah packing kurir.</li>
                        <li class="mb-2">Balas ulasan pelanggan di WhatsApp untuk menjaga loyalitas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="section-inventory" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Inventori</h2>
            <div class="d-flex gap-2">
                <input type="text" id="adminSearchInput" class="form-control rounded-pill px-3" placeholder="Cari barang..." onkeyup="filterAdminInventory()">
                <button class="btn btn-danger rounded-pill px-4" onclick="openCreateModal()">+ Tambah</button>
            </div>
        </div>
        <ul class="nav nav-tabs mb-4" id="inventoryTabs">
            <li class="nav-item"><a class="nav-link active" href="#" onclick="switchInvTab('bunga')">🌻 Bunga</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="switchInvTab('kertas')">📜 Kertas</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="switchInvTab('aksesoris')">🎀 Aksesoris</a></li>
        </ul>
        <div class="card p-4 shadow-sm" style="border-radius: 20px;">
            <table class="table table-hover align-middle">
                <thead><tr><th>Gambar</th><th>Nama</th><th>Warna</th><th>Gender</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
                <tbody id="flower-list"></tbody>
            </table>
        </div>
    </div>

    <div id="section-orders" style="display: none;">
        <h2 class="fw-bold mb-4">Kelola Pesanan</h2>
        <div class="card p-4 shadow-sm" style="border-radius: 20px;">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Item</th>
                        <th>Detail Custom</th>
                        <th>Total</th>
                        <th>Status Saat Ini</th>
                        <th>Ubah Status</th>
                    </tr>
                </thead>
                <tbody id="order-list"></tbody>
            </table>
        </div>
    </div>

    <div id="section-reviews" style="display: none;">
        <h2 class="fw-bold mb-4">Ulasan Pelanggan ⭐</h2>
        <div id="review-list" class="row g-4"></div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalBunga" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">Form Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body p-4">
                <form id="flowerForm" enctype="multipart/form-data">
                    <input type="hidden" id="flower-id">
                    <div class="mb-3"><label class="form-label small fw-bold">Foto Produk</label><input type="file" id="image" name="image" class="form-control" accept="image/*"></div>
                    <div class="mb-3"><label class="form-label small fw-bold">Nama Item</label><input type="text" id="flower_name" name="flower_name" class="form-control" required></div>
                    <div id="flower-specific-fields">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label small fw-bold">Warna Tersedia</label>
                                <div class="d-flex flex-wrap gap-2 mb-2" id="color-checklist">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Putih" id="c-putih"><label class="form-check-label small" for="c-putih">Putih</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Hitam" id="c-hitam"><label class="form-check-label small" for="c-hitam">Hitam</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Orange" id="c-orange"><label class="form-check-label small" for="c-orange">Orange</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Pink" id="c-pink"><label class="form-check-label small" for="c-pink">Pink</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Ungu" id="c-ungu"><label class="form-check-label small" for="c-ungu">Ungu</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Brown" id="c-brown"><label class="form-check-label small" for="c-brown">Brown</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Abu" id="c-abu"><label class="form-check-label small" for="c-abu">Abu</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="Other" id="c-other" onchange="toggleOtherColor()"><label class="form-check-label small" for="c-other">Other</label></div>
                                </div>
                                <input type="text" id="other_color" class="form-control form-control-sm mt-1" placeholder="Ketik warna lain..." style="display:none;">
                                <input type="hidden" id="color" name="color">
                            </div>
                            <div class="col-md-12 mb-3"><label class="form-label small fw-bold">Gender</label><select id="target_gender" name="target_gender" class="form-select"><option value="General">General</option><option value="Female">Female</option><option value="Male">Male</option></select></div>
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-label small fw-bold">Kategori</label><select id="category" name="category" class="form-select" required></select></div>
                    <div class="row"><div class="col-md-6 mb-3"><label class="form-label small fw-bold">Harga</label><input type="text" id="price" name="price" class="form-control" placeholder="Contoh: 10000" required></div><div class="col-md-6 mb-3"><label class="form-label small fw-bold">Stok</label><input type="number" id="stock" name="stock" class="form-control" required></div></div>
                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ACCESS_TOKEN = localStorage.getItem('access_token');
    let currentInvTab = 'bunga';
    const flowerModal = new bootstrap.Modal(document.getElementById('modalBunga'));

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    async function authFetch(url, options = {}) {
        options.headers = { ...options.headers, 'Authorization': `Bearer ${ACCESS_TOKEN}`, 'Accept': 'application/json' };
        const response = await fetch(url, options);
        if (response.status === 401) { localStorage.clear(); window.location.href = '/login'; }
        return response;
    }

    function switchMainTab(tab) {
        document.getElementById('section-dashboard').style.display = tab === 'dashboard' ? 'block' : 'none';
        document.getElementById('section-inventory').style.display = tab === 'inventory' ? 'block' : 'none';
        document.getElementById('section-orders').style.display = tab === 'orders' ? 'block' : 'none';
        document.getElementById('section-reviews').style.display = tab === 'reviews' ? 'block' : 'none';
        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
        document.getElementById('menu-' + tab).classList.add('active');
        if (tab === 'dashboard') loadDashboardStats();
        if (tab === 'inventory') loadFlowers();
        if (tab === 'orders') loadOrders();
        if (tab === 'reviews') loadReviews();
    }

    async function loadReviews() {
        const res = await authFetch('/api/reviews');
        const result = await res.json();
        const data = result.data || [];
        document.getElementById('new-review-badge').style.display = data.length > 0 ? 'inline' : 'none';
        document.getElementById('new-review-badge').innerText = data.length;
        const list = document.getElementById('review-list');
        if (data.length === 0) { list.innerHTML = '<div class="col-12 text-center py-5">Belum ada ulasan.</div>'; return; }
        list.innerHTML = data.map(r => `
            <div class="col-md-6"><div class="card p-3 shadow-sm h-100"><h6 class="fw-bold text-pink" style="color:#ff758c;">${r.order?.flower?.flower_name || 'Item'}</h6><p class="small mb-0 fst-italic">"${r.comment}"</p></div></div>
        `).join('');
    }

    async function loadDashboardStats() {
        const resF = await authFetch('/api/flowers'); const resultF = await resF.json(); const flowers = resultF.data || [];
        const resO = await authFetch('/api/orders'); const resultO = await resO.json(); const orders = resultO.data || [];
        const totalRevenue = orders.filter(o => o.status === 'Selesai').reduce((sum, o) => sum + parseFloat(o.total_price), 0);
        document.getElementById('stat-revenue').innerText = formatRupiah(totalRevenue);
        document.getElementById('stat-orders').innerText = orders.filter(o => o.status === 'Selesai').length;
        document.getElementById('stat-products').innerText = flowers.length;
        const lowStock = flowers.filter(f => f.stock < 5);
        document.getElementById('low-stock-alert').style.display = lowStock.length > 0 ? 'block' : 'none';
        document.getElementById('low-stock-list').innerHTML = lowStock.map(f => `<div class="col-md-4 small fw-bold">${f.flower_name} (${f.stock})</div>`).join('');
    }

    function switchInvTab(tab) {
        currentInvTab = tab;
        document.querySelectorAll('#inventoryTabs .nav-link').forEach(el => el.classList.remove('active'));
        if(event && event.target) event.target.classList.add('active');
        document.getElementById('flower-specific-fields').style.display = tab === 'bunga' ? 'block' : 'none';
        updateCategoryDropdown(); 
        loadFlowers();
    }

    function updateCategoryDropdown() {
        const select = document.getElementById('category');
        if (currentInvTab === 'bunga') select.innerHTML = '<option value="Fresh Flower">Fresh Flower</option><option value="Pipe Cleaner Flower">Pipe Cleaner Flower</option><option value="Artificial Flower">Artificial Flower</option>';
        else if (currentInvTab === 'kertas') select.innerHTML = '<option value="Wrapping Paper">Wrapping Paper</option>';
        else select.innerHTML = '<option value="Accessory">Accessory</option><option value="Add-on">Add-on</option>';
    }

    let allFlowersAdmin = [];
    async function loadFlowers() {
        const res = await authFetch('/api/flowers'); 
        const result = await res.json();
        allFlowersAdmin = result.data || [];
        renderFlowers();
    }

    function renderFlowers() {
        const keyword = document.getElementById('adminSearchInput').value.toLowerCase();
        const list = document.getElementById('flower-list');
        let filtered = allFlowersAdmin;
        if (currentInvTab === 'bunga') filtered = filtered.filter(f => ['Fresh Flower', 'Pipe Cleaner Flower', 'Artificial Flower'].includes(f.category));
        else if (currentInvTab === 'kertas') filtered = filtered.filter(f => f.category === 'Wrapping Paper');
        else filtered = filtered.filter(f => ['Accessory', 'Add-on'].includes(f.category));
        if (keyword) filtered = filtered.filter(f => f.flower_name.toLowerCase().startsWith(keyword));
        if (filtered.length === 0) { list.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data ditemukan.</td></tr>`; return; }
        list.innerHTML = filtered.map(f => `
            <tr>
                <td><img src="${f.image ? '/uploads/'+f.image : 'https://via.placeholder.com/50'}" class="img-preview"></td>
                <td class="fw-bold">${f.flower_name}</td>
                <td>${f.color||'-'}</td>
                <td><span class="badge bg-secondary rounded-pill">${f.target_gender||'General'}</span></td>
                <td>${f.category}</td>
                <td class="fw-bold text-pink" style="color:#ff758c;">${formatRupiah(f.price)}</td>
                <td>${f.stock}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2 me-1" onclick="window.duplicateFlower(${f.id})">Copy</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2 me-1" onclick="window.openEditModal(${f.id})">Edit</button>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" onclick="window.deleteFlower(${f.id})">Hapus</button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function filterAdminInventory() { renderFlowers(); }

    document.getElementById('flowerForm').addEventListener('submit', async (e) => {
        e.preventDefault(); 
        const id = document.getElementById('flower-id').value; 
        const selectedColors = getSelectedColors();
        document.getElementById('color').value = JSON.stringify(selectedColors);
        const formData = new FormData(e.target);
        let url = '/api/flowers';
        if (id) { url = `/api/flowers/${id}`; formData.append('_method', 'PUT'); }
        try {
            const res = await authFetch(url, { method: 'POST', body: formData });
            if (res.ok) { 
                flowerModal.hide(); 
                loadFlowers(); 
                Swal.fire({ title: 'Berhasil!', icon: 'success', showConfirmButton: false, timer: 1500 }); 
            } else {
                Swal.fire('Gagal Simpan', 'Cek kembali inputanmu.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Gagal menghubungi server.', 'error');
        }
    });

    function toggleOtherColor() {
        const isOther = document.getElementById('c-other').checked;
        document.getElementById('other_color').style.display = isOther ? 'block' : 'none';
    }

    function getSelectedColors() {
        const colors = [];
        document.querySelectorAll('#color-checklist input[type="checkbox"]:checked').forEach(cb => { 
            if (cb.value !== 'Other') colors.push(cb.value); 
        });
        const otherEl = document.getElementById('other_color');
        const otherChecked = document.getElementById('c-other').checked;
        if (otherChecked && otherEl && otherEl.value.trim()) {
            colors.push(otherEl.value.trim());
        }
        return colors;
    }

    async function loadOrders() {
        const res = await authFetch('/api/orders'); 
        const result = await res.json(); 
        const data = result.data || [];
        document.getElementById('new-order-badge').style.display = data.filter(o => o.status === 'Pending').length > 0 ? 'inline' : 'none';
        document.getElementById('new-order-badge').innerText = data.filter(o => o.status === 'Pending').length;
        document.getElementById('order-list').innerHTML = data.map(o => `
            <tr>
                <td>${new Date(o.created_at).toLocaleString('id-ID')}</td>
                <td><div class="fw-bold">${o.flower?.flower_name||'Item'}</div><div class="small text-muted">Warna: ${o.selected_color || '-'} | Qty: ${o.quantity}</div></td>
                <td class="small">${o.order_type}<br>${o.paper_name ? 'Kertas: '+o.paper_name : ''}</td>
                <td class="fw-bold text-pink" style="color:#ff758c;">${formatRupiah(o.total_price)}</td>
                <td><span class="badge-status bg-${o.status.toLowerCase()}">${o.status}</span></td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 me-1" onclick="window.printOrder(${o.id})">Cetak</button>
                        <button class="btn btn-sm btn-outline-warning rounded-pill px-2 me-1" onclick="updateOrderStatus(${o.id}, 'Packing')">Pack</button>
                        <button class="btn btn-sm btn-outline-info rounded-pill px-2 me-1" onclick="updateOrderStatus(${o.id}, 'Delivered')">Kirim</button>
                        <button class="btn btn-sm btn-outline-success rounded-pill px-2" onclick="updateOrderStatus(${o.id}, 'Selesai')">Done</button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    window.printOrder = async function(id) {
        const res = await authFetch('/api/orders');
        const result = await res.json();
        const o = result.data.find(order => order.id === id);
        if (!o) return;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`<html><head><title>Nota #${o.id}</title><style>body { font-family: sans-serif; padding: 20px; }.header { text-align: center; border-bottom: 2px dashed #ff758c; }.total { font-weight: bold; font-size: 1.2em; color: #ff758c; }</style></head><body><div class="header"><h2>🌸 Bloom Florist</h2><p>Nota #${o.id}</p></div><p>Waktu: ${new Date(o.created_at).toLocaleString('id-ID')}</p><p>Item: ${o.flower?.flower_name}</p><p>Warna: ${o.selected_color || '-'}</p><p>Tipe: ${o.order_type}</p><p class="total">Total: ${formatRupiah(o.total_price)}</p><script>window.onload=function(){window.print();window.close();}<\/script></body></html>`);
        printWindow.document.close();
    }

    window.updateOrderStatus = async function(id, status) { 
        try {
            const res = await authFetch(`/api/orders/${id}/status`, { method: 'PUT', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({ status }) }); 
            if (res.ok) { loadOrders(); Swal.fire({ title: 'Status Updated', icon: 'success', timer: 1000 }); }
        } catch (e) { console.error(e); }
    }

    window.deleteFlower = async function(id) { 
        Swal.fire({ title: 'Hapus?', icon: 'warning', showCancelButton: true }).then(async (result) => {
            if (result.isConfirmed) { await authFetch(`/api/flowers/${id}`, { method: 'DELETE' }); loadFlowers(); }
        });
    }

    function setupColorChecklist(colorData) {
        let colors = [];
        try { colors = typeof colorData === 'string' ? JSON.parse(colorData) : (colorData || []); } catch(e) { colors = []; }
        const presetColors = ['Putih', 'Hitam', 'Orange', 'Pink', 'Ungu', 'Brown', 'Abu'];
        document.querySelectorAll('#color-checklist input[type="checkbox"]').forEach(cb => cb.checked = false);
        let otherColors = [];
        colors.forEach(c => {
            if (presetColors.includes(c)) { 
                const cb = document.querySelector(`#color-checklist input[value="${c}"]`); 
                if (cb) cb.checked = true; 
            } else { otherColors.push(c); }
        });
        if (otherColors.length > 0) {
            document.getElementById('c-other').checked = true;
            document.getElementById('other_color').value = otherColors.join(', ');
            document.getElementById('other_color').style.display = 'block';
        } else {
            document.getElementById('other_color').style.display = 'none';
        }
    }

    window.duplicateFlower = async function(id) {
        const res = await authFetch(`/api/flowers/${id}`); 
        const result = await res.json(); 
        const f = result.data;
        document.getElementById('flowerForm').reset();
        document.getElementById('flower-id').value = '';
        document.getElementById('flower_name').value = f.flower_name;
        document.getElementById('price').value = f.price;
        document.getElementById('stock').value = f.stock;
        document.getElementById('target_gender').value = f.target_gender || 'General';
        updateCategoryDropdown(); 
        document.getElementById('category').value = f.category;
        setupColorChecklist(f.color);
        flowerModal.show();
    }

    window.openEditModal = async function(id) {
        const res = await authFetch(`/api/flowers/${id}`); 
        const result = await res.json(); 
        const f = result.data;
        document.getElementById('flower-id').value = f.id; 
        document.getElementById('flower_name').value = f.flower_name;
        document.getElementById('price').value = f.price; 
        document.getElementById('stock').value = f.stock;
        document.getElementById('target_gender').value = f.target_gender || 'General';
        updateCategoryDropdown(); 
        document.getElementById('category').value = f.category;
        setupColorChecklist(f.color);
        flowerModal.show();
    }

    window.openCreateModal = function() { 
        document.getElementById('flowerForm').reset(); 
        document.getElementById('flower-id').value = ''; 
        document.getElementById('other_color').style.display = 'none';
        document.querySelectorAll('#color-checklist input[type="checkbox"]').forEach(cb => cb.checked = false);
        updateCategoryDropdown(); 
        flowerModal.show(); 
    }

    async function logout() { localStorage.clear(); window.location.href = '/login'; }

    switchMainTab('dashboard');
</script>
</body>
</html>