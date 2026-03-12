<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom Florist - Katalog Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fdf2f4; font-family: 'Poppins', sans-serif; }
        .hero { background: linear-gradient(rgba(255,117,140,0.6), rgba(255,117,140,0.6)), url('https://images.unsplash.com/photo-1526047932273-341f2a7631f9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); height: 250px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; color: white; border-radius: 0 0 50px 50px; }
        .nav-link { font-weight: 600; color: #ff758c !important; }
        .nav-pills .nav-link.active { background-color: #ff758c; color: white !important; }
        .flower-card { border: none; border-radius: 25px; overflow: hidden; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: white; position: relative; }
        .flower-card:hover { transform: translateY(-10px); }
        .card-img-top { height: 220px; object-fit: cover; }
        .price-tag { color: #ff758c; font-weight: 600; font-size: 1.1rem; }
        .search-box { border-radius: 50px; padding: 12px 25px; border: 2px solid #ff758c; width: 100%; max-width: 400px; outline: none; }
        .step-card { border: 2px solid #eee; border-radius: 15px; padding: 10px; cursor: pointer; text-align: center; transition: 0.2s; }
        .step-card.active { border-color: #ff758c; background: #fff5f7; color: #ff758c; }
        .out-of-stock { filter: grayscale(0.8); opacity: 0.7; }
        .sold-out-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 220px; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; z-index: 5; }
        .sold-out-badge { background: #ff4757; color: white; padding: 5px 20px; border-radius: 50px; font-weight: bold; font-size: 0.8rem; transform: rotate(-10deg); box-shadow: 0 5px 15px rgba(255,71,87,0.4); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="/" style="color:#ff758c;">🌸 Bloom Florist</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="/">Katalog</a></li>
                <li class="nav-item"><a class="nav-link" href="/pesanan">Pesanan Saya</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="/keranjang">🛒 Keranjang <span id="cart-count-nav" class="badge bg-danger rounded-pill">0</span></a></li>
                <li class="nav-item ms-lg-3">
                    <a class="nav-link btn btn-outline-danger rounded-pill px-4" href="/login" style="color:#ff758c !important;">Login Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero">
    <div class="text-center w-100">
        <h1 class="display-6 fw-bold">Mau Bunga Apa Hari Ini?</h1>
        <div class="mt-3 d-flex justify-content-center">
            <input type="text" id="searchInput" class="search-box shadow-sm" placeholder="Cari bunga..." onkeyup="filterFlowers()">
        </div>
    </div>
</div>

<div class="container my-5">
    <!-- TABS KATEGORI (Fresh, Artificial, Pipe Cleaner) -->
    <ul class="nav nav-pills justify-content-center mb-5 gap-2" id="categoryTabs">
        <li class="nav-item"><a class="nav-link active rounded-pill px-4" href="#" onclick="switchCategory('Fresh Flower', this)">Fresh Flower</a></li>
        <li class="nav-item"><a class="nav-link rounded-pill px-4" href="#" onclick="switchCategory('Artificial Flower', this)">Artificial Flower</a></li>
        <li class="nav-item"><a class="nav-link rounded-pill px-4" href="#" onclick="switchCategory('Pipe Cleaner Flower', this)">Pipe Cleaner</a></li>
    </ul>

    <div id="flower-catalog" class="row">
        <!-- Data Bunga dimuat via JS -->
    </div>

    <!-- SECTION TESTIMONI (NEW) -->
    <div class="mt-5 pt-5 border-top">
        <h3 class="fw-bold text-center mb-4" style="color: #ff758c;">Apa Kata Pelanggan Kami? ⭐</h3>
        <div id="testimonial-container" class="row g-3">
            <!-- Ulasan dimuat di sini -->
        </div>
    </div>
</div>

<!-- Modal Pemesanan -->
<div class="modal fade" id="modalOrder" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 30px;">
            <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">Pesan Bunga Cantik</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Preview Produk -->
                    <div class="col-md-4 text-center border-end">
                        <img id="m-img" src="" class="img-fluid rounded-4 mb-3 shadow-sm">
                        <h4 id="m-name" class="fw-bold text-pink" style="color:#ff758c;">Bunga</h4>
                        <p id="m-price" class="text-muted fw-bold">Rp 0</p>
                    </div>
                    
                    <!-- Pilihan Logic -->
                    <div class="col-md-8 px-4">
                        <div class="mb-4">
                            <label class="fw-bold mb-2">1. Pilih Warna:</label>
                            <select id="m-selected-color" class="form-select rounded-pill px-3" onchange="updateTotal()"></select>
                        </div>

                        <label class="fw-bold mb-2">2. Pilih Tipe Pesanan:</label>
                        <div class="row g-2 mb-4">
                            <div class="col-6"><div class="step-card" id="opt-satuan" onclick="selectType('Satuan')"><b>Satuan</b><br><small>Bunga saja</small></div></div>
                            <div class="col-6"><div class="step-card" id="opt-buket" onclick="selectType('Buket')"><b>Buket</b><br><small>Rangkaian Cantik</small></div></div>
                        </div>

                        <div id="buket-options" style="display: none;">
                            <label class="fw-bold mb-2 small text-muted">3. Pilih Tambahan (Opsional):</label>
                            <div class="mb-3">
                                <label class="small text-muted d-block mb-1">Pilihan Kertas:</label>
                                <div class="row g-2" id="m-papers"></div>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted d-block mb-1">Aksesoris/Boneka:</label>
                                <div class="row g-2" id="m-addons"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2">4. Jumlah Pesanan (pcs):</label>
                            <input type="number" id="m-quantity" class="form-control rounded-pill px-3" value="1" min="1" onchange="updateTotal()">
                        </div>

                        <label class="fw-bold mb-2">5. Metode Pembayaran:</label>
                        <div class="row g-2 mb-4">
                            <div class="col-4"><div class="step-card py-2 payment-opt" onclick="selectPayment('GoPay', this)">GoPay</div></div>
                            <div class="col-4"><div class="step-card py-2 payment-opt" onclick="selectPayment('OVO', this)">OVO</div></div>
                            <div class="col-4"><div class="step-card py-2 payment-opt" onclick="selectPayment('Transfer', this)">Bank</div></div>
                        </div>

                        <div class="bg-light p-3 rounded-4 d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-secondary">Total:</span>
                            <span id="m-total" class="fs-4 fw-bold text-pink" style="color:#ff758c;">Rp 0</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn btn-outline-danger w-100 rounded-pill py-3 fw-bold" onclick="addToCart()">🛒 + Keranjang</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-danger w-100 rounded-pill py-3 fw-bold shadow" onclick="submitOrder()">Bayar Sekarang 🌸</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let allFlowers = [];
    let currentCategory = 'Fresh Flower';
    let currentOrder = { flower: null, type: 'Satuan', paper: null, addon: null, payment: null, total: 0, quantity: 1 };
    const orderModal = new bootstrap.Modal(document.getElementById('modalOrder'));

    async function loadData() {
        const res = await fetch('/api/flowers');
        const result = await res.json();
        allFlowers = result.data; // Ambil dari key data
        renderCatalog();
        loadPublicReviews();
    }

    async function loadPublicReviews() {
        const res = await fetch('/api/reviews');
        const result = await res.json();
        const data = result.data; // Ambil dari key data
        const container = document.getElementById('testimonial-container');

        // Tampilkan 3 ulasan terbaru yang ratingnya 4 atau 5
        const bestReviews = data.filter(r => r.rating >= 4).slice(0, 3);

        if (bestReviews.length === 0) {
            container.innerHTML = '<p class="text-center text-muted small">Jadilah pelanggan pertama yang memberikan ulasan! 🌸</p>';
            return;
        }

        container.innerHTML = bestReviews.map(r => `
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:20px; background:#fff;">
                    <div class="text-warning mb-2">${'★'.repeat(r.rating)}</div>
                    <p class="small fst-italic mb-3">"${r.comment}"</p>
                    <div class="d-flex align-items-center mt-auto">
                        <div class="bg-light rounded-circle p-2 me-2">🌸</div>
                        <div>
                            <div class="fw-bold small">${r.order?.flower?.flower_name || 'Pelanggan'}</div>
                            <div class="text-muted" style="font-size:10px;">${new Date(r.created_at).toLocaleDateString('id-ID')}</div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function switchCategory(cat, el) {
        currentCategory = cat;
        document.querySelectorAll('#categoryTabs .nav-link').forEach(n => n.classList.remove('active'));
        el.classList.add('active');
        renderCatalog();
    }

    function renderCatalog() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        
        // Filter Berdasarkan Kategori yang Sedang Aktif DAN Keyword Pencarian
        const filtered = allFlowers.filter(f => 
            f.category === currentCategory && 
            f.flower_name.toLowerCase().includes(keyword)
        );
        
        const catalog = document.getElementById('flower-catalog');
        
        if (filtered.length === 0) {
            catalog.innerHTML = `
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Bunga dengan nama "${keyword}" tidak ditemukan di kategori ini.</p>
                </div>`;
            return;
        }

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        catalog.innerHTML = filtered.map(f => {
            const isHabis = f.stock <= 0;
            let colors = [];
            try {
                colors = typeof f.color === 'string' ? JSON.parse(f.color) : (f.color || []);
            } catch(e) { colors = []; }
            
            return `
            <div class="col-md-3 mb-4">
                <div class="card flower-card h-100 text-center ${isHabis ? 'out-of-stock' : ''}">
                    ${isHabis ? `
                        <div class="sold-out-overlay">
                            <div class="sold-out-badge">SOLD OUT</div>
                        </div>
                    ` : ''}
                    <img src="${f.image ? '/uploads/'+f.image : 'https://via.placeholder.com/300'}" class="card-img-top">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-1">${f.flower_name}</h6>
                        <div class="small text-muted mb-2">${colors.length > 0 ? 'Warna: '+colors.join(', ') : ''} ${f.target_gender && f.target_gender !== 'General' ? '| Untuk: '+f.target_gender : ''}</div>
                        <div class="price-tag mb-3">${formatRupiah(f.price)}</div>
                        <button class="btn ${isHabis ? 'btn-secondary' : 'btn-danger'} btn-sm w-100 rounded-pill" 
                            onclick="${isHabis ? '' : `openOrder(${f.id})`}" ${isHabis ? 'disabled' : ''}>
                            ${isHabis ? 'Stok Habis' : 'Pesan'}
                        </button>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    function filterFlowers() { 
        renderCatalog(); 
    }

    function openOrder(id) {
        const flower = allFlowers.find(f => f.id === id);
        currentOrder = { flower, type: 'Satuan', paper: null, addon: null, payment: null, total: parseInt(flower.price), quantity: 1 };
        
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        document.getElementById('m-name').innerText = flower.flower_name;
        document.getElementById('m-price').innerText = formatRupiah(flower.price);
        document.getElementById('m-img').src = flower.image ? `/uploads/${flower.image}` : 'https://via.placeholder.com/300';
        document.getElementById('m-quantity').value = 1;
        
        // Populate Colors
        const colorSelect = document.getElementById('m-selected-color');
        let colors = [];
        try {
            colors = typeof flower.color === 'string' ? JSON.parse(flower.color) : (flower.color || []);
        } catch(e) { colors = []; }
        
        if (colors.length > 0) {
            colorSelect.innerHTML = colors.map(c => `<option value="${c}">${c}</option>`).join('');
            colorSelect.disabled = false;
        } else {
            colorSelect.innerHTML = '<option value="-">Tanpa Pilihan Warna</option>';
            colorSelect.disabled = true;
        }

        // Reset UI
        document.getElementById('buket-options').style.display = 'none';
        document.querySelectorAll('.step-card').forEach(c => c.classList.remove('active'));
        document.getElementById('opt-satuan').classList.add('active');
        
        loadCustomizer();
        updateTotal();
        orderModal.show();
    }

    function selectType(type) {
        currentOrder.type = type;
        document.getElementById('opt-satuan').classList.toggle('active', type === 'Satuan');
        document.getElementById('opt-buket').classList.toggle('active', type === 'Buket');
        document.getElementById('buket-options').style.display = type === 'Buket' ? 'block' : 'none';
        if(type === 'Satuan') { currentOrder.paper = null; currentOrder.addon = null; }
        updateTotal();
    }

    function loadCustomizer() {
        const papers = allFlowers.filter(f => f.category === 'Wrapping Paper');
        const addons = allFlowers.filter(f => ['Accessory', 'Add-on'].includes(f.category));

        document.getElementById('m-papers').innerHTML = papers.map(p => `
            <div class="col-4"><div class="step-card py-2 small" onclick="selectSub(this, 'paper', '${p.flower_name}', ${p.price})">${p.flower_name}</div></div>
        `).join('');

        document.getElementById('m-addons').innerHTML = addons.map(a => `
            <div class="col-4"><div class="step-card py-2 small" onclick="selectSub(this, 'addon', '${a.flower_name}', ${a.price})">${a.flower_name}</div></div>
        `).join('');
    }

    function selectSub(el, key, name, price) {
        document.querySelectorAll(`#m-${key}s .step-card`).forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        currentOrder[key] = { name, price: parseInt(price) };
        updateTotal();
    }

    function selectPayment(p, el) {
        document.querySelectorAll('.payment-opt').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        currentOrder.payment = p;
    }

    function updateTotal() {
        const qty = parseInt(document.getElementById('m-quantity').value) || 1;
        currentOrder.quantity = qty;

        let total = currentOrder.flower.price * qty;
        if(currentOrder.type === 'Buket') {
            if(currentOrder.paper) total += currentOrder.paper.price;
            if(currentOrder.addon) total += currentOrder.addon.price;
        }
        currentOrder.total = total;
        
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };
        
        document.getElementById('m-total').innerText = formatRupiah(total);
    }

    function addToCart() {
        const selectedColor = document.getElementById('m-selected-color').value;
        const item = {
            flower_id: currentOrder.flower.id,
            flower_name: currentOrder.flower.flower_name,
            flower_image: currentOrder.flower.image,
            flower_price: currentOrder.flower.price,
            order_type: currentOrder.type,
            paper_name: currentOrder.paper?.name || null,
            paper_price: currentOrder.paper?.price || 0,
            accessory_name: currentOrder.addon?.name || null,
            addon_price: currentOrder.addon?.price || 0,
            quantity: currentOrder.quantity,
            selected_color: selectedColor === '-' ? null : selectedColor,
            total_price: currentOrder.total,
            selected: true
        };

        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart.push(item);
        localStorage.setItem('cart', JSON.stringify(cart));
        
        updateCartCount();
        orderModal.hide();
        
        Swal.fire({
            title: 'Berhasil! 🛒',
            text: 'Bunga cantikmu sudah masuk keranjang.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Lihat Keranjang',
            cancelButtonText: 'Belanja Lagi',
            confirmButtonColor: '#ff758c'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/keranjang';
            }
        });
    }

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const badge = document.getElementById('cart-count-nav');
        if(badge) badge.innerText = cart.length;
    }

    async function submitOrder() {
        if(!currentOrder.payment) return Swal.fire('Oops', 'Pilih pembayaran dulu ya!', 'warning');
        
        const user = JSON.parse(localStorage.getItem('user'));
        const token = localStorage.getItem('access_token');
        const selectedColor = document.getElementById('m-selected-color').value;

        const data = {
            user_id: user ? user.id : null,
            flower_id: currentOrder.flower.id,
            order_type: currentOrder.type,
            paper_name: currentOrder.paper?.name || null,
            accessory_name: currentOrder.addon?.name || null,
            quantity: currentOrder.quantity,
            selected_color: selectedColor === '-' ? null : selectedColor,
            total_price: currentOrder.total,
            payment_method: currentOrder.payment,
            status: 'Pending'
        };

        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
        if (token) headers['Authorization'] = `Bearer ${token}`;

        try {
            const res = await fetch('/api/orders', { 
                method: 'POST', 
                headers: headers, 
                body: JSON.stringify(data) 
            });

            const result = await res.json();

            if(res.ok && result.data) {
                // SIMPAN ID PESANAN KE BROWSER
                let mine = JSON.parse(localStorage.getItem('my_orders') || '[]');
                mine.push(result.data.id); 
                localStorage.setItem('my_orders', JSON.stringify(mine));
                
                orderModal.hide();
                Swal.fire('Pembayaran Berhasil! 🌸', 'Pesanan kamu sudah masuk di Admin. Kamu akan diarahkan ke halaman lacak pesanan.', 'success')
                .then(() => {
                    window.location.href = '/pesanan'; 
                });
            } else {
                Swal.fire('Gagal', result.message || 'Terjadi kesalahan sistem.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Gagal menghubungi server.', 'error');
        }
    }

    loadData();
</script>
</body>
</html>
