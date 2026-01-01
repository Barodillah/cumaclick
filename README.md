# Aplikasi Shortlink Laravel

Aplikasi **Shortlink Laravel** adalah platform pemendek URL berbasis Laravel yang dilengkapi dengan fitur analitik klik, manajemen link, sistem wallet & top up, upgrade tier (Free / Premium / Diamond), serta dukungan **one-time link** (link sekali pakai).

Project ini dirancang modular, aman, dan scalable untuk kebutuhan personal maupun bisnis.

---

## ✨ Fitur Utama

- 🔗 **Shortlink Management**
  - Generate short URL
  - Custom alias
  - Expired link
  - Enable / disable link

- 👁️ **One-Time Link (Sekali Lihat)**
  - Link hanya bisa diakses satu kali
  - Locking dengan database transaction
  - Aman untuk link sensitif

- 📊 **Analytics & Statistik**
  - Total click
  - Unique click
  - Top links
  - Tracking waktu akses

- 👤 **User & Role**
  - User
  - Admin

- 💎 **Tier & Upgrade Fitur**
  - Free / Premium / Diamond
  - Unlock fitur berdasarkan tier

- 💰 **Wallet & Top Up**
  - Sistem koin
  - Top up via Midtrans
  - Callback otomatis

- 📧 **Email Notification**
  - Invoice top up
  - Konfirmasi pembayaran

---

## 🛠️ Teknologi yang Digunakan

- **Backend** : Laravel 11
- **Frontend** : Blade + Bootstrap
- **Database** : MySQL / MariaDB
- **Payment Gateway** : Midtrans
- **Auth** : Laravel Auth
- **API** : RESTful

---

## 📦 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/shortlink-laravel.git
cd shortlink-laravel
```

### 2. Install Dependency

```bash
composer install
npm install && npm run build
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Atur konfigurasi database dan Midtrans di file `.env`

```env
DB_DATABASE=shortlink
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=xxx
MIDTRANS_IS_PRODUCTION=false
```

### 4. Migrasi Database

```bash
php artisan migrate --seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Akses di browser:
```
http://127.0.0.1:8000
```

---

## 🔐 One-Time Link (Flow Singkat)

1. User mengaktifkan mode one-time
2. Sistem generate token one-time
3. Saat diakses:
   - Database di-lock (`lockForUpdate`)
   - Jika sudah dipakai → link invalid
   - Jika valid → redirect & tandai `used_at`

---

## 💳 Midtrans Callback

Endpoint callback:
```
POST /midtrans/callback
```

Digunakan untuk:
- Validasi transaksi
- Update status top up
- Menambahkan saldo wallet

---

## 📂 Struktur Folder Penting

```
app/
 ├── Http/Controllers
 ├── Models
 ├── Services
 └── Policies

resources/views/
 ├── dashboard
 ├── shortlink
 └── emails

routes/
 ├── web.php
 └── api.php
```

---

## 🚀 Roadmap (Next Improvement)

- QR Code generator
- Password protected link
- Geo & device analytics
- API public
- Rate limit per link

---

## 🤝 Kontribusi

Pull request sangat diterima.

Langkah kontribusi:
1. Fork repository
2. Buat branch fitur
3. Commit perubahan
4. Buat pull request

---

## 📄 Lisensi

Project ini menggunakan lisensi **MIT**.

---

## 👨‍💻 Author

Dikembangkan dengan ❤️ menggunakan Laravel.

Jika butuh versi **README yang lebih teknis**, **versi bisnis**, atau **versi SaaS pitching**, tinggal bilang.

