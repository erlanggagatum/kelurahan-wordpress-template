# Usulan struktur situs dan menu

Peta situs ini memetakan setiap halaman ke ID modul di [modul.md](modul.md). Halaman milik modul nonaktif
tidak dibuat, dan item menunya tidak muncul. Menu induk ikut disembunyikan bila semua anaknya nonaktif.

Slug URL memakai bahasa Indonesia, huruf kecil, dan tanda hubung. Label menu memakai huruf kapital hanya di
awal, kecuali nama diri dan singkatan.

## Menu utama

```
Label menu                       URL                                    Modul
Beranda                          /                                      beranda
Profil                           /profil/                               (induk)
├── Sejarah                      /profil/sejarah/                       tentang.sejarah
├── Visi dan misi                /profil/visi-misi/                     tentang.visi-misi
├── Wilayah dan geografi         /profil/wilayah/                       profil-wilayah
└── Potensi sumber daya alam     /profil/potensi-sda/                   sda (subhalaman per sda.*)
Pemerintahan                     /pemerintahan/                         (induk)
├── Struktur organisasi          /pemerintahan/struktur-organisasi/     kelembagaan.pemerintahan
├── Lurah dan perangkat          /pemerintahan/perangkat/               kelembagaan.pemerintahan
└── Pokja profil kelurahan       /pemerintahan/pokja-profil/            pokja-profil
Lembaga                          /lembaga/                              kelembagaan (arsip CPT lembaga)
├── Lembaga kemasyarakatan                                              kelembagaan.kemasyarakatan
├── Lembaga ekonomi                                                     kelembagaan.ekonomi
├── Lembaga pendidikan                                                  kelembagaan.pendidikan
├── Keamanan dan ketertiban                                             kelembagaan.keamanan
└── Sosial, profesi, partai politik, adat (bila aktif)                  kelembagaan.*
Data kelurahan                   /data/                                 (induk)
├── Kependudukan                 /data/kependudukan/                    kependudukan.*
├── Prasarana dan sarana         /data/prasarana-sarana/                prasarana-sarana.*
├── Data keluarga                /data/keluarga/                        data-keluarga
└── Ibu dan anak                 /data/ibu-anak/                        riad-ibu-anak
Perkembangan                     /perkembangan/                         (induk)
├── Potensi dan tipologi         /perkembangan/potensi-tipologi/        analisis-potensi
├── Tingkat perkembangan         /perkembangan/tingkat/                 tingkat-perkembangan
└── Permasalahan dan program     /perkembangan/program/                 permasalahan-program
Layanan                          /layanan/                              (induk)
├── Layanan administrasi         /layanan/                              layanan (arsip CPT layanan)
├── Pengaduan                    /layanan/pengaduan/                    pengaduan
└── Informasi publik (PPID)      /ppid/                                 ppid
Informasi                        /informasi/                            (induk)
├── Berita dan pengumuman        /berita/                               berita
├── Agenda                       /agenda/                               agenda
├── Galeri                       /galeri/                               galeri
└── UMKM dan produk lokal        /umkm/                                 umkm
Publikasi                        /publikasi/                            (induk)
├── Dokumen profil               /publikasi/profil/                     dokumen-profil
├── Produk hukum                 /publikasi/produk-hukum/               produk-hukum
└── Jadwal pendataan             /publikasi/jadwal-pendataan/           jadwal-pendataan
Kontak                           /kontak/                               kontak
```

Catatan desain menu:

- Batasi menu tingkat atas sampai tujuh atau delapan item agar tetap terbaca di ponsel. Bila banyak modul
  pendukung yang nonaktif, gabungkan "Informasi" dan "Publikasi" menjadi satu menu "Informasi".
- Pembagian isi: "Profil" memuat identitas dan kondisi alam; "Data kelurahan" memuat angka statistik
  (Ps 3, 4, 7, dan 9); "Perkembangan" memuat hasil analisis (Ps 10 sampai 23 dan Ps 34).
- Label menu sama persis dengan judul halaman tujuannya. Jangan memakai sinonim di tempat lain, misalnya
  "Pemerintahan" di menu tetapi "Pemerintah kelurahan" di judul halaman.

## Susunan beranda

| Urutan | Blok | Modul |
|---|---|---|
| 1 | *Hero*: nama kelurahan, kecamatan, kabupaten/kota, foto kantor atau wilayah | `beranda` |
| 2 | Sambutan lurah | `beranda`, `kelembagaan.pemerintahan` |
| 3 | Statistik singkat: jumlah penduduk, KK, luas wilayah, jumlah RW dan RT | `kependudukan.jumlah`, `profil-wilayah` |
| 4 | Status kelurahan: klasifikasi, kategori, dan tipologi, beserta tahun datanya | `tingkat-perkembangan`, `analisis-potensi` |
| 5 | Layanan yang sering dicari | `layanan` |
| 6 | Berita dan agenda terbaru | `berita`, `agenda` |
| 7 | Tombol "Unduh profil kelurahan [tahun]" | `dokumen-profil` |
| 8 | Peta dan kontak singkat | `kontak` |

Blok milik modul nonaktif tidak ditampilkan.

## *Footer*

- Kolom 1: nama kelurahan, alamat, telepon, email, dan jam layanan (`kontak`).
- Kolom 2: tautan cepat ke Layanan administrasi, Pengaduan, Informasi publik (PPID), dan Dokumen profil.
- Kolom 3: tautan ke situs kecamatan, pemerintah kabupaten/kota, dan SP4N-LAPOR!
- Baris bawah: "Data profil disusun berdasarkan Permendagri Nomor 12 Tahun 2007. Tahun data: ____."

## Jenis konten per halaman (usulan)

| Jenis konten | Dipakai oleh |
|---|---|
| Halaman | profil-wilayah, sda, kependudukan, prasarana-sarana, data-keluarga, riad-ibu-anak, analisis-potensi, tingkat-perkembangan, pokja-profil, jadwal-pendataan, tentang, pengaduan, ppid, kontak |
| Pos bawaan WordPress | berita |
| CPT `data_profil` | angka tahunan untuk semua modul data inti |
| CPT `lembaga` dan taksonomi `jenis_lembaga` | kelembagaan.* (kecuali pemerintahan) |
| CPT `perangkat` | kelembagaan.pemerintahan, pokja-profil |
| CPT `dokumen` dan taksonomi `jenis_dokumen` | dokumen-profil, produk-hukum, ppid |
| CPT `program` dan taksonomi `bidang` | permasalahan-program |
| CPT `layanan` | layanan |
| CPT `agenda` | agenda, jadwal-pendataan |
| CPT `galeri`, `umkm`, `lokasi` atau `fasilitas` | galeri, umkm, sda.wisata, sda.ruang-publik, prasarana-sarana |

Nama CPT dan taksonomi di atas masih usulan dan akan difinalkan saat implementasi, mengikuti
`docs/recipes/add-post-type.md`.
