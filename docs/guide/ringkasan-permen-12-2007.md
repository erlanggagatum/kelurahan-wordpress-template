# Ringkasan Permendagri Nomor 12 Tahun 2007

Pedoman Penyusunan dan Pendayagunaan Data Profil Desa dan Kelurahan, ditetapkan di Jakarta pada 12 Maret 2007.
Peraturan ini mencabut Keputusan Menteri Dalam Negeri Nomor 25 Tahun 1996 tentang Data Dasar Profil Desa/Kelurahan.

Sumber: [`Permen 12-2007-Profil Desa dan Kelurahan.pdf`](Permen%2012-2007-Profil%20Desa%20dan%20Kelurahan.pdf).
Ringkasan ini hanya mencakup batang tubuh (Pasal 1 sampai 56). Lampiran I sampai V tidak ada dalam salinan PDF
("Lampiran tidak dicantumkan"); lampiran itu memuat daftar isian, indikator, skor, panduan teknis, dan format laporan.

Singkatan "Ps" berarti pasal. Kode pada kolom Modul merujuk ke [modul.md](modul.md).

## 1. Definisi (Bab I, Pasal 1)

| Istilah | Arti ringkas | Ps |
|---|---|---|
| Kelurahan | Wilayah kerja lurah sebagai perangkat daerah kabupaten/kota dalam wilayah kerja kecamatan | 1 angka 2 |
| Profil desa dan kelurahan | Gambaran menyeluruh karakter desa/kelurahan: data dasar keluarga, potensi SDA, SDM, kelembagaan, prasarana dan sarana, perkembangan kemajuan, serta permasalahan | 1 angka 3 |
| Penyusunan | Pengumpulan, pengolahan, dan publikasi data profil | 1 angka 4 |
| Pendayagunaan | Pemanfaatan data profil untuk perencanaan dan evaluasi kinerja pemerintahan, pembangunan, dan pembinaan kemasyarakatan | 1 angka 5 |
| Potensi desa dan kelurahan | Seluruh sumber daya (SDM, SDA, kelembagaan, prasarana dan sarana) untuk mempercepat kesejahteraan masyarakat | 1 angka 8 |
| Tingkat perkembangan | Status capaian hasil pembangunan yang mencerminkan kemajuan masyarakat dan pemerintahan | 1 angka 9 |
| Program aplikasi | Alat bantu komputer untuk mengolah, menganalisis, dan menyajikan data profil | 1 angka 10 |
| Kategori Mula | Prioritas penanganan pada kebutuhan dasar: ekonomi, pendidikan, kesehatan | 1 angka 11 |
| Kategori Madya | Prioritas penanganan pada keamanan dan ketertiban, kesadaran politik dan kebangsaan, peran serta masyarakat, serta kinerja lembaga kemasyarakatan | 1 angka 12 |
| Kategori Lanjut | Prioritas penanganan pada kinerja pemerintahan desa/kelurahan serta pembinaan dan pengawasan | 1 angka 13 |
| Desa/kelurahan miskin | Potensi umumnya rendah, laju perkembangannya lamban atau kurang berkembang, dan statusnya swadaya (kategori mula, madya, atau lanjut) | 1 angka 14 |
| Data dasar keluarga | Potensi dan perkembangan keluarga (lihat bagian 3) | 1 angka 15 |
| RIAD | Registrasi ibu dan anak tingkat dusun dan lingkungan: pengumpulan, pengolahan, analisis, dan publikasi data perkembangan ibu dan anak per dusun/lingkungan | 1 angka 16 |
| Tipologi | Kondisi khas keunggulan potensi yang menentukan arah pengembangan desa/kelurahan | 1 angka 17 |

## 2. Susunan data profil (Bab II, Pasal 2)

Profil terdiri atas tiga kelompok data: data dasar keluarga, potensi desa/kelurahan, dan tingkat
perkembangan desa/kelurahan.

```
Profil kelurahan
├── Data dasar keluarga (Ps 3 dan 4), menjadi dasar RIAD (ibu dan anak)
├── Potensi (Ps 5 sampai 14)
│   ├── Sumber daya alam (Ps 6)
│   ├── Sumber daya manusia (Ps 7)
│   ├── Kelembagaan (Ps 8)
│   ├── Prasarana dan sarana (Ps 9)
│   └── Analisis: tingkat potensi umum, potensi pengembangan, tipologi (Ps 10 sampai 14)
└── Tingkat perkembangan (Ps 15 sampai 23)
    ├── Laju tahunan (empat kategori)
    ├── Klasifikasi lima tahunan (swadaya, swakarya, swasembada)
    └── Status kemajuan (mula, madya, lanjut)
```

