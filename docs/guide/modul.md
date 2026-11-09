# Katalog modul situs profil kelurahan

Fitur situs dikelompokkan menjadi modul yang bisa diaktifkan atau dinonaktifkan untuk setiap kelurahan.
Modul terbagi dua:

- Modul inti, yang diturunkan langsung dari Permendagri 12/2007 (lihat [ringkasan](ringkasan-permen-12-2007.md)).
- Modul pendukung, yang tidak berasal dari Permen 12/2007. Isinya fitur yang lazim ada di situs pemerintah
  kelurahan, seperti berita, layanan, dan PPID.

ID, status bawaan, dan dependensi setiap modul juga tercatat di
[`theme/config/modules.php`](../../theme/config/modules.php). Tema membaca berkas itu: bagian situs yang
ditandai dengan ID modul tidak ditampilkan bila modulnya nonaktif. Menu dan halaman belum mengikuti modul. Setiap kali modul ditambah atau diubah, perbarui dokumen ini, `modules.php`,
dan [struktur-situs.md](struktur-situs.md) sekaligus.

## Cara membaca katalog

| Istilah | Arti |
|---|---|
| ID | Slug modul: huruf kecil dan tanda hubung. Submodul ditulis `induk.anak`, misalnya `sda.pertanian`. |
| Bawaan | Status saat situs baru dibuat: aktif, atau nonaktif dan diaktifkan bila relevan. |
| Butuh | Modul lain yang harus ikut aktif. |
| Usulan implementasi | Rencana teknis berupa halaman, *custom post type* (CPT), *meta*, atau taksonomi. Belum dibangun dan masih bisa berubah. |

## Aturan yang berlaku untuk semua modul

1. **Data pribadi hanya ditampilkan sebagai rekap.** Modul `data-keluarga` dan `riad-ibu-anak` bersumber
   dari data per keluarga dan per orang. Situs hanya menampilkan angka gabungan per RW, per lingkungan, atau
   satu kelurahan. NIK, nama, dan alamat warga tidak boleh disimpan atau ditampilkan (UU Nomor 27 Tahun 2022
   tentang Pelindungan Data Pribadi). Data agama, etnis, dan disabilitas juga hanya ditampilkan sebagai rekap.
2. **Setiap angka mencantumkan tahun dan sumbernya.** Data profil diperbarui berkala (Ps 39 dan 40): potensi
   setiap tiga tahun, data keluarga dan tingkat perkembangan setiap tahun. Contoh keterangan sumber:
   "Profil Kelurahan 2026, disahkan dengan Keputusan Lurah Nomor …".
3. **Situs tidak menghitung skor.** Indikator dan cara penilaian ada di Lampiran I sampai III yang tidak
   tersedia. Pokja memasukkan persentase skor dan kategorinya; situs menampilkannya dengan label sesuai
   tabel ambang di ringkasan.
4. **Modul nonaktif tidak meninggalkan jejak.** Halaman, item menu, dan blok miliknya ikut hilang, sehingga
   tidak ada tautan ke halaman yang tidak ada.

## A. Modul inti (Permendagri 12/2007)

### `profil-wilayah`: wilayah dan geografi

- Bawaan: aktif. Sumber: Ps 6 huruf a (potensi umum).
- Isi: batas wilayah di keempat arah; luas wilayah menurut penggunaan; iklim (curah hujan, suhu, musim);
  jenis dan kesuburan tanah; orbitasi, yaitu jarak dan waktu tempuh ke kantor kecamatan, kabupaten/kota, dan
  provinsi; bentangan wilayah (dataran rendah atau tinggi, pesisir, bantaran sungai); letak; jumlah RW, RT,
  dan lingkungan; peta wilayah.
- Usulan implementasi: halaman "Wilayah dan geografi" dengan *meta* halaman atau opsi tema. Peta berupa
  gambar atau sematan peta.

### `sda`: potensi sumber daya alam

- Bawaan: aktif. Sumber: Ps 6 huruf b sampai k.
- Submodul diaktifkan sesuai kondisi wilayah. Kelurahan di kota biasanya hanya memakai sebagian.

