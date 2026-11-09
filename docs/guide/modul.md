# Katalog Modul Situs Profil Kelurahan

Setiap fitur situs dikelompokkan menjadi **modul** yang bisa diaktifkan atau dinonaktifkan per kelurahan.
Ada dua grup:

- **Inti**: turunan langsung dari Permendagri 12/2007 (lihat [ringkasan](ringkasan-permen-12-2007.md)).
- **Pendukung**: *bukan dari Permen 12/2007*. Fitur umum situs pemerintahan kelurahan (berita, layanan, PPID, dll.).

Status di kode: [`theme/config/modules.php`](../../theme/config/modules.php) memuat ID, default, dan
dependensi yang sama dengan dokumen ini. File itu **belum dibaca oleh tema**: untuk sekarang ia adalah
spesifikasi. Bila menambah atau mengubah modul, perbarui dokumen ini, `modules.php`, dan
[struktur-situs.md](struktur-situs.md) bersamaan.

## Konvensi

| Kolom | Arti |
|---|---|
| **ID** | Slug modul (huruf kecil, tanda hubung). Sub-modul ditulis `induk.anak`, mis. `sda.pertanian` |
| **Default** | ✅ aktif saat situs baru dibuat · ⬜ nonaktif, aktifkan bila relevan |
| **Butuh** | Modul lain yang wajib aktif |
| **Usulan implementasi** | Rencana teknis (Page, CPT, meta, taksonomi). Belum dibangun, dan bisa berubah |

### Aturan lintas modul

1. **Data pribadi hanya agregat.** Modul `data-keluarga` dan `riad-ibu-anak` bersumber dari data per
   keluarga/per orang. Situs **hanya** menampilkan rekap (per RW/lingkungan atau satu kelurahan). Jangan
   menyimpan atau menampilkan NIK, nama, atau alamat warga (UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi).
   Rekap agama, etnis, dan disabilitas juga ditampilkan agregat saja.
2. **Data berlabel tahun.** Data profil diperbarui berkala (Ps 39–40): potensi tiap 3 tahun, data
   keluarga dan tingkat perkembangan tiap tahun. Setiap angka harus punya **tahun data** dan sumber
   (mis. "Profil Kelurahan 2026, disahkan Keputusan Lurah No. …").
3. **Situs menyimpan dan menampilkan hasil skor, tanpa menghitungnya.** Indikator dan skor ada di Lampiran I–III
   yang tidak tersedia. Pokja memasukkan persentase skor dan kategori. Situs menampilkan label sesuai tabel ambang di ringkasan.
4. **Modul nonaktif = halaman, menu, dan widget hilang.** Tidak boleh ada tautan mati ke modul yang nonaktif.

---

## A. Modul Inti (Permendagri 12/2007)

### `profil-wilayah` — Profil Wilayah & Geografi ✅
- **Sumber:** Ps 6 huruf a (potensi umum).
- **Isi:** batas wilayah (utara/selatan/timur/barat), luas wilayah menurut penggunaan, iklim (curah hujan,
  suhu, musim), jenis dan kesuburan tanah, orbitasi (jarak/waktu tempuh ke kecamatan, kab/kota, provinsi),
  bentangan wilayah (dataran rendah/tinggi, pesisir, bantaran sungai), letak, jumlah RW/RT/lingkungan, peta wilayah.
- **Usulan implementasi:** Page "Wilayah & Geografi" + meta di halaman atau opsi tema. Peta memakai embed/gambar.

### `sda` — Potensi Sumber Daya Alam ✅
- **Sumber:** Ps 6 huruf b–k. Setiap sub-modul diaktifkan sesuai kondisi. Kelurahan perkotaan biasanya hanya memakai sebagian.

| Sub-modul | Butir | Default |
|---|---|---|
| `sda.pertanian` | Pertanian (tanaman pangan, hortikultura) | ⬜ |
| `sda.perkebunan` | Perkebunan | ⬜ |
| `sda.kehutanan` | Kehutanan | ⬜ |
| `sda.peternakan` | Peternakan | ⬜ |
| `sda.perikanan` | Perikanan | ⬜ |
| `sda.bahan-galian` | Bahan galian | ⬜ |
| `sda.air` | Sumber daya air | ✅ |
| `sda.lingkungan` | Kualitas lingkungan | ✅ |
| `sda.ruang-publik` | Ruang publik/taman | ✅ |
| `sda.wisata` | Wisata | ⬜ |

