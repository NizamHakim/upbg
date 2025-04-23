# Panduan Instalasi Sistem UPBG

## Daftar Isi

1. [Instalasi Dependency](#a-instalasi-dependency)
2. [Instalasi Project](#b-instalasi-project)
3. [Konfigurasi Hosting](#c-konfigurasi-hosting)
4. [Finalisasi](#d-finalisasi)

## A. Instalasi Dependency

| Dependency                                           | Version |
| ---------------------------------------------------- | ------- |
| [xampp](https://www.apachefriends.org/download.html) |         |
| [composer](https://getcomposer.org/download/)        |         |
| [npm](https://nodejs.org/en/download)                |         |
| php                                                  |         |

### xampp

### composer

### npm

### php

## B. Instalasi Project

Jika tidak memiliki akses file `upbg.zip` pada komputer server sebelumnya maka dapat melakukan clone dari repository ini, akan tetapi akan memerlukan beberapa konfigurasi tambahan.

### Clone Repository

Buka `Command Prompt` pada komputer anda (bukan `Windows Powershell`) kemudian clone repository menggunakan command

```bash
git clone
```

Pindah ke folder yang baru dibuat dengan command

```bash
cd
```

Kemudian jalankan command

```bash
composer install
```

dan

```bash
npm install
```

### Konfigurasi `.env`

`.env` adalah file konfigurasi dasar sistem dan untuk alasan keamanan `.env` tidak dimasukkan ke dalam github. Oleh karena itu perlu membuat `.env` sendiri menggunakan template `.env.example` . Copy file menggunakan command

```bash
cp .env.example .env
```

Beberapa konfigurasi yang perlu disesuaikan adalah sebagai berikut

```ini
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://sim.upbg

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upbg
DB_USERNAME=root
```

### Generate key

Lakukan key generation untuk mengisi `APP_KEY=` pada `.env` menggunakan command

```bash
php artisan key:generate
```

### Buat database baru

Buka `phpmyadmin` dan buat database baru sesuai dengan nama yang digunakan pada `DB_DATABASE=` . Kemudian jalankan command dibawah untuk migrate table yang dibutuhkan sistem

```bash
php artisan migrate
```

Jika ingin menggunakan data dari database sebelumnya maka lakukan.

```

```

Tetapi jika ingin memulai dari awal maka jalankan seeder dengan command. Seeder ini akan menginisiasi data-data Kelas dan Tes yang terdaftar pada saat sistem ini dibuat.

```bash
php artisan db:seed
```

### Start sistem menggunakan command

```bash
composer run dev
```

## C. Konfigurasi Hosting

## D. Finalisasi