| Submodul | Butir | Bawaan |
|---|---|---|
| `sda.pertanian` | Pertanian (tanaman pangan dan hortikultura) | nonaktif |
| `sda.perkebunan` | Perkebunan | nonaktif |
| `sda.kehutanan` | Kehutanan | nonaktif |
| `sda.peternakan` | Peternakan | nonaktif |
| `sda.perikanan` | Perikanan | nonaktif |
| `sda.bahan-galian` | Bahan galian | nonaktif |
| `sda.air` | Sumber daya air | aktif |
| `sda.lingkungan` | Kualitas lingkungan | aktif |
| `sda.ruang-publik` | Ruang publik dan taman | aktif |
| `sda.wisata` | Wisata | nonaktif |

- Usulan implementasi: halaman induk "Potensi sumber daya alam" dengan subhalaman untuk setiap submodul
  yang aktif. Angka tahunan disimpan di CPT `data_profil` (lihat bagian data tahunan). `sda.wisata` dan
  `sda.ruang-publik` dapat memakai CPT `lokasi` yang memuat nama, foto, alamat, dan peta.

### `kependudukan`: kependudukan

- Bawaan: aktif. Sumber: Ps 7 (sumber daya manusia).

| Submodul | Butir | Bawaan |
|---|---|---|
| `kependudukan.jumlah` | Jumlah penduduk dan KK, per jenis kelamin dan per RW | aktif |
| `kependudukan.usia` | Kelompok usia (piramida penduduk) | aktif |
| `kependudukan.pendidikan` | Tingkat pendidikan | aktif |
| `kependudukan.pekerjaan` | Mata pencaharian pokok | aktif |
| `kependudukan.agama` | Agama dan aliran kepercayaan | aktif |
| `kependudukan.kewarganegaraan` | Kewarganegaraan (WNI dan WNA) | nonaktif |
| `kependudukan.etnis` | Etnis dan suku bangsa | nonaktif |
| `kependudukan.disabilitas` | Penyandang disabilitas fisik dan mental | aktif |
| `kependudukan.tenaga-kerja` | Tenaga kerja: angkatan kerja, bekerja, menganggur | aktif |

- Usulan implementasi: halaman "Kependudukan" berisi tabel dan grafik per submodul. Datanya diambil dari
  CPT `data_profil` dan ditampilkan oleh blok `kelurahan/statistik` (tabel dan grafik sederhana tanpa
  proses *build*).

### `kelembagaan`: kelembagaan

- Bawaan: aktif. Sumber: Ps 8.

| Submodul | Butir | Contoh | Bawaan |
|---|---|---|---|
| `kelembagaan.pemerintahan` | Lembaga pemerintahan kelurahan | Lurah, sekretaris, kepala seksi, kepala lingkungan, bagan organisasi | aktif |
| `kelembagaan.kemasyarakatan` | Lembaga kemasyarakatan | LPM, RT dan RW, TP-PKK, Karang Taruna, posyandu | aktif |
| `kelembagaan.sosial` | Lembaga sosial kemasyarakatan | Majelis taklim, yayasan, panti | nonaktif |
| `kelembagaan.profesi` | Organisasi profesi | Kelompok tani, nelayan, perajin | nonaktif |
| `kelembagaan.parpol` | Partai politik | Pengurus tingkat kelurahan | nonaktif |
| `kelembagaan.ekonomi` | Lembaga perekonomian | Koperasi, pasar, kelompok usaha | aktif |
| `kelembagaan.pendidikan` | Lembaga pendidikan | PAUD, TK, SD, SMP, SMA, pesantren | aktif |
| `kelembagaan.adat` | Lembaga adat | Pemangku adat | nonaktif |
| `kelembagaan.keamanan` | Lembaga keamanan dan ketertiban | Linmas, poskamling, Bhabinkamtibmas, Babinsa | aktif |

- Usulan implementasi: CPT `lembaga` (nama, ketua, alamat sekretariat, kontak, jumlah anggota, SK
  pembentukan, logo) dengan taksonomi `jenis_lembaga` yang istilahnya mengikuti submodul di atas. Untuk
  `kelembagaan.pemerintahan` dipakai CPT `perangkat` (nama, jabatan, foto, urutan) dan gambar bagan organisasi.

### `prasarana-sarana`: prasarana dan sarana

- Bawaan: aktif. Sumber: Ps 9.