- **Usulan implementasi:** Page induk "Potensi SDA" + sub-halaman per sub-modul aktif. Angka tahunan disimpan di
  CPT `data_profil` (lihat §Data tahunan). `sda.wisata` dan `sda.ruang-publik` bisa memakai CPT `lokasi` (nama, foto, alamat, peta).

### `kependudukan` — Kependudukan (Sumber Daya Manusia) ✅
- **Sumber:** Ps 7.

| Sub-modul | Butir | Default |
|---|---|---|
| `kependudukan.jumlah` | Jumlah penduduk, KK, per jenis kelamin, per RW | ✅ |
| `kependudukan.usia` | Kelompok usia (piramida penduduk) | ✅ |
| `kependudukan.pendidikan` | Tingkat pendidikan | ✅ |
| `kependudukan.pekerjaan` | Mata pencaharian pokok | ✅ |
| `kependudukan.agama` | Agama dan aliran kepercayaan | ✅ |
| `kependudukan.kewarganegaraan` | Kewarganegaraan (WNI/WNA) | ⬜ |
| `kependudukan.etnis` | Etnis/suku bangsa | ⬜ |
| `kependudukan.disabilitas` | Penyandang disabilitas (fisik dan mental) | ✅ |
| `kependudukan.tenaga-kerja` | Tenaga kerja (angkatan kerja, bekerja, menganggur) | ✅ |

- **Usulan implementasi:** Page "Kependudukan" dengan tabel/grafik per sub-modul. Data dari CPT `data_profil`,
  ditampilkan oleh block `kelurahan/statistik` (tabel + grafik sederhana, tanpa build step).

### `kelembagaan` — Kelembagaan ✅
- **Sumber:** Ps 8.

| Sub-modul | Butir | Contoh | Default |
|---|---|---|---|
| `kelembagaan.pemerintahan` | Lembaga pemerintahan kelurahan | Lurah, Sekretaris, Kasi, Kepala Lingkungan, struktur organisasi | ✅ |
| `kelembagaan.kemasyarakatan` | Lembaga kemasyarakatan kelurahan | LPM, RT/RW, TP-PKK, Karang Taruna, Posyandu | ✅ |
| `kelembagaan.sosial` | Lembaga sosial kemasyarakatan | Majelis taklim, yayasan, panti | ⬜ |
| `kelembagaan.profesi` | Organisasi profesi | Kelompok tani/nelayan/pengrajin | ⬜ |
| `kelembagaan.parpol` | Partai politik | Kepengurusan tingkat kelurahan | ⬜ |
| `kelembagaan.ekonomi` | Lembaga perekonomian | Koperasi, pasar, kelompok usaha | ✅ |
| `kelembagaan.pendidikan` | Lembaga pendidikan | PAUD, TK, SD, SMP, SMA, pesantren | ✅ |
| `kelembagaan.adat` | Lembaga adat | Pemangku adat | ⬜ |
| `kelembagaan.keamanan` | Lembaga keamanan & ketertiban | Linmas, Poskamling, Bhabinkamtibmas, Babinsa | ✅ |

- **Usulan implementasi:** CPT `lembaga` (nama, ketua, alamat sekretariat, kontak, jumlah anggota, SK
  pembentukan, logo) + taksonomi `jenis_lembaga` yang istilahnya mengikuti sub-modul di atas. Untuk
  `kelembagaan.pemerintahan`: CPT `perangkat` (nama, jabatan, foto, urutan) + gambar struktur organisasi.

### `prasarana-sarana` — Prasarana & Sarana ✅
- **Sumber:** Ps 9.

