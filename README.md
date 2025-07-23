# Aplikasi Manajemen Barang (CRUD Items)

Aplikasi web sederhana yang dibangun dengan framework Laravel untuk mengelola data barang (items). Proyek ini menyediakan fungsionalitas CRUD (Create, Read, Update, Delete) yang lengkap, dengan fitur unggah gambar dan tabel data yang interaktif dan efisien.

## Fitur Utama

- **Manajemen Data Barang**: Fungsionalitas penuh untuk menambah, melihat, mengubah, dan menghapus data barang.
- **Atribut Barang**: Setiap barang memiliki atribut seperti kode unik, nama, harga, dan gambar.
- **Tabel Data Interaktif**: Daftar barang ditampilkan menggunakan [Yajra DataTables](https://yajrabox.com/docs/laravel-datatables/master) dengan pemrosesan di sisi server (*server-side processing*). Ini membuat pemuatan data menjadi sangat cepat dan efisien, bahkan untuk jutaan data, lengkap dengan fitur pencarian dan pemilahan (*sorting*).
- **Unggah & Pemrosesan Gambar**:
  - Pengguna dapat mengunggah gambar untuk setiap barang.
  - Gambar yang diunggah akan secara otomatis diubah ukurannya (*resized*) menjadi 300x300 piksel menggunakan pustaka [Intervention Image](https://image.intervention.io/) untuk konsistensi tampilan dan efisiensi penyimpanan.
  - Gambar lama akan otomatis terhapus saat gambar baru diunggah pada proses edit.
- **Validasi Data**: Validasi di sisi server untuk memastikan integritas dan format data yang benar sebelum disimpan ke database.
- **API Endpoint**: Menyediakan endpoint API sederhana untuk mengambil harga barang berdasarkan ID, yang bisa diintegrasikan dengan sistem lain.
- **Manajemen Hak Akses**: Telah terpasang pustaka [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction) yang siap digunakan untuk mengelola peran (*roles*) dan izin (*permissions*) pengguna.

## Tumpukan Teknologi (Technology Stack)

### Backend
- **PHP 8.2+**
- **Laravel 12**
- **Pustaka Utama**:
  - `yajra/laravel-datatables-oracle`: Untuk tabel data sisi server.
  - `intervention/image`: Untuk manipulasi gambar.
  - `spatie/laravel-permission`: Untuk manajemen peran dan izin.
  - `laravel/ui`: Untuk komponen UI bawaan Laravel.
  - `laravel/breeze`: Untuk autentikasi.
  **Database**: Kompatibel dengan database relasional yang didukung Laravel seperti MySQL, PostgreSQL, SQLite, dan SQL Server.

### Frontend
- **Vite**: Sebagai *build tool* untuk aset frontend.
- **JavaScript**
- **Laravel Blade**: Sebagai *templating engine*.
- **Bootstrap (via `laravel/ui`)**: Kelas-kelas CSS seperti `.btn` mengindikasikan penggunaan Bootstrap untuk styling.
- **JavaScript**: Untuk interaksi dinamis.
- **CSS**: Untuk styling.
- **HTML**: Struktur hal
- **jQuery**: Untuk manipulasi DOM.
- **Chart.js**: Untuk visualisasi data.

### Alat Pengembangan
- **Visual Studio Code**: Editor kode yang digunakan.
- **Git**: Sistem kontrol versi.
- **Composer**: Manajer paket PHP.
- **Node.js & npm**: Untuk mengelola dependensi JavaScript.

### Database
- Kompatibel dengan database relasional yang didukung Laravel seperti **MySQL, PostgreSQL, SQLite, dan SQL Server**.

## Panduan Instalasi & Setup

1.  **Clone Repositori**
    ```bash
    git clone <url-repositori-anda>
    cd test-adibayu
    ```

2.  **Install Dependensi**
    ```bash
    # Install dependensi PHP
    composer install

    # Install dependensi JavaScript
    npm install
    ```

3.  **Konfigurasi Lingkungan**
    ```bash
    # Salin file .env.example
    copy .env.example .env

    # Generate kunci aplikasi
    php artisan key:generate
    ```
    - Buka file `.env` dan sesuaikan konfigurasi database Anda (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4.  **Migrasi Database & Storage Link**
    ```bash
    # Jalankan migrasi untuk membuat tabel-tabel
    php artisan migrate

    # Buat symbolic link untuk folder storage
    php artisan storage:link
    ```

5.  **Jalankan Aplikasi**
    ```bash
    # Jalankan server development Laravel
    php artisan serve

    # Jalankan Vite untuk kompilasi aset frontend
    npm run dev
    ```

6.  **Akses Aplikasi**
    Buka browser Anda dan kunjungi `http://127.0.0.1:8000`.
