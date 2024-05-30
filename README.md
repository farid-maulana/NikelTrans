<h1 align="center">
  NikelTrans
</h1>

**NikelTrans** adalah sebuah sistem berbasis web yang digunakan untuk memonitoring kendaraan yang dimiliki oleh
perusahaan tambang Nikel.
Sistem ini dibangun menggunakan Laravel 11 dan Filament.

## Features

- **Authentication**
- **Manajemen Kendaraan**
- **Manajemen Pegawai/Driver**
- **Data Approver (Pihak Penyetuju)**
- **Pemesanan Kendaraan**
- **Monitoring Maintenance Kendaraan**

## Installation

- Buka terminal menggunakan Command Prompt atau GitBash.
- Arahkan ke direktori tempat kita akan menyimpan projek.
- Jalankan perintah berikut pada terminal.
  ```
  git clone https://github.com/farid-maulana/NikelTrans.git
  ```
- Masuk ke dalam direktori projek.
- Jalankan perintah berikut untuk menginstall dependency.
  ```
  composer install
  ```
  ```
  composer update
  ```
- Buka project menggunakan text editor.
- Jalankan perinah berikut untuk menyalin dan mengubah file **.env.example** menjadi **.env.**
    - Windows
      ```
      copy .env.example .env
      ```
    - Linux/MacOS
      ```
      cp .env.example .env
      ```
- Isi konfigurasi database pada file **.env**.
  ```
  DB_CONNECTION=mariadb
  DB_HOST=
  DB_PORT=
  DB_DATABASE=nikel_trans
  DB_USERNAME=
  DB_PASSWORD=
  ```
- Jalankan perintah ***php artisan key:generate*** pada terminal.
  ```
  php artisan key:generate
  ```
- Jalankan perintah berikut pada terminal untuk menginstall dependency nodejs.
  ```
  npm install
  ```
- Jalankan perintah berikut pada terminal.
  ```
  npm run dev
  ```
- Jalankan perintah berikut untuk menyimpan file dari web server
  ```
  php artisan storage:link
  ```
- Jalankan perintah berikut pada terminal untuk membuat tabel dan seeding data.
  ```
  php artisan migrate:fresh --seed
  ```
- Jalankan perintah berikut pada terminal untuk menjalankan server.
  ```
  php artisan serve
  ```

## Requirement

(Recomended)

- PHP Version: "^8.2"

  Link untuk download :
  https://www.php.net/downloads

- Nodejs Version: "^20.11.0"

  Link untuk download :
  https://nodejs.org/en/download

- MySQL Version: "^10.4.32"

  Link untuk download :
  https://dev.mysql.com/downloads/mysql/

- Composer version: "^2.5.4"

  Link untuk download :
  https://getcomposer.org/download/

- Git

  Link untuk download :
  https://git-scm.com/

- GD extension

- **Windows**: Windows 10 atau lebih baru.
- **Linux**: Ubuntu 18.04 atau lebih baru, CentOS 7 atau lebih baru, Debian 9 atau lebih baru, dan distribusi Linux
  lainnya yang mendukung versi PHP dan dependensi lain yang diperlukan.
- **macOS**: macOS 10.13 (High Sierra) atau lebih baru.

## Dependencies:

- laravel framework: "^11.9"
- laravel/tinker: "^2.8"
- filament/filament: "^3.0-stable"

## Development Dependencies:

- fakerphp/faker: "^1.23"
- laravel/pint: "^1.12"
- laravel/sail: "^1.26"
- mockery/mockery: "^1.6"
- nunomaduro/collision: "^8.0"
- pestphp/pest: "^2.34"
- pestphp/pest-plugin-laravel: "^2.4"
- axios: "^1.6.4",
- laravel-vite-plugin: "^1.0.0",
- vite: ">=5.0.12"
  