| Sub-modul | Butir | Default |
|---|---|---|
| `prasarana-sarana.transportasi` | Transportasi (jalan, jembatan, angkutan) | ✅ |
| `prasarana-sarana.komunikasi` | Informasi & komunikasi | ✅ |
| `prasarana-sarana.air-sanitasi` | Air bersih & sanitasi | ✅ |
| `prasarana-sarana.irigasi` | Prasarana & kondisi irigasi | ⬜ |
| `prasarana-sarana.pemerintahan` | Prasarana pemerintahan (kantor, kendaraan dinas) | ✅ |
| `prasarana-sarana.lembaga` | Prasarana lembaga kemasyarakatan (balai RW, gedung PKK) | ✅ |
| `prasarana-sarana.ibadah` | Prasarana peribadatan | ✅ |
| `prasarana-sarana.olahraga` | Prasarana olah raga | ✅ |
| `prasarana-sarana.kesehatan` | Prasarana & sarana kesehatan (puskesmas, posyandu, klinik) | ✅ |
| `prasarana-sarana.pendidikan` | Prasarana & sarana pendidikan | ✅ |
| `prasarana-sarana.energi` | Energi & penerangan | ✅ |
| `prasarana-sarana.hiburan-wisata` | Hiburan & wisata | ⬜ |
| `prasarana-sarana.kebersihan` | Kebersihan (TPS, bank sampah, armada) | ✅ |

- **Usulan implementasi:** Page "Prasarana & Sarana" berisi rekap jumlah per jenis (CPT `data_profil`).
  Opsional: CPT `fasilitas` + taksonomi `jenis_fasilitas` untuk direktori lokasi beserta peta.

### `data-keluarga` — Rekap Data Dasar Keluarga ⬜
- **Sumber:** Ps 3.
- **Isi (agregat):** potensi SDM keluarga, perkembangan kesehatan, perkembangan pendidikan, penguasaan aset
  ekonomi & sosial, partisipasi dalam pemerintahan/pembangunan, permasalahan kesejahteraan, keamanan &
  ketertiban lingkungan (Ps 3 huruf a–g = sub-modul `data-keluarga.sdm`, `.kesehatan`, `.pendidikan`,
  `.aset`, `.partisipasi`, `.kesejahteraan`, `.kamtib`).
- **Privasi:** hanya rekap. Daftar isian per KK **tidak** dikelola di situs.
- **Usulan implementasi:** bagian dari Page "Data Keluarga". Angka di CPT `data_profil`.

### `riad-ibu-anak` — Kualitas Ibu & Anak (RIAD) ⬜
- **Sumber:** Ps 1(16), Ps 4, Ps 34 huruf a.
- **Butuh:** `data-keluarga`.
- **Isi (agregat per lingkungan/RW):** jumlah ibu hamil, kelahiran, bayi/balita, cakupan imunisasi,
  status gizi, dll. (Indikator rinci ada di Lampiran yang tidak tersedia, jadi sesuaikan dengan data Posyandu/Puskesmas.)
- **Usulan implementasi:** Page "Ibu & Anak" dengan tabel per RW. CPT `data_profil` dengan dimensi wilayah.

### `analisis-potensi` — Tingkat Potensi & Tipologi ✅
- **Sumber:** Ps 10–14, Ps 34 huruf b–d.

| Sub-modul | Isi |
|---|---|
| `analisis-potensi.potensi-umum` | Skor % dan kategori: Tinggi / Sedang / Rendah |
| `analisis-potensi.potensi-pengembangan` | Per potensi: Sangat / Potensial / Cukup / Kurang Potensial Dikembangkan |
| `analisis-potensi.tipologi` | Salah satu dari 9 tipologi (persawahan … jasa & perdagangan) |

- **Usulan implementasi:** meta di CPT `data_profil` (tahun) + block `kelurahan/badge-status`. Label
  diturunkan dari skor memakai tabel ambang. Bila skor dan label tidak cocok, tampilkan peringatan di admin.

### `tingkat-perkembangan` — Tingkat Perkembangan Kelurahan ✅
- **Sumber:** Ps 15–23, Ps 34 huruf e–g.

| Sub-modul | Isi |
|---|---|
| `tingkat-perkembangan.variabel` | Skor 9 variabel (ekonomi, pendidikan, kesehatan, kamtib, kedaulatan politik, peran serta, lembaga kemasyarakatan, kinerja pemerintahan, pembinaan & pengawasan) |
| `tingkat-perkembangan.laju` | Laju tahunan: Cepat / Berkembang / Lamban / Kurang Berkembang |
| `tingkat-perkembangan.klasifikasi` | Klasifikasi 5 tahunan: Swasembada / Swakarya / Swadaya |
| `tingkat-perkembangan.status` | Status kemajuan: Mula / Madya / Lanjut |

