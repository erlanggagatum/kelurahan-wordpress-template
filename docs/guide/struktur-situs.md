# Usulan Struktur Situs & Menu

Peta situs untuk situs profil kelurahan. Setiap halaman memetakan ke ID modul di [modul.md](modul.md).
Bila modul nonaktif, halaman dan item menunya **tidak dibuat**. Bila semua anak sebuah menu induk
nonaktif, menu induknya juga disembunyikan.

Slug URL memakai Bahasa Indonesia, huruf kecil, dan tanda hubung.

## Menu utama

```
Beranda                                   /                                  beranda
Profil                                    /profil/                           (induk)
├── Sejarah                               /profil/sejarah/                   tentang.sejarah
├── Visi & Misi                           /profil/visi-misi/                 tentang.visi-misi
├── Wilayah & Geografi                    /profil/wilayah/                   profil-wilayah
└── Potensi Sumber Daya Alam              /profil/potensi-sda/               sda (+ sub-halaman per sda.*)
Pemerintahan                              /pemerintahan/                     (induk)
├── Struktur Organisasi                   /pemerintahan/struktur-organisasi/ kelembagaan.pemerintahan
├── Lurah & Perangkat                     /pemerintahan/perangkat/           kelembagaan.pemerintahan
└── Pokja Profil Kelurahan                /pemerintahan/pokja-profil/        pokja-profil
Lembaga                                   /lembaga/                          kelembagaan (arsip CPT lembaga)
├── Lembaga Kemasyarakatan (LPM, RT/RW, PKK, Karang Taruna)                  kelembagaan.kemasyarakatan
├── Lembaga Ekonomi                                                          kelembagaan.ekonomi
├── Lembaga Pendidikan                                                       kelembagaan.pendidikan
├── Keamanan & Ketertiban                                                    kelembagaan.keamanan
└── (Sosial, Profesi, Partai Politik, Adat: bila aktif)                      kelembagaan.*
Data Kelurahan                            /data/                             (induk)
├── Kependudukan                          /data/kependudukan/                kependudukan.*
├── Prasarana & Sarana                    /data/prasarana-sarana/            prasarana-sarana.*
├── Data Keluarga                         /data/keluarga/                    data-keluarga
└── Ibu & Anak                            /data/ibu-anak/                    riad-ibu-anak
Perkembangan                              /perkembangan/                     (induk)
├── Potensi & Tipologi                    /perkembangan/potensi-tipologi/    analisis-potensi
├── Tingkat Perkembangan                  /perkembangan/tingkat/             tingkat-perkembangan
└── Permasalahan & Program                /perkembangan/program/             permasalahan-program
Layanan                                   /layanan/                          (induk)
├── Layanan Administrasi                  /layanan/                          layanan (arsip CPT layanan)
├── Pengaduan                             /layanan/pengaduan/                pengaduan
└── Informasi Publik (PPID)               /ppid/                             ppid
Informasi                                 /informasi/                        (induk)
├── Berita & Pengumuman                   /berita/                           berita
├── Agenda                                /agenda/                           agenda
├── Galeri                                /galeri/                           galeri
└── UMKM & Produk Lokal                   /umkm/                             umkm
Publikasi                                 /publikasi/                        (induk)
├── Dokumen Profil Kelurahan              /publikasi/profil/                 dokumen-profil
├── Produk Hukum                          /publikasi/produk-hukum/           produk-hukum
└── Jadwal Pendataan                      /publikasi/jadwal-pendataan/       jadwal-pendataan
Kontak                                    /kontak/                           kontak
```

Catatan desain menu:
- Maksimal **7–8 item tingkat atas** agar tetap nyaman di ponsel. Bila modul pendukung banyak yang
  nonaktif, `Informasi` dan `Publikasi` dapat digabung menjadi satu menu "Informasi".
- "Profil" berisi gambaran alam dan identitas. "Data Kelurahan" berisi angka statistik (Ps 7, 9, 3–4).
  "Perkembangan" berisi hasil analisis (Ps 10–23, 34).

## Beranda (urutan blok yang diusulkan)

| Urutan | Blok | Modul |
|---|---|---|
| 1 | Hero: nama kelurahan, kecamatan, kab/kota, foto kantor/wilayah | `beranda` |
| 2 | Sambutan Lurah | `beranda`, `kelembagaan.pemerintahan` |
| 3 | Statistik singkat: penduduk, KK, luas, RW/RT | `kependudukan.jumlah`, `profil-wilayah` |
| 4 | Status kelurahan: klasifikasi + kategori + tipologi (tahun data) | `tingkat-perkembangan`, `analisis-potensi` |
| 5 | Layanan populer | `layanan` |
| 6 | Berita & agenda terbaru | `berita`, `agenda` |
| 7 | Unduh Profil Kelurahan tahun terbaru | `dokumen-profil` |
| 8 | Peta & kontak singkat | `kontak` |

Blok yang modulnya nonaktif tidak dirender.

## Footer

- Kolom 1: identitas kelurahan, alamat, telepon, email, jam layanan (`kontak`)
- Kolom 2: tautan cepat: Layanan, Pengaduan, PPID, Dokumen Profil
- Kolom 3: tautan instansi: kecamatan, pemerintah kab/kota, SP4N-LAPOR!
- Baris bawah: "Data profil disusun berdasarkan Permendagri No. 12 Tahun 2007 · Tahun data: ____"

## Pemetaan ke jenis konten (usulan)

| Jenis | Dipakai oleh |
|---|---|
| Page | profil-wilayah, sda, kependudukan, prasarana-sarana, data-keluarga, riad-ibu-anak, analisis-potensi, tingkat-perkembangan, pokja-profil, jadwal-pendataan, tentang, pengaduan, ppid, kontak |
| Post (bawaan) | berita |
| CPT `data_profil` | angka tahunan untuk semua modul data inti |
| CPT `lembaga` + tax `jenis_lembaga` | kelembagaan.* (kecuali pemerintahan) |
| CPT `perangkat` | kelembagaan.pemerintahan, pokja-profil |
| CPT `dokumen` + tax `jenis_dokumen` | dokumen-profil, produk-hukum, ppid |
| CPT `program` + tax `bidang` | permasalahan-program |
| CPT `layanan` | layanan |
| CPT `agenda` | agenda, jadwal-pendataan |
| CPT `galeri`, `umkm`, `lokasi`/`fasilitas` | galeri, umkm, sda.wisata, sda.ruang-publik, prasarana-sarana |

Semua nama CPT dan taksonomi di atas masih **usulan**. Finalisasi dilakukan saat implementasi
mengikuti `docs/recipes/add-post-type.md`.
