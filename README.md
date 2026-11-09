# Template WordPress Profil Kelurahan

Template situs web **profil kelurahan** di Indonesia, disusun mengikuti **Permendagri No. 12 Tahun 2007**
tentang Pedoman Penyusunan dan Pendayagunaan Data Profil Desa dan Kelurahan. Pasal 37 Permen tersebut menyebut
website sebagai media publikasi data profil.

Isi situs dibagi menjadi **modul** yang bisa dipilih per kelurahan. Modul **inti** berasal dari Permen:
wilayah, SDA, kependudukan, kelembagaan, prasarana, tingkat perkembangan, dan lain-lain. Modul **pendukung**
bukan dari Permen: berita, layanan, PPID, pengaduan, dan lain-lain.

Secara teknis, ini adalah tema WordPress hibrida (block theme + template PHP), dibangun agar mudah dikembangkan
oleh AI coding agent: post type, taksonomi, dan custom field berbasis konfigurasi, scaffolder, pemeriksa
otomatis, dan resep langkah demi langkah.

| Untuk | Baca |
|---|---|
| Isi & struktur situs (spesifikasi domain) | [docs/guide/](docs/guide/) |
| Katalog modul + checklist per situs | [docs/guide/modul.md](docs/guide/modul.md) |
| Usulan peta situs & menu | [docs/guide/struktur-situs.md](docs/guide/struktur-situs.md) |
| Ringkasan Permendagri 12/2007 | [docs/guide/ringkasan-permen-12-2007.md](docs/guide/ringkasan-permen-12-2007.md) |
| AI agent (Claude Code, Codex, Cursor, …) | [AGENTS.md](AGENTS.md) (Claude Code memuatnya lewat [CLAUDE.md](CLAUDE.md)) |
| Cara template WordPress di-resolve | [docs/architecture.md](docs/architecture.md) |
| Resep tugas teknis | [docs/recipes/](docs/recipes/) |

## Status

| Bagian | Status |
|---|---|
| Panduan domain & katalog modul | ✅ selesai (`docs/guide/`) |
| Deklarasi modul (`theme/config/modules.php`) | ✅ spesifikasi; belum dibaca tema |
| Fondasi tema (header, footer, template dasar, scaffolder, pemeriksa) | ✅ |
| Implementasi modul (CPT, field, template, menu otomatis) | ⏳ berikutnya |

## Kebutuhan

- PHP 8.1+ dengan `mysqli`, `curl`, `gd`, `mbstring`, `openssl`, `zip`
- MySQL 8 / MariaDB lokal
- Node/npm (opsional, hanya untuk pintasan `npm run`)
- Tanpa XAMPP/htdocs dan tanpa Docker. WordPress berjalan dari `.wp/` di dalam repo, dilayani server bawaan PHP.

## Instalasi lokal

**1. Buat database dan user** (sekali). Dari shell root MySQL (`mysql -u root -p` atau MySQL Workbench):

```sql
CREATE DATABASE wp_agent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wp_agent'@'localhost' IDENTIFIED BY 'choose-a-password';
GRANT ALL PRIVILEGES ON wp_agent.* TO 'wp_agent'@'localhost';
FLUSH PRIVILEGES;
```

**2. Konfigurasi:**

```bash
cp .env.example .env      # lalu isi DB_PASSWORD (dan nilai lain bila perlu)
```

**3. Pasang.** Perintah ini mengunduh WP-CLI (diverifikasi sha512) dan WordPress core, menulis wp-config,
memasang situs, menautkan tema, dan mengisi konten contoh:

```bash
npm run setup             # = php bin/setup.php --seed
```

**4. Jalankan** (host/port dari `WP_URL` di `.env`):

```bash
npm start                 # http://localhost:8080   admin: http://localhost:8080/wp-admin
```

Setup aman dijalankan ulang. Langkah yang sudah selesai dilewati.

## Membuat situs untuk satu kelurahan

Repo ini adalah **template**. Setiap kelurahan memakai salinannya sendiri, dengan database dan `.env` sendiri.

```bash
git clone <repo-ini> kelurahan-contoh && cd kelurahan-contoh   # atau GitHub "Use this template"
rm -rf .git && git init                                        # riwayat baru untuk situs ini
cp .env.example .env                                           # DB_NAME, password, WP_TITLE, port WP_URL baru
```

1. **Pilih modul.** Salin checklist di akhir [docs/guide/modul.md](docs/guide/modul.md), isi bersama pihak
   kelurahan, lalu set `enabled` di `theme/config/modules.php`.
2. **Pasang:** `npm run setup`, lalu `npm start`.
3. **Isi data** dari dokumen profil kelurahan yang sudah disahkan Keputusan Lurah. Sertakan tahun data.

Buat database situs terlebih dahulu (SQL seperti di atas, dengan nama DB baru). Set `WP_URL` **sebelum**
setup. Mengubahnya setelah itu memerlukan `wp search-replace`, dan `npm start` mencetak perintah yang tepat.

Tema sudah bernama `kelurahan` (prefix PHP `kelurahan_`). `bin/rename.php` **tidak** perlu dijalankan lagi.
Perbaikan umum yang dibuat saat membangun satu situs sebaiknya dikembalikan ke template ini.

## Perintah harian

| Perintah | Fungsi |
|---|---|
| `npm start` | server dev pada host/port `WP_URL` dari `.env` |
| `npm run make` | bantuan scaffolder (`php bin/make.php <cmd>`) |
| `npm run check` | pemeriksaan statis: lint PHP, JSON, markup blok, bentuk config, privasi |
| `npm run check:http` | statis + runtime + ambil setiap URL penting (server harus jalan) |
| `npm run wp -- <args>` | WP-CLI, mis. `npm run wp -- post list` |
| `npm run seed` | (ulang) buat konten contoh |
| `composer install && composer lint` | WordPress Coding Standards (PHPCS) |

## Prinsip penting

- **Data pribadi hanya agregat.** Data dasar keluarga dan data ibu & anak (RIAD) ditampilkan sebagai rekap.
  Tidak ada NIK, nama, atau alamat warga di situs (UU 27/2022 PDP). Lihat [modul.md](docs/guide/modul.md#aturan-lintas-modul).
- **Situs menampilkan hasil, tanpa menghitung skor.** Indikator penilaian ada di Lampiran Permen yang tidak tersedia.
  Situs menyimpan skor dan kategori yang disahkan Lurah.
- **Repo publik.** Tidak boleh ada path absolut, nama pribadi, email asli, atau rahasia. Ini diperiksa
  otomatis oleh `php bin/check.php` (lihat [AGENTS.md](AGENTS.md)).

## Alternatif server lokal

- **wp-env (Docker):** `npm run env:start`. Memakai `.wp-env.json` dan container DB sendiri, bukan MySQL Anda.
- **WordPress yang sudah ada** (XAMPP, LocalWP…): salin atau symlink `theme/` ke `wp-content/themes/kelurahan`, lalu aktifkan.

## Ke produksi

Deploy folder `theme/` sebagai `wp-content/themes/kelurahan`. Bagian lain repo tidak dibutuhkan saat runtime.

## Lisensi

GPL-2.0-or-later (lihat `composer.json`).