## 3. Data dasar keluarga (Pasal 3 dan 4)

Modul: `data-keluarga`, `riad-ibu-anak`.

| Huruf | Butir |
|---|---|
| a | Potensi sumber daya manusia |
| b | Perkembangan kesehatan |
| c | Perkembangan pendidikan |
| d | Penguasaan aset ekonomi dan sosial keluarga |
| e | Partisipasi anggota keluarga dalam proses pemerintahan, pembangunan, dan kemasyarakatan |
| f | Permasalahan kesejahteraan keluarga |
| g | Perkembangan keamanan dan ketertiban di lingkungannya |

Menurut Pasal 4, data dasar keluarga menjadi dasar perhitungan perkembangan kualitas manusia melalui RIAD.
Daftar isiannya diisi oleh kepala keluarga (Ps 39 ayat 1).

Catatan untuk situs: data per keluarga termasuk data pribadi, jadi situs hanya menampilkan rekapnya.

## 4. Potensi (Pasal 5 sampai 9)

### 4.1 Sumber daya alam (Pasal 6)

Modul: `profil-wilayah`, `sda`.

| Huruf | Butir | Modul |
|---|---|---|
| a | Potensi umum: batas dan luas wilayah, iklim, jenis dan kesuburan tanah, orbitasi, bentangan wilayah, letak | `profil-wilayah` |
| b | Pertanian | `sda.pertanian` |
| c | Perkebunan | `sda.perkebunan` |
| d | Kehutanan | `sda.kehutanan` |
| e | Peternakan | `sda.peternakan` |
| f | Perikanan | `sda.perikanan` |
| g | Bahan galian | `sda.bahan-galian` |
| h | Sumber daya air | `sda.air` |
| i | Kualitas lingkungan | `sda.lingkungan` |
| j | Ruang publik/taman | `sda.ruang-publik` |
| k | Wisata | `sda.wisata` |

### 4.2 Sumber daya manusia (Pasal 7)

Modul: `kependudukan`.

Butirnya: (a) jumlah, (b) usia, (c) pendidikan, (d) mata pencaharian pokok, (e) agama dan aliran
kepercayaan, (f) kewarganegaraan, (g) etnis/suku bangsa, (h) cacat fisik dan mental, serta (i) tenaga
kerja. Di situs, butir (h) ditampilkan dengan istilah penyandang disabilitas.

### 4.3 Kelembagaan (Pasal 8)

Modul: `kelembagaan`. Kolom contoh adalah tafsiran untuk keperluan situs, bukan teks Permen.

| Huruf | Butir | Contoh di kelurahan |
|---|---|---|
| a | Lembaga pemerintahan desa/kelurahan | Lurah, sekretaris kelurahan, kepala seksi, kepala lingkungan |
| b | Lembaga kemasyarakatan desa/kelurahan | LPM, RT, RW, TP-PKK, Karang Taruna, posyandu |
| c | Lembaga sosial kemasyarakatan | Organisasi sosial dan keagamaan, panti |
| d | Organisasi profesi | Kelompok tani, nelayan, perajin |
| e | Partai politik | Pengurus partai tingkat kelurahan, bila ada |
| f | Lembaga perekonomian | Koperasi, unit usaha, kelompok simpan pinjam, pasar |
| g | Lembaga pendidikan | PAUD, TK, SD, SMP, SMA, pesantren, lembaga kursus |
| h | Lembaga adat | Pemangku adat setempat |
| i | Lembaga keamanan dan ketertiban | Linmas, poskamling, Bhabinkamtibmas, Babinsa |

### 4.4 Prasarana dan sarana (Pasal 9)

Modul: `prasarana-sarana`.

Butirnya: (a) transportasi, (b) informasi dan komunikasi, (c) air bersih dan sanitasi, (d) irigasi,
(e) pemerintahan, (f) lembaga kemasyarakatan, (g) peribadatan, (h) olahraga, (i) kesehatan,
(j) pendidikan, (k) energi dan penerangan, (l) hiburan dan wisata, serta (m) kebersihan.

## 5. Analisis potensi (Pasal 10 sampai 14)

Modul: `analisis-potensi`.

Data Pasal 6 sampai 9 diukur untuk menentukan tingkat potensi umum, potensi pengembangan, dan tipologi
(Ps 10). Semua persentase di bawah dihitung terhadap skor maksimal.

Tingkat potensi umum (Ps 11 dan 12):

| Kategori | Skor |
|---|---|
| Potensi Tinggi | di atas 80% |
| Potensi Sedang | 60% sampai 80% |
| Potensi Rendah | di bawah 60% |

Potensi pengembangan (Ps 13), dihitung per potensi yang diukur:

| Kategori | Skor |
|---|---|
| Sangat Potensial Dikembangkan | di atas 80% |
| Potensial Dikembangkan | 70% sampai 80% |
| Cukup Potensial Dikembangkan | 60% sampai 70% |
| Kurang Potensial Dikembangkan | di bawah 60% |

Hasil kedua penilaian itu menentukan tipologi desa/kelurahan (Ps 14 ayat 2): (a) persawahan,
(b) perladangan, (c) perkebunan, (d) peternakan, (e) nelayan, (f) pertambangan/galian, (g) kerajinan dan
industri kecil, (h) industri sedang dan besar, atau (i) jasa dan perdagangan.

## 6. Tingkat perkembangan (Pasal 15 sampai 23)

Modul: `tingkat-perkembangan`.

Tingkat perkembangan diukur setiap tahun dan setiap lima tahun dari sembilan variabel (Ps 15). Kolom
kanan menunjukkan kelompok variabel yang dipakai untuk menentukan status kemajuan (Ps 21 sampai 23).

| Huruf | Variabel | Kelompok |
|---|---|---|
| a | Ekonomi masyarakat | Mula |
| b | Pendidikan masyarakat | Mula |
| c | Kesehatan masyarakat | Mula |
| d | Keamanan dan ketertiban | Madya |
| e | Kedaulatan politik masyarakat | Madya |
| f | Peran serta masyarakat dalam pembangunan | Madya |
| g | Lembaga kemasyarakatan | Madya |
| h | Kinerja pemerintahan desa/kelurahan | Lanjut |
| i | Pembinaan dan pengawasan | Lanjut |

Laju perkembangan tahunan (Ps 16 dan 17), dihitung terhadap total skor maksimal per tahun:

| Kategori | Skor |
|---|---|
| Cepat Berkembang | di atas 90% |
| Berkembang | 60% sampai 90% |
| Lamban Berkembang | 30% sampai 60% |
| Kurang Berkembang | di bawah 30% |

Klasifikasi lima tahunan (Ps 18 dan 19):

| Klasifikasi | Skor |
|---|---|
| Swasembada | di atas 80% |
| Swakarya | 60% sampai 80% |
| Swadaya | di bawah 60% |

Status kemajuan (Ps 20 sampai 23) dibagi menjadi kategori Mula, Madya, atau Lanjut. Sebuah kelurahan masuk
suatu kategori bila total skor kelompok variabelnya selama lima tahun berada di bawah ambang yang berlaku
untuk klasifikasinya:

| Klasifikasi | Ambang | Ps |
|---|---|---|
| Swasembada | di bawah 90% | 21 |
| Swakarya | di bawah 70% | 22 |
| Swadaya | di bawah 50% | 23 |

Contoh: kelurahan berklasifikasi swakarya yang total skor ekonomi, kesehatan, dan pendidikannya di bawah 70%
berstatus Swakarya Kategori Mula.

## 7. Penyusunan profil (Bab III, Pasal 24 sampai 40)

### Tahapan dan instrumen

Penyusunan profil meliputi lima kegiatan (Ps 24): menyiapkan instrumen, membentuk Pokja, mengumpulkan data,
mengolah data, dan memublikasikan data. Instrumennya berupa daftar isian data dasar keluarga, potensi, dan
tingkat perkembangan yang tercantum dalam Lampiran I sampai III (Ps 25).

### Pokja profil tingkat kelurahan (Pasal 27)

Modul: `pokja-profil`.

- Penanggung jawab: lurah.
- Ketua: sekretaris kelurahan.
- Anggota: perangkat kelurahan, kepala lingkungan, pengurus lembaga kemasyarakatan, kader pemberdayaan
  masyarakat, dan aparat perangkat daerah di kelurahan dan kecamatan.
- Pokja ditetapkan dengan keputusan lurah.

Pokja juga dibentuk di tingkat kecamatan (Ps 28), kabupaten/kota (Ps 29), dan provinsi (Ps 30).
Penyusunan dan pengolahan dilakukan berjenjang dari kelurahan sampai provinsi (Ps 26 dan 32).

### Sumber informasi dan pengolahan

Data dikumpulkan dari kepala keluarga, pengurus RT dan RW, kepala lingkungan, lurah dan perangkat kelurahan,
pengurus TP-PKK, lembaga kemasyarakatan, serta unit pelaksana teknis instansi daerah dan pusat yang ada di
kelurahan dan kecamatan (Ps 31). Pengolahannya meliputi klarifikasi, tabulasi, kompilasi, dan rekapitulasi,
baik dengan program aplikasi maupun secara manual (Ps 32 dan 33; panduannya di Lampiran IV).

