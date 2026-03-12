<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bloom Florist</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fff5f7; font-family: 'Poppins', sans-serif; height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 25px; border: none; box-shadow: 0 15px 35px rgba(255, 117, 140, 0.1); background: white; }
        .btn-pink { background: #ff758c; color: white; border: none; font-weight: 600; transition: 0.3s; }
        .btn-pink:hover { background: #ff607b; transform: translateY(-2px); }
        .form-control { border-radius: 12px; padding: 12px 20px; border: 1px solid #eee; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(255, 117, 140, 0.2); border-color: #ff758c; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: #ff758c;">🌸 BloomLogin</h2>
                        <p class="text-muted small">Silakan masuk untuk mengelola toko</p>
                    </div>

                    <div id="alert-box"></div>

                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" id="email" class="form-control" placeholder="admin@mail.com" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="********" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn-pink w-100 rounded-pill py-2 mt-3 shadow-sm">Login</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">Belum punya akun? <a href="/register" class="text-decoration-none" style="color: #ff758c;">Daftar di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const alertBox = document.getElementById('alert-box');
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    alertBox.innerHTML = '<div class="alert alert-info py-2" style="font-size: 13px;">Memverifikasi data...</div>';

    try {
        const response = await fetch('/api/auth/login', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            // SIMPAN TOKEN KE LOCALSTORAGE
            localStorage.setItem('access_token', result.access_token);
            localStorage.setItem('user', JSON.stringify(result.user));

            alertBox.innerHTML = '<div class="alert alert-success py-2" style="font-size: 13px;">Login Berhasil!</div>';
            setTimeout(() => { window.location.href = '/admin/dashboard'; }, 800);
        } else {
            alertBox.innerHTML = `<div class="alert alert-danger py-2" style="font-size: 13px;">${result.message || 'Email atau Password salah!'}</div>`;
        }
    } catch (error) {
        alertBox.innerHTML = '<div class="alert alert-danger py-2" style="font-size: 13px;">Gagal terhubung ke server. Pastikan server aktif.</div>';
    }
});
</script>

</body>
</html>
