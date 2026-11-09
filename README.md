# Templat WordPress profil kelurahan

Templat situs web profil kelurahan yang disusun mengikuti Permendagri Nomor 12 Tahun 2007 tentang Pedoman
Penyusunan dan Pendayagunaan Data Profil Desa dan Kelurahan. Pasal 37 peraturan itu menyebut situs web
sebagai salah satu media publikasi data profil.

Isi situs dibagi menjadi modul yang bisa dipilih untuk setiap kelurahan. Modul inti diturunkan dari Permen,
misalnya wilayah, sumber daya alam, kependudukan, kelembagaan, prasarana, dan tingkat perkembangan. Modul
pendukung, seperti berita, layanan, PPID, dan pengaduan, tidak berasal dari Permen.

Secara teknis, repositori ini berisi tema WordPress hibrida (tema blok dan templat PHP) yang dirancang untuk
dikembangkan oleh AI *coding agent*. Post type, taksonomi, dan *custom field* dideklarasikan lewat berkas
konfigurasi; tersedia juga *scaffolder*, pemeriksa otomatis, dan resep langkah demi langkah.

| Kebutuhan | Dokumen |
|---|---|
| Isi dan struktur situs | [docs/guide/](docs/guide/) |
| Katalog modul dan daftar periksa per situs | [docs/guide/modul.md](docs/guide/modul.md) |
| Usulan peta situs dan menu | [docs/guide/struktur-situs.md](docs/guide/struktur-situs.md) |
| Ringkasan Permendagri 12/2007 | [docs/guide/ringkasan-permen-12-2007.md](docs/guide/ringkasan-permen-12-2007.md) |
| Panduan untuk AI agent (Claude Code, Codex, Cursor, dan lainnya) | [AGENTS.md](AGENTS.md); Claude Code membacanya lewat [CLAUDE.md](CLAUDE.md) |
| Cara WordPress memilih templat | [docs/architecture.md](docs/architecture.md) |
| Resep tugas teknis | [docs/recipes/](docs/recipes/) |

## Status pengerjaan

| Bagian | Status |
|---|---|
| Panduan domain dan katalog modul (`docs/guide/`) | Selesai |
| Deklarasi modul (`theme/config/modules.php`) dan sakelarnya di tema | Selesai: bagian yang ditandai ID modul tersembunyi bila modulnya nonaktif. Menu dan halaman otomatis belum |
| Fondasi tema: *header*, *footer*, templat dasar, *scaffolder*, pemeriksa | Selesai |
| Implementasi modul: CPT, *field*, templat, menu otomatis | Tahap berikutnya |

## Kebutuhan sistem

- PHP 8.1 atau lebih baru dengan ekstensi `mysqli`, `curl`, `gd`, `mbstring`, `openssl`, dan `zip`
- MySQL 8 atau MariaDB di komputer lokal
- Node dan npm (opsional, hanya untuk pintasan `npm run`)

XAMPP dan Docker tidak diperlukan. WordPress berjalan dari folder `.wp/` di dalam repositori dan dilayani
server bawaan PHP.

## Pemasangan lokal

**1. Buat database dan pengguna** (cukup sekali). Buka *shell* MySQL sebagai root (`mysql -u root -p` atau
MySQL Workbench), lalu jalankan:

```sql
CREATE DATABASE wp_kelurahan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wp_kelurahan'@'localhost' IDENTIFIED BY 'choose-a-password';
GRANT ALL PRIVILEGES ON wp_kelurahan.* TO 'wp_kelurahan'@'localhost';
FLUSH PRIVILEGES;
```

**2. Atur konfigurasi:**

```bash
cp .env.example .env      # lalu isi DB_PASSWORD dan nilai lain yang perlu
```

**3. Pasang WordPress.** Perintah ini mengunduh WP-CLI (diverifikasi dengan sha512) dan WordPress, menulis
`wp-config.php`, memasang situs, menautkan tema, lalu mengisi konten contoh:

```bash
npm run setup             # sama dengan php bin/setup.php --seed
```

**4. Jalankan server** (host dan port diambil dari `WP_URL` di `.env`):

