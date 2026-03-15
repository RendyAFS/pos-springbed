# 🚀 Laravel Filament Project

Project ini dibuat menggunakan **Laravel** dan **Filament Admin Panel**.

## 📦 Installation Guide

Ikuti langkah-langkah berikut untuk menjalankan project di local.

---

## 1. Clone Repository

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
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
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Generate Application Key

```bash
php artisan key:generate
```

---

## 5. Import Database

Project ini **tidak menggunakan migration awal**, database sudah disediakan dalam bentuk **file SQL**.

Silahkan cek folder:

```
docs/database.sql
```

Kemudian **import file tersebut ke database** yang sudah dibuat.

Contoh menggunakan MySQL CLI:

```bash
mysql -u root -p nama_database < docs/database.sql
```

Atau bisa melalui **phpMyAdmin / TablePlus / DBeaver**.

---

## 6. Build Frontend Assets

Untuk production build:

```bash
npm run build
```

---

## 7. Run Development Server

Jalankan Vite untuk development.

```bash
npm run dev
```

Jalankan Laravel server.

```bash
php artisan serve
```

---

## 8. Akses Aplikasi

Frontend:

```
http://127.0.0.1:8000
```

Admin Panel (Filament):

```
http://127.0.0.1:8000/admin
```

---

## 📁 Important Folder

```
docs/
 └── database.sql   # File database yang harus di import
```

---

## 🛠 Useful Commands

Clear cache:

```bash
php artisan optimize:clear
```

Generate Filament resources:

```bash
php artisan make:filament-resource
```

---

## 👨‍💻 Development

Jika ada perubahan pada frontend:

```bash
npm run dev
```

Jika deploy production:

```bash
npm run build
```

---

## 📄 License

Project ini dibuat untuk kebutuhan internal / development.