- **Usulan implementasi:** CPT `data_profil` (tahun) + grafik tren antartahun. Sama seperti `analisis-potensi`, situs hanya menampilkan.

### `permasalahan-program` — Permasalahan & Indikasi Program ✅
- **Sumber:** Ps 34 huruf h–i, Ps 42 huruf d (input Musrenbang).
- **Isi:** daftar permasalahan per bidang (kualitas keluarga, potensi, faktor pembatas, perkembangan),
  indikasi program pembangunan tahun berikutnya, dan tautan ke hasil Musrenbang kelurahan.
- **Usulan implementasi:** CPT `program` (judul, bidang, tahun, status: usulan/berjalan/selesai, anggaran opsional) + taksonomi `bidang`.

### `dokumen-profil` — Dokumen & Publikasi Profil ✅
- **Sumber:** Ps 35–37 (website disebut eksplisit sebagai media publikasi), Ps 36(1) pengesahan oleh Keputusan Lurah.
- **Isi:** arsip buku profil kelurahan per tahun (PDF), Keputusan Lurah pengesahan profil, papan profil (foto/infografik), unduhan data.
- **Usulan implementasi:** CPT `dokumen` (file, nomor, tanggal, tahun) + taksonomi `jenis_dokumen`
  (`profil`, `keputusan-lurah`, …). Dipakai bersama oleh `produk-hukum` dan `ppid`.

### `pokja-profil` — Pokja Profil Kelurahan ✅
- **Sumber:** Ps 27.
- **Isi:** susunan Pokja (penanggung jawab: Lurah; ketua: Sekretaris Kelurahan; anggota), Keputusan Lurah pembentukan Pokja.
- **Usulan implementasi:** Page dengan daftar anggota (bisa memakai CPT `perangkat`/`lembaga`). SK di `dokumen`.

### `jadwal-pendataan` — Jadwal & Siklus Pendataan ⬜
- **Sumber:** Ps 39–40.
- **Isi:** kalender pendataan tahunan (Agu–Sep keluarga, Okt potensi & perkembangan, Nov pengolahan,
  Des publikasi), periode pembaruan (potensi 3 tahun, keluarga & perkembangan tahunan/5 tahunan),
  ajakan warga mengisi daftar isian.
- **Usulan implementasi:** pattern statis `kelurahan/jadwal-pendataan` + opsional entri di `agenda`.

---

## B. Modul Pendukung (bukan dari Permen 12/2007)

Fitur umum situs pemerintahan kelurahan. Dasar hukum lain disebut bila relevan. **Perlu diverifikasi**
terhadap peraturan daerah setempat.

| ID | Nama | Isi | Default | Usulan implementasi |
|---|---|---|---|---|
| `beranda` | Beranda | Sambutan Lurah, ringkasan statistik, berita terbaru, layanan populer, tautan cepat | ✅ (wajib) | `front-page` block template + patterns |
| `tentang` | Tentang Kelurahan | Sejarah, visi & misi, lambang/arti nama | ✅ | Pages (sub-modul `tentang.sejarah`, `tentang.visi-misi`) |
| `berita` | Berita & Pengumuman | Kabar kegiatan, pengumuman resmi | ✅ | Post bawaan + kategori `berita`/`pengumuman` |
| `agenda` | Agenda Kegiatan | Jadwal kegiatan kelurahan, Posyandu, kerja bakti, Musrenbang | ✅ | CPT `agenda` (tanggal, waktu, lokasi) |
| `galeri` | Galeri | Foto/video kegiatan | ✅ | CPT `galeri` atau Page dengan block Gallery |
| `layanan` | Layanan Administrasi | Jenis surat/layanan, persyaratan, alur, waktu, biaya, jam layanan | ✅ | CPT `layanan` (persyaratan, alur, durasi, biaya) |
| `ppid` | Informasi Publik (PPID) | Informasi berkala, serta-merta, setiap saat, dikecualikan; permohonan informasi (UU No. 14 Tahun 2008 KIP) | ✅ | Page + `dokumen` dengan taksonomi kategori informasi |
| `pengaduan` | Pengaduan Masyarakat | Kanal pengaduan, tautan SP4N-LAPOR!, kontak WhatsApp/telepon | ✅ | Page + tautan. Formulir (opsional) butuh nonce, sanitasi, dan anti-spam |
| `produk-hukum` | Produk Hukum | Keputusan Lurah, perda/perwali terkait kelurahan | ⬜ | `dokumen` dengan `jenis_dokumen` |
| `umkm` | UMKM & Produk Lokal | Direktori usaha warga | ⬜ | CPT `umkm` (pemilik usaha sebagai badan usaha, bukan data pribadi sensitif) |
| `kontak` | Kontak & Lokasi | Alamat kantor, peta, telepon, email, jam layanan, media sosial | ✅ (wajib) | Page + opsi tema untuk kontak di footer |

