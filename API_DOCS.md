# 🌸 Bloom Florist API Documentation

Dokumentasi ini berisi daftar endpoint API yang tersedia untuk aplikasi Manajemen Florist.

## 🛡️ Autentikasi (JWT)
Seluruh endpoint manajemen bunga (POST/PUT/DELETE) wajib menggunakan **Bearer Token** yang didapat setelah login.

### 1. Register Akun Baru
*   **URL:** `/api/auth/register`
*   **Method:** `POST`
*   **Request Body:**
    ```json
    { "name": "Admin", "email": "admin@mail.com", "password": "password123" }
    ```

### 2. Login (Mendapatkan Token)
*   **URL:** `/api/auth/login`
*   **Method:** `POST`
*   **Response:**
    ```json
    { "status": "success", "access_token": "eyJhbGciOiJIUzI1NiI...", "user": { ... } }
    ```

---

## 🌻 Koleksi Bunga (CRUD)

### 1. Lihat Semua Bunga
*   **URL:** `/api/flowers`
*   **Method:** `GET`
*   **Query Params:** `search`, `category`, `sort`, `order`

### 2. Tambah Bunga Baru
*   **URL:** `/api/flowers`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer <token>`
*   **Body (Form-Data):** `flower_name`, `category`, `price`, `stock`, `image (file)`

### 3. Edit Bunga
*   **URL:** `/api/flowers/{id}`
*   **Method:** `POST` (dengan field `_method=PUT`)
*   **Headers:** `Authorization: Bearer <token>`

### 4. Hapus Bunga
*   **URL:** `/api/flowers/{id}`
*   **Method:** `DELETE`
*   **Headers:** `Authorization: Bearer <token>`

---
*Dibuat untuk Tugas UTS Pemrograman Web.*
