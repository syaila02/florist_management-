<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Bloom Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fdf2f4; font-family: 'Poppins', sans-serif; }
        .order-card { border: none; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; margin-bottom: 20px; border-left: 8px solid #ff758c; transition: 0.3s; }
        .badge-status { padding: 8px 15px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; }
        .bg-pending { background: #fff3cd !important; color: #856404 !important; }
        .bg-packing { background: #cfe2ff !important; color: #004085 !important; }
        .bg-delivered { background: #d1e7dd !important; color: #0f5132 !important; }
        .bg-selesai { background: #e2e3e5 !important; color: #41464b !important; }
        .nav-link { font-weight: 600; color: #ff758c !important; }

        /* Progress Bar Styling */
        .progress-track { display: flex; justify-content: space-between; position: relative; margin-bottom: 30px; padding: 0 10px; }
        .progress-track::before { content: ""; position: absolute; top: 12px; left: 10px; right: 10px; height: 3px; background: #eee; z-index: 1; }
        .step { width: 25px; height: 25px; border-radius: 50%; background: #eee; z-index: 2; display: flex; align-items: center; justify-content: center; font-size: 10px; color: white; transition: 0.3s; position: relative; }
        .step.active { background: #ff758c; box-shadow: 0 0 10px rgba(255,117,140,0.5); }
        .step.active::after { content: attr(data-label); position: absolute; top: 30px; font-size: 9px; color: #ff758c; font-weight: bold; white-space: nowrap; }
        .step:not(.active)::after { content: attr(data-label); position: absolute; top: 30px; font-size: 9px; color: #bbb; white-space: nowrap; }
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
                <li class="nav-item"><a class="nav-link active" href="/pesanan">Pesanan Saya</a></li>
                <li class="nav-item ms-lg-3">
                    <a class="nav-link btn btn-outline-danger rounded-pill px-4" href="/login" style="color:#ff758c !important;">Login Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5" style="max-width: 650px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Lacak Pesanan 🌸</h2>
        <button class="btn btn-danger btn-sm rounded-pill px-4" onclick="loadMyOrders()">Perbarui</button>
    </div>
    
    <div id="my-orders-container">
        <!-- Status dimuat di sini -->
        <div class="text-center py-5"><div class="spinner-border text-danger" role="status"></div></div>
    </div>
</div>

<!-- Modal Review -->
<div class="modal fade" id="modalReview" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">Beri Ulasan 🌸</h5><button type="button" class="btn-close" data-bs-close="modal"></button></div>
            <div class="modal-body p-4">
                <input type="hidden" id="rev-order-id">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Rating:</label>
                    <div class="d-flex gap-2 justify-content-center mb-2">
                        <span class="star-rating fs-3" style="cursor:pointer; color:#ccc;" onclick="setStar(1)">★</span>
                        <span class="star-rating fs-3" style="cursor:pointer; color:#ccc;" onclick="setStar(2)">★</span>
                        <span class="star-rating fs-3" style="cursor:pointer; color:#ccc;" onclick="setStar(3)">★</span>
                        <span class="star-rating fs-3" style="cursor:pointer; color:#ccc;" onclick="setStar(4)">★</span>
                        <span class="star-rating fs-3" style="cursor:pointer; color:#ccc;" onclick="setStar(5)">★</span>
                    </div>
                    <input type="hidden" id="rev-rating" value="0">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Komentar:</label>
                    <textarea id="rev-comment" class="form-control rounded-3" rows="3" placeholder="Ceritakan pengalamanmu..."></textarea>
                </div>
                <button class="btn btn-danger w-100 rounded-pill py-2 fw-bold" onclick="submitReview()">Kirim Ulasan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const reviewModal = new bootstrap.Modal(document.getElementById('modalReview'));

    function openReviewModal(orderId) {
        document.getElementById('rev-order-id').value = orderId;
        document.getElementById('rev-rating').value = 0;
        document.getElementById('rev-comment').value = '';
        setStar(0);
        reviewModal.show();
    }

    function setStar(n) {
        document.getElementById('rev-rating').value = n;
        const stars = document.querySelectorAll('.star-rating');
        stars.forEach((s, i) => {
            s.style.color = i < n ? '#ff758c' : '#ccc';
        });
    }

    async function submitReview() {
        const orderId = document.getElementById('rev-order-id').value;
        const rating = document.getElementById('rev-rating').value;
        const comment = document.getElementById('rev-comment').value;

        if (rating == 0) return Swal.fire('Oops', 'Pilih bintang dulu ya!', 'warning');
        if (!comment) return Swal.fire('Oops', 'Berikan komentar kamu!', 'warning');

        try {
            const res = await fetch('/api/reviews', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ order_id: orderId, rating, comment })
            });

            if (res.ok) {
                reviewModal.hide();
                Swal.fire('Terima Kasih! ❤️', 'Ulasan kamu sudah terkirim ke Admin.', 'success');
                loadMyOrders();
            } else {
                const err = await res.json();
                Swal.fire('Gagal', err.message || 'Gagal mengirim ulasan.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
        }
    }
    async function loadMyOrders() {
        const container = document.getElementById('my-orders-container');
        let myOrderIds = JSON.parse(localStorage.getItem('my_orders') || '[]');

        if (myOrderIds.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 card border-0 shadow-sm" style="border-radius:25px;">
                    <h4 class="fw-bold mb-3">Belum Ada Riwayat Pesanan</h4>
                    <a href="/" class="btn btn-danger rounded-pill px-5">Pesan Bunga Sekarang</a>
                </div>`;
            return;
        }

        try {
            // Kita ambil data pesanan berdasarkan ID yang disimpan di browser saja
            const [resOrders, resFlowers] = await Promise.all([
                fetch(`/api/orders?ids=${myOrderIds.join(',')}`),
                fetch('/api/flowers')
            ]);
            
            const resultOrders = await resOrders.json();
            const resultFlowers = await resFlowers.json();

            const myOrders = resultOrders.data || [];
            const allFlowers = resultFlowers.data || [];

            if (myOrders.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 card border-0 shadow-sm" style="border-radius:25px;">
                        <h4 class="fw-bold mb-3">Pesanan Tidak Ditemukan</h4>
                        <p class="text-muted small">Mungkin pesanan sudah dihapus oleh Admin.</p>
                        <a href="/" class="btn btn-danger rounded-pill px-5">Pesan Baru</a>
                    </div>`;
                return;
            }

            // Urutkan dari yang terbaru (reverse)
            container.innerHTML = myOrders.reverse().map(o => {
                const flower = allFlowers.find(f => String(f.id) === String(o.flower_id));
                const statusLcase = (o.status || 'pending').toLowerCase();
                
                let statusMsg = "Admin sedang memverifikasi pembayaranmu...";
                if(statusLcase === 'packing') statusMsg = "Yeay! Bungamu sedang dirangkai cantik ✨";
                if(statusLcase === 'delivered') statusMsg = "Kurir sedang menuju alamatmu! 🚚";
                if(statusLcase === 'selesai') statusMsg = "Terima kasih sudah memesan di Bloom Florist! ❤️";

                const formatRupiah = (number) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                };

                return `
                <div class="card order-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold mb-0" style="color:#ff758c;">${flower ? flower.flower_name : 'Produk'}</h5>
                            <small class="text-muted">ID: #${o.id} | ${new Date(o.created_at).toLocaleDateString('id-ID')}</small>
                        </div>
                        <span class="badge-status bg-${statusLcase}">${o.status}</span>
                    </div>
                    
                    <!-- Progress Bar (Tampilan Lucu/Tracking) -->
                    <div class="progress-track my-4">
                        <div class="step ${['pending','packing','delivered','selesai'].includes(statusLcase) ? 'active' : ''}" data-label="Pending">1</div>
                        <div class="step ${['packing','delivered','selesai'].includes(statusLcase) ? 'active' : ''}" data-label="Packing">2</div>
                        <div class="step ${['delivered','selesai'].includes(statusLcase) ? 'active' : ''}" data-label="Kirim">3</div>
                        <div class="step ${statusLcase === 'selesai' ? 'active' : ''}" data-label="Selesai">4</div>
                    </div>

                    <div class="row small text-secondary mb-3">
                        <div class="col-6">Tipe: <b>${o.order_type}</b></div>
                        <div class="col-6 text-end">Total: <b class="text-danger">${formatRupiah(o.total_price)}</b></div>
                        <div class="col-12 mt-2">Warna: <b>${o.selected_color || '-'}</b></div>
                        <div class="col-12">Kertas: ${o.paper_name || '-'} | Aksesoris: ${o.accessory_name || '-'}</div>
                    </div>
                    <div class="p-2 bg-light rounded-3 small text-center fw-bold text-dark">
                        ${statusMsg}
                    </div>

                    ${statusLcase === 'selesai' ? `
                        <button class="btn btn-outline-danger btn-sm w-100 mt-3 rounded-pill" onclick="openReviewModal(${o.id})">
                            ⭐ Beri Rating & Ulasan
                        </button>
                    ` : ''}
                </div>`;
            }).join('');

        } catch (e) { 
            console.error("Error load orders:", e);
            container.innerHTML = `<div class="alert alert-danger text-center">Gagal memuat data.</div>`; 
        }
    }

    loadMyOrders();
    updateCartCount();
    // Auto refresh status setiap 20 detik agar User senang melihat statusnya berubah
    setInterval(loadMyOrders, 20000);

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const badge = document.getElementById('cart-count-nav');
        if(badge) badge.innerText = cart.length;
    }
</script>
</body>
</html>
