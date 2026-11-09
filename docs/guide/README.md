# Panduan Domain: Situs Profil Kelurahan

Folder ini adalah **spesifikasi isi** situs. Dokumen di sini menjelaskan *apa* yang ditampilkan situs dan *mengapa*.
Cara membangunnya ada di `docs/recipes/` dan `AGENTS.md`.

| Dokumen | Isi |
|---|---|
| [ringkasan-permen-12-2007.md](ringkasan-permen-12-2007.md) | Ekstraksi pasal demi pasal Permendagri 12/2007: definisi, butir data, tabel skor, Pokja, jadwal, publikasi |
| [modul.md](modul.md) | Katalog modul (inti dan pendukung): ID, sumber pasal, isi, default, dependensi, usulan implementasi, checklist per situs |
| [struktur-situs.md](struktur-situs.md) | Usulan peta situs, menu utama, susunan beranda, footer, pemetaan ke jenis konten |
| [Permen 12-2007-Profil Desa dan Kelurahan.pdf](Permen%2012-2007-Profil%20Desa%20dan%20Kelurahan.pdf) | Dokumen sumber (batang tubuh saja) |

## Sumber regulasi

**Peraturan Menteri Dalam Negeri No. 12 Tahun 2007** tentang Pedoman Penyusunan dan Pendayagunaan Data
Profil Desa dan Kelurahan. Pasal 37 menyebut **website** sebagai salah satu media publikasi data profil.

## Catatan status (penting)

1. **Lampiran I–V tidak tersedia** di salinan PDF. Isinya: daftar isian data dasar keluarga, potensi,
   tingkat perkembangan, panduan teknis pengolahan, dan format laporan. Akibatnya:
   - rincian indikator per butir (mis. kolom tabel pertanian) belum bisa dibakukan. Modul memakai butir pada batang tubuh;
   - situs **tidak menghitung skor**. Situs hanya menyimpan dan menampilkan hasil yang disahkan Lurah.
   Jika lampiran diperoleh, tambahkan ke folder ini dan perinci field di `modul.md`.
2. **Periksa regulasi yang lebih baru.** Permen ini terbit 2007. Beberapa bagian, terutama evaluasi
   tingkat perkembangan desa/kelurahan dan administrasi kependudukan, mungkin sudah diatur atau diganti
   peraturan yang lebih baru. Verifikasi dengan pemerintah kecamatan/kab/kota sebelum situs dipakai resmi.
3. Modul **pendukung** (berita, layanan, PPID, pengaduan, dll.) **bukan** turunan Permen 12/2007. Modul
   ini ditandai terpisah di `modul.md`.

## Alur pakai untuk situs baru

1. Salin checklist di akhir [modul.md](modul.md), lalu isi bersama pihak kelurahan.
2. Set `enabled` setiap modul/sub-modul di `theme/config/modules.php` sesuai checklist.
3. Bangun atau aktifkan halaman sesuai [struktur-situs.md](struktur-situs.md) (hanya untuk modul aktif).
4. Isi data dari dokumen profil kelurahan yang sudah disahkan, beserta **tahun data** dan nomor Keputusan Lurah.