| Submodul | Butir | Bawaan |
|---|---|---|
| `prasarana-sarana.transportasi` | Transportasi: jalan, jembatan, angkutan | aktif |
| `prasarana-sarana.komunikasi` | Informasi dan komunikasi | aktif |
| `prasarana-sarana.air-sanitasi` | Air bersih dan sanitasi | aktif |
| `prasarana-sarana.irigasi` | Prasarana dan kondisi irigasi | nonaktif |
| `prasarana-sarana.pemerintahan` | Prasarana pemerintahan: kantor, kendaraan dinas | aktif |
| `prasarana-sarana.lembaga` | Prasarana lembaga kemasyarakatan: balai RW, gedung PKK | aktif |
| `prasarana-sarana.ibadah` | Prasarana peribadatan | aktif |
| `prasarana-sarana.olahraga` | Prasarana olahraga | aktif |
| `prasarana-sarana.kesehatan` | Prasarana dan sarana kesehatan: puskesmas, posyandu, klinik | aktif |
| `prasarana-sarana.pendidikan` | Prasarana dan sarana pendidikan | aktif |
| `prasarana-sarana.energi` | Energi dan penerangan | aktif |
| `prasarana-sarana.hiburan-wisata` | Hiburan dan wisata | nonaktif |
| `prasarana-sarana.kebersihan` | Kebersihan: TPS, bank sampah, armada | aktif |

- Usulan implementasi: halaman "Prasarana dan sarana" berisi rekap jumlah per jenis dari CPT `data_profil`.
  Bila perlu direktori lokasi beserta peta, tambahkan CPT `fasilitas` dengan taksonomi `jenis_fasilitas`.

### `data-keluarga`: rekap data dasar keluarga

- Bawaan: nonaktif. Sumber: Ps 3.
- Isi berupa rekap tujuh butir Ps 3 huruf a sampai g, masing-masing menjadi submodul: potensi SDM keluarga
  (`data-keluarga.sdm`), perkembangan kesehatan (`.kesehatan`), perkembangan pendidikan (`.pendidikan`),
  aset ekonomi dan sosial (`.aset`), partisipasi dalam pemerintahan dan pembangunan (`.partisipasi`),
  permasalahan kesejahteraan (`.kesejahteraan`), serta keamanan dan ketertiban lingkungan (`.kamtib`).
- Privasi: situs hanya memuat rekap. Daftar isian per KK tidak dikelola di situs.
- Usulan implementasi: halaman "Data keluarga" dengan angka dari CPT `data_profil`.

### `riad-ibu-anak`: kualitas ibu dan anak (RIAD)

- Bawaan: nonaktif. Sumber: Ps 1 angka 16, Ps 4, Ps 34 huruf a. Butuh: `data-keluarga`.
- Isi berupa rekap per lingkungan atau RW: jumlah ibu hamil, kelahiran, bayi dan balita, cakupan imunisasi,
  status gizi, dan sejenisnya. Indikator rincinya ada di lampiran yang tidak tersedia, jadi sesuaikan dengan
  data posyandu dan puskesmas.
- Usulan implementasi: halaman "Ibu dan anak" berisi tabel per RW, dengan data dari CPT `data_profil` yang
  dipilah per wilayah.

### `analisis-potensi`: tingkat potensi dan tipologi

- Bawaan: aktif. Sumber: Ps 10 sampai 14, Ps 34 huruf b sampai d.

| Submodul | Isi |
|---|---|
| `analisis-potensi.potensi-umum` | Skor persen dan kategorinya: tinggi, sedang, atau rendah |
| `analisis-potensi.potensi-pengembangan` | Kategori per potensi: sangat potensial, potensial, cukup potensial, atau kurang potensial dikembangkan |
| `analisis-potensi.tipologi` | Salah satu dari sembilan tipologi, dari persawahan sampai jasa dan perdagangan |

- Usulan implementasi: *meta* di CPT `data_profil` per tahun, ditampilkan oleh blok `kelurahan/badge-status`.
  Label diturunkan dari skor dengan tabel ambang. Jika skor dan label yang dimasukkan tidak cocok, admin
  melihat peringatan.

### `tingkat-perkembangan`: tingkat perkembangan

- Bawaan: aktif. Sumber: Ps 15 sampai 23, Ps 34 huruf e sampai g.

| Submodul | Isi |
|---|---|
| `tingkat-perkembangan.variabel` | Skor sembilan variabel: ekonomi, pendidikan, kesehatan, keamanan dan ketertiban, kedaulatan politik, peran serta masyarakat, lembaga kemasyarakatan, kinerja pemerintahan, pembinaan dan pengawasan |
| `tingkat-perkembangan.laju` | Laju tahunan: cepat berkembang, berkembang, lamban berkembang, atau kurang berkembang |
| `tingkat-perkembangan.klasifikasi` | Klasifikasi lima tahunan: swasembada, swakarya, atau swadaya |
| `tingkat-perkembangan.status` | Status kemajuan: mula, madya, atau lanjut |