### Hasil pengolahan (Pasal 34)

| Huruf | Hasil | Modul |
|---|---|---|
| a | Kualitas ibu dan anak per dusun/lingkungan (RIAD) | `riad-ibu-anak` |
| b | Tingkatan potensi umum | `analisis-potensi` |
| c | Potensi pengembangan | `analisis-potensi` |
| d | Tipologi sesuai potensi unggulan | `analisis-potensi` |
| e | Laju perkembangan | `tingkat-perkembangan` |
| f | Klasifikasi tingkat perkembangan | `tingkat-perkembangan` |
| g | Kategori status kemajuan | `tingkat-perkembangan` |
| h | Permasalahan kualitas keluarga, tingkatan potensi, faktor pembatas pengembangan, laju, serta tingkat dan kategori perkembangan | `permasalahan-program` |
| i | Indikasi program pembangunan tahun berikutnya | `permasalahan-program` |

### Penyajian, pengesahan, dan publikasi

- Bentuk penyajian (Ps 35): cetak, seperti buku profil dan papan profil, serta digital, seperti CD, *flash disk*, dan audio-video.
- Pengesahan (Ps 36 ayat 1): data profil kelurahan disahkan dan dipublikasikan oleh lurah melalui keputusan lurah.
- Saluran publikasi (Ps 37): surat dinas, media cetak dan elektronik, situs web, serta teknologi informasi
  pemerintahan lainnya. Pasal ini menjadi dasar langsung situs profil kelurahan.
- Distribusi (Ps 38): data disebarkan kepada semua pelaku pemerintahan, pembangunan, pelayanan publik,
  pemberdayaan masyarakat, dan pihak lain yang berkepentingan.

### Jadwal dan periode pendataan

Modul: `jadwal-pendataan`.

| Bulan | Kegiatan (Ps 39) |
|---|---|
| Agustus sampai September | Kepala keluarga mengisi daftar isian data dasar keluarga dan menyerahkannya ke Pokja |
| Oktober | Pokja mengisi daftar isian potensi dan tingkat perkembangan |
| November | Pengolahan data |
| Desember | Publikasi |

Data potensi dikumpulkan dan dipublikasikan setiap tiga tahun. Data dasar keluarga dan tingkat perkembangan
diperbarui setiap tahun dan setiap lima tahun (Ps 40).

## 8. Pendayagunaan data (Bab IV, Pasal 41 dan 42)

Data profil menjadi data dasar bersama bagi pelaku pembangunan untuk merencanakan, melaksanakan, dan
mengevaluasi program penanggulangan kemiskinan, pemberdayaan masyarakat, pelayanan publik, pembinaan
pemerintahan, dan penataan wilayah (Ps 41).

Pasal 42 merinci sepuluh pemanfaatannya:

- (a) mengenali karakteristik potensi dan perkembangan;
- (b) mengukur kecepatan perkembangan;
- (c) mengukur kemajuan dari swadaya ke swakarya menuju swasembada;
- (d) menjadi masukan musyawarah perencanaan pembangunan (Musrenbang);
- (e) menentukan arah pengembangan sesuai tipologi;
- (f) menyelaraskan program pembangunan yang masuk ke kelurahan;
- (g) mendeteksi masalah yang menghambat perkembangan;
- (h) menata administrasi pemerintahan;
- (i) menjadi bahan penilaian lomba desa dan kelurahan;
- (j) menentukan lokasi dan keluarga sasaran program penanggulangan kemiskinan.

## 9. Pelaporan, pembinaan, dan pendanaan (Bab V sampai IX, Pasal 43 sampai 56)

Bagian ini menjadi konteks bagi situs dan tidak memerlukan modul khusus.

- Pelaporan berjenjang: lurah melapor ke camat (Ps 43), camat ke bupati/wali kota (Ps 44), lalu bupati/wali
  kota ke gubernur dan Menteri Dalam Negeri (Ps 45 dan 46). Formatnya ada di Lampiran V.
- Pembinaan dan pengawasan dilakukan oleh pemerintah pusat, provinsi, kabupaten/kota, dan camat (Ps 48
  sampai 52). Camat memfasilitasi pendanaan dalam anggaran kelurahan dan pembentukan Pokja (Ps 52).
- Pendanaan bersumber dari APBN, APBD provinsi, APBD kabupaten/kota, APB Desa melalui alokasi dana desa,
  bantuan luar negeri, dan sumber lain yang sah serta tidak mengikat (Ps 53).
- Kelurahan yang belum mampu mengolah data dapat menyerahkan penyusunannya ke kecamatan atau kabupaten/kota (Ps 54).
