# Panduan domain situs profil kelurahan

Folder ini berisi spesifikasi isi situs: apa yang ditampilkan dan dasar aturannya. Cara membangunnya ada
di `docs/recipes/` dan `AGENTS.md`.

| Dokumen | Isi |
|---|---|
| [ringkasan-permen-12-2007.md](ringkasan-permen-12-2007.md) | Isi Permendagri 12/2007 per pasal: definisi, butir data, tabel skor, Pokja, jadwal, publikasi |
| [modul.md](modul.md) | Katalog modul inti dan pendukung: ID, pasal sumber, isi, status bawaan, dependensi, usulan implementasi, daftar periksa per situs |
| [struktur-situs.md](struktur-situs.md) | Usulan peta situs, menu utama, susunan beranda, *footer*, dan jenis konten per halaman |
| [Permen 12-2007-Profil Desa dan Kelurahan.pdf](Permen%2012-2007-Profil%20Desa%20dan%20Kelurahan.pdf) | Dokumen sumber (batang tubuh saja) |

## Sumber regulasi

Peraturan Menteri Dalam Negeri Nomor 12 Tahun 2007 tentang Pedoman Penyusunan dan Pendayagunaan Data Profil
Desa dan Kelurahan. Pasal 37 menyebut situs web sebagai salah satu media publikasi data profil.

## Batasan dokumen dan regulasi

1. Salinan PDF tidak memuat Lampiran I sampai V, padahal lampiran itulah yang berisi daftar isian data
   dasar keluarga, potensi, dan tingkat perkembangan, panduan teknis pengolahan, serta format laporan.
   Akibatnya ada dua hal:
   - rincian indikator per butir (misalnya kolom tabel pertanian) belum bisa dibakukan, sehingga modul
     memakai butir dari batang tubuh;
   - situs tidak menghitung skor, tetapi menyimpan dan menampilkan hasil yang sudah disahkan lurah.

   Jika lampiran sudah didapat, simpan di folder ini dan perinci *field* di `modul.md`.
2. Permen ini terbit tahun 2007. Sebagian isinya, terutama evaluasi tingkat perkembangan desa/kelurahan
   dan administrasi kependudukan, mungkin sudah diatur ulang oleh peraturan yang lebih baru. Periksa ke
   kecamatan atau pemerintah kabupaten/kota sebelum situs dipakai secara resmi.
3. Modul pendukung (berita, layanan, PPID, pengaduan, dan lainnya) tidak berasal dari Permen 12/2007 dan
   ditandai terpisah di `modul.md`.

## Langkah membuat situs untuk satu kelurahan

1. Salin daftar periksa di akhir [modul.md](modul.md) dan isi bersama pihak kelurahan.
2. Atur nilai `enabled` setiap modul dan submodul di `theme/config/modules.php` sesuai daftar periksa.
3. Buat halaman sesuai [struktur-situs.md](struktur-situs.md), hanya untuk modul yang aktif.
4. Isi data dari dokumen profil kelurahan yang sudah disahkan. Cantumkan tahun data dan nomor keputusan lurah.