- Usulan implementasi: CPT `data_profil` per tahun dan grafik tren antartahun. Seperti `analisis-potensi`,
  situs hanya menampilkan hasil.

### `permasalahan-program`: permasalahan dan indikasi program

- Bawaan: aktif. Sumber: Ps 34 huruf h dan i, Ps 42 huruf d (masukan Musrenbang).
- Isi: daftar permasalahan per bidang (kualitas keluarga, potensi, faktor pembatas, perkembangan), indikasi
  program pembangunan tahun berikutnya, dan tautan ke hasil Musrenbang kelurahan.
- Usulan implementasi: CPT `program` (judul, bidang, tahun, status usulan/berjalan/selesai, anggaran bila
  ada) dengan taksonomi `bidang`.

### `dokumen-profil`: dokumen profil

- Bawaan: aktif. Sumber: Ps 35 sampai 37. Pasal 37 menyebut situs web sebagai media publikasi, dan Ps 36
  ayat 1 mewajibkan pengesahan dengan keputusan lurah.
- Isi: arsip buku profil kelurahan per tahun (PDF), keputusan lurah tentang pengesahan profil, foto atau
  infografik papan profil, dan berkas data yang bisa diunduh.
- Usulan implementasi: CPT `dokumen` (berkas, nomor, tanggal, tahun) dengan taksonomi `jenis_dokumen`
  (`profil`, `keputusan-lurah`, dan seterusnya). CPT ini juga dipakai modul `produk-hukum` dan `ppid`.

### `pokja-profil`: Pokja profil kelurahan

- Bawaan: aktif. Sumber: Ps 27.
- Isi: susunan Pokja (penanggung jawab lurah, ketua sekretaris kelurahan, dan para anggota) serta keputusan
  lurah tentang pembentukannya.
- Usulan implementasi: halaman berisi daftar anggota yang diambil dari CPT `perangkat` atau `lembaga`. SK
  disimpan di CPT `dokumen`.

### `jadwal-pendataan`: jadwal pendataan

- Bawaan: nonaktif. Sumber: Ps 39 dan 40.
- Isi: kalender pendataan tahunan (Agustus sampai September data keluarga, Oktober potensi dan
  perkembangan, November pengolahan, Desember publikasi), periode pembaruan data, dan ajakan kepada warga
  untuk mengisi daftar isian.
- Usulan implementasi: pola blok statis `kelurahan/jadwal-pendataan`, ditambah entri di modul `agenda` bila perlu.

## B. Modul pendukung (bukan dari Permen 12/2007)

Modul ini mengikuti kebiasaan situs pemerintah kelurahan. Dasar hukum lain disebutkan bila ada. Cocokkan
dengan peraturan daerah setempat sebelum dipakai.

| ID | Nama | Isi | Bawaan | Usulan implementasi |
|---|---|---|---|---|
| `beranda` | Beranda | Sambutan lurah, ringkasan statistik, berita terbaru, layanan yang sering dicari, tautan cepat | aktif, wajib | Templat blok `front-page` dan pola blok |
| `tentang` | Tentang kelurahan | Sejarah, visi dan misi, lambang atau asal-usul nama | aktif | Halaman; submodul `tentang.sejarah` dan `tentang.visi-misi` |
| `berita` | Berita dan pengumuman | Kabar kegiatan dan pengumuman resmi | aktif | Pos bawaan WordPress dengan kategori `berita` dan `pengumuman` |
| `agenda` | Agenda kegiatan | Jadwal kegiatan kelurahan, posyandu, kerja bakti, Musrenbang | aktif | CPT `agenda` (tanggal, waktu, lokasi) |
| `galeri` | Galeri | Foto dan video kegiatan | aktif | CPT `galeri`, atau halaman dengan blok Galeri |
| `layanan` | Layanan administrasi | Jenis surat dan layanan, persyaratan, alur, lama proses, biaya, jam layanan | aktif | CPT `layanan` (persyaratan, alur, lama proses, biaya) |
| `ppid` | Informasi publik (PPID) | Informasi berkala, serta-merta, tersedia setiap saat, dan yang dikecualikan; cara mengajukan permohonan informasi (UU Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik) | aktif | Halaman, ditambah CPT `dokumen` dengan taksonomi kategori informasi |
| `pengaduan` | Pengaduan masyarakat | Saluran pengaduan, tautan ke SP4N-LAPOR!, nomor telepon atau WhatsApp | aktif | Halaman berisi tautan. Bila memakai formulir, wajib ada *nonce*, sanitasi masukan, dan perlindungan dari spam |
| `produk-hukum` | Produk hukum | Keputusan lurah serta perda atau perwali yang terkait kelurahan | nonaktif | CPT `dokumen` dengan taksonomi `jenis_dokumen` |
| `umkm` | UMKM dan produk lokal | Direktori usaha warga | nonaktif | CPT `umkm` yang memuat data usaha, bukan data pribadi pemiliknya |
| `kontak` | Kontak dan lokasi | Alamat kantor, peta, telepon, email, jam layanan, media sosial | aktif, wajib | Halaman, ditambah opsi tema untuk kontak di *footer* |