---

## Data tahunan (usulan bersama)

Banyak modul inti berupa angka per tahun. Usulan pola tunggal agar tidak muncul satu CPT per modul:

- CPT **`data_profil`**: satu entri = satu tahun data (mis. "Profil 2026"), dengan meta per modul
  (`kependudukan_jumlah_l`, `tp_skor_ekonomi`, …) yang dideklarasikan di `theme/config/meta/data_profil.php`.
  Meta untuk modul nonaktif tidak didaftarkan.
- Taksonomi **`tahun_data`** (opsional) untuk mengelompokkan dokumen dan program per tahun.
- Data per RW/lingkungan (RIAD, jumlah penduduk per RW) memakai meta repeater JSON atau CPT anak.
  Keputusan ini diambil saat implementasi.

Rancangan ini akan difinalkan ketika modul pertama dibangun.

---

## Checklist pemilihan modul (salin per situs)

Nama kelurahan: ______________________ · Kecamatan: ______________ · Kab/Kota: ______________ · Tahun data: ______

**Inti (Permen 12/2007)**
- [ ] `profil-wilayah` — Profil Wilayah & Geografi
- [ ] `sda` — Potensi SDA: [ ] pertanian [ ] perkebunan [ ] kehutanan [ ] peternakan [ ] perikanan [ ] bahan-galian [ ] air [ ] lingkungan [ ] ruang-publik [ ] wisata
- [ ] `kependudukan` — Kependudukan: [ ] jumlah [ ] usia [ ] pendidikan [ ] pekerjaan [ ] agama [ ] kewarganegaraan [ ] etnis [ ] disabilitas [ ] tenaga-kerja
- [ ] `kelembagaan` — Kelembagaan: [ ] pemerintahan [ ] kemasyarakatan [ ] sosial [ ] profesi [ ] parpol [ ] ekonomi [ ] pendidikan [ ] adat [ ] keamanan
- [ ] `prasarana-sarana` — Prasarana & Sarana: [ ] transportasi [ ] komunikasi [ ] air-sanitasi [ ] irigasi [ ] pemerintahan [ ] lembaga [ ] ibadah [ ] olahraga [ ] kesehatan [ ] pendidikan [ ] energi [ ] hiburan-wisata [ ] kebersihan
- [ ] `data-keluarga` — Rekap Data Dasar Keluarga (agregat)
- [ ] `riad-ibu-anak` — Kualitas Ibu & Anak (butuh `data-keluarga`)
- [ ] `analisis-potensi` — Tingkat Potensi & Tipologi
- [ ] `tingkat-perkembangan` — Tingkat Perkembangan
- [ ] `permasalahan-program` — Permasalahan & Indikasi Program
- [ ] `dokumen-profil` — Dokumen & Publikasi Profil
- [ ] `pokja-profil` — Pokja Profil Kelurahan
- [ ] `jadwal-pendataan` — Jadwal & Siklus Pendataan

**Pendukung**
- [x] `beranda` (wajib) · [x] `kontak` (wajib)
- [ ] `tentang` · [ ] `berita` · [ ] `agenda` · [ ] `galeri` · [ ] `layanan` · [ ] `ppid` · [ ] `pengaduan` · [ ] `produk-hukum` · [ ] `umkm`
