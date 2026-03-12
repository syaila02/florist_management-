<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Bloom Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fff9fa; font-family: 'Poppins', sans-serif; color: #444; }
        .navbar { background: white !important; border-bottom: 2px solid #ffeef0; }
        .card { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(255,117,140,0.1); }
        .btn-danger { background: #ff758c; border: none; transition: 0.3s; }
        .btn-danger:hover { background: #ff5e7a; transform: translateY(-2px); }
        .btn-outline-danger { color: #ff758c; border-color: #ff758c; }
        .btn-outline-danger:hover { background: #ff758c; color: white; }
        .table-cart img { width: 80px; height: 80px; object-fit: cover; border-radius: 15px; }
        .qty-input { width: 60px; text-align: center; border-radius: 10px; border: 1px solid #ddd; }
        .price-tag { color: #ff758c; font-weight: 600; }
        .cart-empty-img { width: 200px; opacity: 0.5; margin-bottom: 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light py-3 sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="/" style="color:#ff758c;">🌸 Bloom Florist</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="/">Katalog</a></li>
                <li class="nav-item"><a class="nav-link" href="/pesanan">Pesanan Saya</a></li>
                <li class="nav-item"><a class="nav-link active fw-bold" href="/keranjang">🛒 Keranjang <span id="cart-count-nav" class="badge bg-danger rounded-pill">0</span></a></li>
                <li class="nav-item ms-lg-3"><a class="nav-link btn btn-outline-danger rounded-pill px-4" href="/login">Login Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Keranjang Belanja Kamu 🛒</h2>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 h-100">
                <div id="cart-items-container">
                    <!-- Items loading... -->
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Produk:</span>
                    <span id="summary-total-qty">0 Item</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total Harga:</span>
                    <span id="summary-total-price" class="fw-bold fs-5 text-danger">Rp 0</span>
                </div>
                <hr>
                <div class="mb-4">
                    <label class="fw-bold mb-2 small">Metode Pembayaran (Semua):</label>
                    <div class="d-flex gap-2 flex-wrap" id="payment-options">
                        <button class="btn btn-sm btn-outline-secondary px-3 rounded-pill pay-opt" onclick="selectPayment('QRIS', this)">QRIS</button>
                        <button class="btn btn-sm btn-outline-secondary px-3 rounded-pill pay-opt" onclick="selectPayment('Transfer Bank', this)">Transfer Bank</button>
                        <button class="btn btn-sm btn-outline-secondary px-3 rounded-pill pay-opt" onclick="selectPayment('COD', this)">COD</button>
                    </div>
                </div>
                <button class="btn btn-danger w-100 rounded-pill py-3 fw-bold" onclick="checkoutAll()">Selesaikan Semua Pesanan</button>
                <a href="/" class="btn btn-outline-danger w-100 rounded-pill py-2 mt-2 small">Tambah Bunga Lagi</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart = JSON.parse(localStorage.getItem('cart') || '[]').map(item => {
        if (item.selected === undefined) item.selected = true;
        return item;
    });
    let selectedPayment = null;

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function updateCartUI() {
        const container = document.getElementById('cart-items-container');
        const countNav = document.getElementById('cart-count-nav');
        countNav.innerText = cart.length;

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" class="cart-empty-img">
                    <h5 class="fw-bold">Keranjangmu masih kosong hiks..</h5>
                    <p class="text-muted">Yuk cari bunga cantik buat orang tersayang!</p>
                    <a href="/" class="btn btn-danger rounded-pill px-4">Lihat Katalog</a>
                </div>`;
            document.getElementById('summary-total-qty').innerText = '0 Item';
            document.getElementById('summary-total-price').innerText = 'Rp 0';
            return;
        }

        const allChecked = cart.length > 0 && cart.every(item => item.selected);
        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="select-all" onchange="toggleAll(this)" ${allChecked ? 'checked' : ''}>
                    <label class="form-check-label small fw-bold" for="select-all">Pilih Semua</label>
                </div>
                <button class="btn btn-sm text-danger small" onclick="removeSelected()">Hapus Terpilih</button>
            </div>
            <table class="table align-middle table-cart">
                <thead><tr><th style="width:40px;"></th><th>Produk</th><th>Detail</th><th>Harga</th><th>Jumlah</th><th>Hapus</th></tr></thead>
                <tbody>
                    ${cart.map((item, index) => {
                        return `
                        <tr>
                            <td>
                                <input class="form-check-input item-checkbox" type="checkbox" 
                                    ${item.selected ? 'checked' : ''} onchange="toggleItem(${index}, this.checked)">
                            </td>
                            <td><img src="${item.flower_image ? '/uploads/'+item.flower_image : 'https://via.placeholder.com/100'}"></td>
                            <td>
                                <div class="fw-bold">${item.flower_name}</div>
                                <div class="small text-muted">Warna: ${item.selected_color || '-'}</div>
                                <div class="small text-muted">${item.paper_name ? 'Kertas: '+item.paper_name : ''}</div>
                            </td>
                            <td class="price-tag">${formatRupiah(item.total_price)}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-sm btn-outline-secondary rounded-circle" style="width:25px;height:25px;padding:0;" onclick="changeQty(${index}, -1)">-</button>
                                    <span class="fw-bold">${item.quantity}</span>
                                    <button class="btn btn-sm btn-outline-secondary rounded-circle" style="width:25px;height:25px;padding:0;" onclick="changeQty(${index}, 1)">+</button>
                                </div>
                            </td>
                            <td><button class="btn btn-sm text-danger" onclick="removeItem(${index})">❌</button></td>
                        </tr>`;
                    }).join('')}
                </tbody>
            </table>`;

        calculateSummary();
    }

    function toggleAll(el) {
        cart.forEach(item => item.selected = el.checked);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
    }

    function toggleItem(index, isChecked) {
        cart[index].selected = isChecked;
        localStorage.setItem('cart', JSON.stringify(cart));
        calculateSummary();
        
        // Update Select All checkbox state
        const allChecked = cart.every(item => item.selected);
        document.getElementById('select-all').checked = allChecked;
    }

    function calculateSummary() {
        let selectedItems = cart.filter(item => item.selected);
        let totalQty = selectedItems.reduce((sum, item) => sum + item.quantity, 0);
        let totalPrice = selectedItems.reduce((sum, item) => sum + item.total_price, 0);

        document.getElementById('summary-total-qty').innerText = totalQty + ' Produk';
        document.getElementById('summary-total-price').innerText = formatRupiah(totalPrice);
        
        const checkoutBtn = document.querySelector('button[onclick="checkoutAll()"]');
        if (selectedItems.length > 0) {
            checkoutBtn.innerText = `Bayar Pesanan Terpilih (${selectedItems.length})`;
            checkoutBtn.disabled = false;
        } else {
            checkoutBtn.innerText = `Pilih Item Terlebih Dahulu`;
            checkoutBtn.disabled = true;
        }
    }

    function selectPayment(method, el) {
        // Reset semua tombol pembayaran
        document.querySelectorAll('.pay-opt').forEach(btn => {
            btn.classList.remove('btn-danger', 'text-white');
            btn.classList.add('btn-outline-secondary');
        });
        // Aktifkan yang dipilih
        el.classList.remove('btn-outline-secondary');
        el.classList.add('btn-danger', 'text-white');
        selectedPayment = method;
    }

    function changeQty(index, delta) {
        if (cart[index].quantity + delta < 1) return;
        
        cart[index].quantity += delta;
        // Update total harga item berdasarkan harga bunga + kertas + aksesoris
        const basePrice = parseInt(cart[index].flower_price) || 0;
        const paperPrice = parseInt(cart[index].paper_price) || 0;
        const addonPrice = parseInt(cart[index].addon_price) || 0;
        
        cart[index].total_price = (basePrice * cart[index].quantity) + paperPrice + addonPrice;
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
    }

    function removeSelected() {
        cart = cart.filter(item => !item.selected);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
    }

    async function checkoutAll() {
        const selectedItems = cart.filter(item => item.selected);
        if (selectedItems.length === 0) return Swal.fire('Oops', 'Pilih barang yang mau dibeli dulu ya!', 'warning');
        if (!selectedPayment) return Swal.fire('Oops', 'Pilih metode pembayaran dulu ya!', 'warning');

        Swal.fire({
            title: 'Memproses Pesanan...',
            html: 'Sedang menyiapkan bunga cantikmu ✨',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const promises = selectedItems.map(item => {
                const data = {
                    flower_id: item.flower_id,
                    order_type: item.order_type,
                    paper_name: item.paper_name,
                    accessory_name: item.accessory_name,
                    quantity: item.quantity,
                    selected_color: item.selected_color === '-' ? null : item.selected_color,
                    total_price: item.total_price,
                    payment_method: selectedPayment,
                    status: 'Pending'
                };
                return fetch('/api/orders', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                }).then(res => res.json());
            });

            const results = await Promise.all(promises);
            const successfulIds = results.filter(r => r.status === 'success').map(r => r.data.id);

            if (successfulIds.length > 0) {
                let mine = JSON.parse(localStorage.getItem('my_orders') || '[]');
                mine = [...mine, ...successfulIds];
                localStorage.setItem('my_orders', JSON.stringify(mine));

                // HANYA hapus barang yang SUDAH dibayar dari keranjang
                cart = cart.filter(item => !item.selected);
                localStorage.setItem('cart', JSON.stringify(cart));

                Swal.fire('Berhasil! 🌸', `Yey! ${successfulIds.length} pesanan kamu sudah masuk.`, 'success')
                .then(() => {
                    window.location.href = '/pesanan';
                });
            } else {
                Swal.fire('Gagal', 'Maaf, pesanan gagal diproses.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
        }
    }

    updateCartUI();
</script>

</body>
</html>