```bash
npm start                 # http://localhost:8080   admin: http://localhost:8080/wp-admin
```

Perintah pemasangan aman dijalankan ulang; langkah yang sudah selesai akan dilewati.

## Membuat situs untuk satu kelurahan

Repositori ini adalah templat. Setiap kelurahan memakai salinannya sendiri, lengkap dengan database dan `.env` sendiri.

```bash
git clone <repo-ini> kelurahan-contoh && cd kelurahan-contoh   # atau tombol "Use this template" di GitHub
rm -rf .git && git init                                        # riwayat git baru untuk situs ini
cp .env.example .env                                           # isi DB_NAME, kata sandi, WP_TITLE, dan port WP_URL
```

1. Pilih modul. Salin daftar periksa di akhir [docs/guide/modul.md](docs/guide/modul.md), isi bersama pihak
   kelurahan, lalu atur nilai `enabled` di `theme/config/modules.php`.
2. Pasang dan jalankan: `npm run setup`, lalu `npm start`.
3. Isi data dari dokumen profil kelurahan yang sudah disahkan dengan keputusan lurah, beserta tahun datanya.

Buat database situs lebih dulu dengan SQL di atas, memakai nama database baru. Atur `WP_URL` sebelum
pemasangan. Jika diubah sesudahnya, Anda perlu menjalankan `wp search-replace`; perintah lengkapnya dicetak
oleh `npm start`.

Tema sudah bernama `kelurahan` dengan prefiks PHP `kelurahan_`, jadi `bin/rename.php` tidak perlu dijalankan
lagi. Perbaikan umum yang muncul saat membangun satu situs sebaiknya dibawa kembali ke templat ini.

## Perintah sehari-hari

| Perintah | Fungsi |
|---|---|
| `npm start` | Menjalankan server pengembangan di host dan port `WP_URL` |
| `npm run make` | Menampilkan bantuan *scaffolder* (`php bin/make.php <cmd>`) |
| `npm run check` | Pemeriksaan statis: sintaksis PHP, JSON, markah blok, bentuk konfigurasi, privasi |
| `npm run check:http` | Pemeriksaan statis ditambah pemeriksaan WordPress dan setiap URL penting (server harus berjalan) |
| `npm run wp -- <args>` | Menjalankan WP-CLI, misalnya `npm run wp -- post list` |
| `npm run seed` | Membuat ulang konten contoh |
| `composer install && composer lint` | Memeriksa kode dengan WordPress Coding Standards (PHPCS) |

## Aturan penting

- **Data pribadi hanya tampil sebagai rekap.** Data dasar keluarga dan data ibu dan anak (RIAD) ditampilkan
  dalam angka gabungan. NIK, nama, dan alamat warga tidak ada di situs (UU Nomor 27 Tahun 2022 tentang
  Pelindungan Data Pribadi). Rinciannya ada di [modul.md](docs/guide/modul.md#aturan-yang-berlaku-untuk-semua-modul).
- **Situs menampilkan hasil penilaian, tidak menghitungnya.** Indikator penilaian ada di lampiran Permen yang
  tidak tersedia, jadi situs menyimpan skor dan kategori yang sudah disahkan lurah.
- **Repositori ini publik.** Path absolut, nama orang, email asli, dan data rahasia tidak boleh masuk.
  `php bin/check.php` memeriksanya secara otomatis (lihat [AGENTS.md](AGENTS.md)).

## Cara lain menjalankan secara lokal

- wp-env (Docker): jalankan `npm run env:start`. Cara ini memakai `.wp-env.json` dan *container* database
  sendiri, bukan MySQL di komputer Anda.
- WordPress yang sudah terpasang (XAMPP, LocalWP, dan sejenisnya): salin atau buat *symlink* folder `theme/`
  ke `wp-content/themes/kelurahan`, lalu aktifkan temanya.

## Memasang di server produksi

Unggah folder `theme/` sebagai `wp-content/themes/kelurahan`. Bagian lain repositori tidak dibutuhkan saat
situs berjalan.

## Lisensi

GPL-2.0-or-later (lihat `composer.json`).
