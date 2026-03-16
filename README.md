# 🚀 Laravel Filament Project

Project ini dibuat menggunakan **Laravel** dan **Filament Admin Panel**.

## 📦 Installation Guide

Ikuti langkah-langkah berikut untuk menjalankan project di local.

---

## 1. Clone Repository

```bash
git clone https://github.com/RendyAFS/pos-springbed.git
cd pos-springbed
```

---

## 2. Install Dependencies

Install dependency Laravel menggunakan Composer.

```bash
composer install
```

Install dependency frontend.

```bash
npm install
```

---

## 3. Setup Environment

Copy file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Atur konfigurasi database pada file `.env`.

```env
DB_DATABASE=pos_springbed
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Generate Application Key

```bash
php artisan key:generate
```

---

## 5. Setup Database & Seeder

Project ini menggunakan **migration dan seeder otomatis**.

Jalankan perintah berikut:

```bash
npm run update
```

Saat proses berjalan akan muncul pertanyaan dari **Filament Shield**.

Jawab dengan urutan berikut:

```
0
yes
policies_and_permissions
```

Perintah ini akan otomatis:

- Menjalankan **migration**
- Menjalankan **seeder**
- Setup **Filament Shield**
- Build frontend
- Menjalankan **Vite**

---
