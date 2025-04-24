# Panduan Instalasi Sistem UPBG

## Daftar Isi

0. [Prerequisite](#prerequisite)
1. [Instalasi Dependency](#a-instalasi-dependency)
2. [Instalasi Project](#b-instalasi-project)
3. [Konfigurasi Database](#c-konfigurasi-database)
4. [Konfigurasi Hosting](#d-konfigurasi-hosting)
5. [Finalisasi](#e-finalisasi)

## Prerequisite

- Dokumentasi ini dibuat dengan asumsi terminal yang digunakan adalah `Command Prompt` atau `Git Bash` dan bukan `Windows Powershell` karena beberapa command pada `Command Prompt` berbeda dengan `Windows Powershell` .
- Dokumentasi ini sebagai acuan utama migrasi server sistem upbg dari komputer server lama ke komputer server baru, untuk panduan instalasi dari awal (bukan migrasi) juga sudah disertakan, mohon dibaca dengan seksama agar tidak terjadi kesalahan.

## A. Instalasi Dependency

| Dependency                                           | Version |
| ---------------------------------------------------- | ------- |
| [xampp](https://www.apachefriends.org/download.html) |         |
| [composer](https://getcomposer.org/download/)        |         |
| [npm](https://nodejs.org/en/download)                |         |
| php                                                  |         |

### xampp

![image](https://placehold.co/600x400)

### composer

![image](https://placehold.co/600x400)

### npm

![image](https://placehold.co/600x400)

### php

![image](https://placehold.co/600x400)

## B. Instalasi Project

### Clone Repository

Buka folder `htdocs` pada tempat xampp diinstal (biasanya `C:/xampp/htdocs`) kemudian klik kanan dan pilih `Open in Terminal` kemudian clone repository menggunakan command

```bash
git clone https://github.com/NizamHakim/upbg.git
```

Pindah ke folder yang baru dibuat dengan command

```bash
cd upbg
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

## C. Konfigurasi Database

### Export database dari komputer server

Buka `phpmyadmin` pada komputer server sebelumnya kemudian `Export` database. Copy file hasil export untuk dipindahkan pada komputer server baru.

![image](https://placehold.co/600x400)

### Buat database baru

Pada komputer server yang baru buat database baru sesuai dengan nama yang digunakan pada `DB_DATABASE=` . Jika ingin menggunakan data dari database sebelumnya maka lakukan `Import` pada database yang baru dibuat.

![image](https://placehold.co/600x400)

Jika tidak ingin menggunakan data yang lama maka jalankan command

```bash
php artisan migrate --seed
```

### Jalankan sistem

Setelah konfigurasi diatas seharusnya sistem sudah bisa dijalankan secara lokal menggunakan command

```bash
composer run dev
```

## D. Konfigurasi Hosting

TBA

## E. Finalisasi

Untuk mempermudah start server disarankan untuk membuat file baru untuk menjalankan command. Buka folder tempat anda ingin menyimpan file ini kemudian klik kanan dan pilih `Open in Terminal` .

### Linux

1. Buat file baru dengan menjalankan command

```bash
touch start_server.sh
```

2. Ubah permission menggunakan command

```bash
chmod +x start_server.sh
```

3. Tambahkan command ke file menggunakan command

```bash
echo TBA > start_server.sh
```

4. Sistem dapat distart dengan menjalankan (double click) file ini

### Windows

1. Buat file baru dengan menjalankan command

```bash
touch start_server.bat
```

2. Tambahkan command ke file menggunakan command

```bash
echo TBA > start_server.sh
```

3. Sistem dapat distart dengan menjalankan (double click) file ini
