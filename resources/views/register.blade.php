<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Bloom Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fff5f7; font-family: 'Poppins', sans-serif; height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-register { background: linear-gradient(45deg, #ff758c, #ff7eb3); color: white; border: none; }
        .btn-register:hover { background: linear-gradient(45deg, #ff6b8e, #ff758c); color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="card-body">
                    <h3 class="text-center mb-4 fw-bold" style="color: #ff758c;">🌸 Daftar Akun</h3>
                    <div id="alert-box"></div>

                    <form id="registerForm">
                        <div class="mb-3">
                            <label class="form-label text-secondary">Nama Lengkap</label>
                            <input type="text" id="name" class="form-control" placeholder="Nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Email</label>
                            <input type="email" id="email" class="form-control" placeholder="email@mail.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Min 6 Karakter" required>
                        </div>
                        <button type="submit" class="btn btn-register w-100 rounded-pill py-2 mt-3">Daftar Sekarang</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">Sudah punya akun? <a href="/login" class="text-decoration-none" style="color: #ff758c;">Login di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
    };

    const alertBox = document.getElementById('alert-box');

    try {
        const response = await fetch('/api/auth/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alertBox.innerHTML = `<div class="alert alert-success">Pendaftaran berhasil! Silakan login.</div>`;
            setTimeout(() => { window.location.href = '/login'; }, 1500);
        } else {
            alertBox.innerHTML = `<div class="alert alert-danger">${JSON.stringify(result)}</div>`;
        }
    } catch (error) {
        alertBox.innerHTML = `<div class="alert alert-danger">Gagal menghubungi server.</div>`;
    }
});
</script>

</body>
</html>