## Data tahunan (usulan)

Sebagian besar modul inti berisi angka per tahun. Agar tidak muncul satu CPT untuk setiap modul, diusulkan
satu pola bersama:

- CPT `data_profil`: satu entri untuk satu tahun data, misalnya "Profil 2026". Setiap modul punya *meta*
  sendiri (`kependudukan_jumlah_l`, `tp_skor_ekonomi`, dan seterusnya) yang dideklarasikan di
  `theme/config/meta/data_profil.php`. *Meta* untuk modul nonaktif tidak didaftarkan.
- Taksonomi `tahun_data`, bila perlu, untuk mengelompokkan dokumen dan program per tahun.
- Data per RW atau lingkungan, seperti RIAD dan jumlah penduduk per RW, disimpan sebagai *meta* berformat
  JSON atau sebagai CPT anak. Pilihan ini diputuskan saat implementasi.

Rancangan ini difinalkan ketika modul pertama dibangun.

## Daftar periksa pemilihan modul

Salin bagian ini untuk setiap situs, lalu centang modul yang dipakai.

Kelurahan: ______________ · Kecamatan: ______________ · Kabupaten/kota: ______________ · Tahun data: ______

Modul inti (Permen 12/2007):

- [ ] `profil-wilayah`: wilayah dan geografi
- [ ] `sda`: potensi sumber daya alam. Submodul: [ ] pertanian [ ] perkebunan [ ] kehutanan [ ] peternakan [ ] perikanan [ ] bahan-galian [ ] air [ ] lingkungan [ ] ruang-publik [ ] wisata
- [ ] `kependudukan`: kependudukan. Submodul: [ ] jumlah [ ] usia [ ] pendidikan [ ] pekerjaan [ ] agama [ ] kewarganegaraan [ ] etnis [ ] disabilitas [ ] tenaga-kerja
- [ ] `kelembagaan`: kelembagaan. Submodul: [ ] pemerintahan [ ] kemasyarakatan [ ] sosial [ ] profesi [ ] parpol [ ] ekonomi [ ] pendidikan [ ] adat [ ] keamanan
- [ ] `prasarana-sarana`: prasarana dan sarana. Submodul: [ ] transportasi [ ] komunikasi [ ] air-sanitasi [ ] irigasi [ ] pemerintahan [ ] lembaga [ ] ibadah [ ] olahraga [ ] kesehatan [ ] pendidikan [ ] energi [ ] hiburan-wisata [ ] kebersihan
- [ ] `data-keluarga`: rekap data dasar keluarga
- [ ] `riad-ibu-anak`: kualitas ibu dan anak (butuh `data-keluarga`)
- [ ] `analisis-potensi`: tingkat potensi dan tipologi
- [ ] `tingkat-perkembangan`: tingkat perkembangan
- [ ] `permasalahan-program`: permasalahan dan indikasi program
- [ ] `dokumen-profil`: dokumen profil
- [ ] `pokja-profil`: Pokja profil kelurahan
- [ ] `jadwal-pendataan`: jadwal pendataan

Modul pendukung:

- [x] `beranda` (wajib)
- [x] `kontak` (wajib)
- [ ] `tentang` [ ] `berita` [ ] `agenda` [ ] `galeri` [ ] `layanan` [ ] `ppid` [ ] `pengaduan` [ ] `produk-hukum` [ ] `umkm`
