# Ringkasan Permendagri No. 12 Tahun 2007

**Pedoman Penyusunan dan Pendayagunaan Data Profil Desa dan Kelurahan**
Ditetapkan di Jakarta, 12 Maret 2007. Mencabut Kepmendagri No. 25 Tahun 1996 tentang Data Dasar Profil Desa/Kelurahan.

Sumber: [`Permen 12-2007-Profil Desa dan Kelurahan.pdf`](Permen%2012-2007-Profil%20Desa%20dan%20Kelurahan.pdf).
Dokumen ini hanya mengekstrak batang tubuh (Pasal 1–56). **Lampiran I–V tidak tersedia** di salinan PDF
("Lampiran tidak dicantumkan"). Isi lampiran: daftar isian, indikator, skor, panduan teknis, dan format laporan.

Setiap butir di bawah menyebut pasal sumbernya. Kode di kolom **Modul** merujuk ke [modul.md](modul.md).

---

## 1. Definisi penting (BAB I, Pasal 1)

| Istilah | Arti ringkas | Ps |
|---|---|---|
| Kelurahan | Wilayah kerja lurah sebagai perangkat daerah kabupaten/kota dalam wilayah kerja kecamatan | 1(2) |
| Profil Desa dan Kelurahan | Gambaran menyeluruh karakter desa/kelurahan: data dasar keluarga, potensi SDA, SDM, kelembagaan, prasarana & sarana, perkembangan kemajuan, dan permasalahan | 1(3) |
| Penyusunan | Pengumpulan, pengolahan, dan **publikasi** data profil | 1(4) |
| Pendayagunaan | Pemanfaatan data profil untuk perencanaan dan evaluasi kinerja pemerintahan, pembangunan, dan pembinaan kemasyarakatan | 1(5) |
| Potensi Desa dan Kelurahan | Seluruh sumber daya (SDM, SDA, kelembagaan, prasarana & sarana) untuk percepatan kesejahteraan | 1(8) |
| Tingkat Perkembangan | Status capaian hasil pembangunan yang mencerminkan kemajuan masyarakat dan pemerintahan | 1(9) |
| Kategori Mula | Prioritas penanganan: kebutuhan dasar (ekonomi, pendidikan, kesehatan) | 1(11) |
| Kategori Madya | Prioritas: keamanan & ketertiban, kesadaran politik & kebangsaan, peran serta masyarakat, kinerja lembaga kemasyarakatan | 1(12) |
| Kategori Lanjut | Prioritas: kinerja pemerintahan desa/kelurahan, pembinaan & pengawasan | 1(13) |
| Desa/Kelurahan Miskin | Potensi umumnya rendah, laju lamban/kurang berkembang, status swadaya (mula/madya/lanjut) | 1(14) |
| Data Dasar Keluarga | Potensi & perkembangan keluarga (lihat §3) | 1(15) |
| RIAD | Registrasi Ibu dan Anak tingkat Dusun dan lingkungan: pengumpulan, analisis, publikasi data perkembangan ibu & anak per dusun/lingkungan | 1(16) |
| Tipologi | Kondisi spesifik keunggulan potensi yang menentukan arah pengembangan | 1(17) |
| Program Aplikasi | Alat bantu komputer untuk mengolah, menganalisis, dan menyajikan data profil | 1(10) |

## 2. Struktur data profil (BAB II, Pasal 2)

Profil = **Data Dasar Keluarga** + **Potensi Desa/Kelurahan** + **Tingkat Perkembangan Desa/Kelurahan**.

```
Profil Kelurahan
├── Data Dasar Keluarga (Ps 3–4) ──► RIAD (ibu & anak)
├── Potensi (Ps 5–14)
│   ├── Sumber Daya Alam (Ps 6)
│   ├── Sumber Daya Manusia (Ps 7)
│   ├── Kelembagaan (Ps 8)
│   ├── Prasarana & Sarana (Ps 9)
│   └── Analisis: tingkat potensi umum, potensi pengembangan, tipologi (Ps 10–14)
└── Tingkat Perkembangan (Ps 15–23)
    ├── Laju tahunan (4 kategori)
    ├── Klasifikasi 5-tahunan (swadaya / swakarya / swasembada)
    └── Status kemajuan (mula / madya / lanjut)
```

## 3. Data Dasar Keluarga (Pasal 3–4) → modul `data-keluarga`, `riad-ibu-anak`

| Huruf | Butir |
|---|---|
| a | Potensi sumber daya manusia |
| b | Perkembangan kesehatan |
| c | Perkembangan pendidikan |
| d | Penguasaan aset ekonomi dan sosial keluarga |
| e | Partisipasi anggota keluarga dalam pemerintahan, pembangunan, dan kemasyarakatan |
| f | Permasalahan kesejahteraan keluarga |
| g | Perkembangan keamanan dan ketertiban di lingkungannya |

Pasal 4: data dasar keluarga menjadi dasar perhitungan perkembangan kualitas manusia melalui **RIAD**.
Daftar isian diisi kepala keluarga (Ps 39(1)).

> Catatan situs: data per keluarga adalah **data pribadi**. Situs hanya menampilkan rekap/agregat.

## 4. Potensi (Pasal 5–9)

### 4.1 Sumber Daya Alam (Pasal 6) → modul `profil-wilayah`, `sda`

| Huruf | Butir | Modul |
|---|---|---|
| a | Potensi umum: batas & luas wilayah, iklim, jenis & kesuburan tanah, orbitasi, bentangan wilayah, letak | `profil-wilayah` |
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

### 4.2 Sumber Daya Manusia (Pasal 7) → modul `kependudukan`

a. jumlah · b. usia · c. pendidikan · d. mata pencaharian pokok · e. agama dan aliran kepercayaan ·
f. kewarganegaraan · g. etnis/suku bangsa · h. cacat fisik dan mental (penyandang disabilitas) · i. tenaga kerja.

### 4.3 Kelembagaan (Pasal 8) → modul `kelembagaan`

| Huruf | Butir | Contoh di kelurahan |
|---|---|---|
| a | Lembaga pemerintahan desa/kelurahan | Lurah, Sekretaris Kelurahan, seksi-seksi, Kepala Lingkungan |
| b | Lembaga kemasyarakatan desa/kelurahan | LPM, RT, RW, TP-PKK, Karang Taruna, Posyandu |
| c | Lembaga sosial kemasyarakatan | Organisasi sosial, keagamaan, panti |
| d | Organisasi profesi | Kelompok tani, nelayan, pengrajin, IBI/IDI tingkat lokal |
| e | Partai politik | Pengurus parpol tingkat kelurahan (jika ada) |
| f | Lembaga perekonomian | Koperasi, BUMDes/unit usaha, kelompok simpan pinjam, pasar |
| g | Lembaga pendidikan | PAUD, TK, SD, SMP, SMA, pesantren, kursus |
| h | Lembaga adat | Lembaga/pemangku adat setempat |
| i | Lembaga keamanan dan ketertiban | Linmas, Siskamling/Poskamling, Bhabinkamtibmas, Babinsa |

(Kolom "contoh" adalah tafsiran untuk situs, bukan teks Permen.)

### 4.4 Prasarana dan Sarana (Pasal 9) → modul `prasarana-sarana`

a. transportasi · b. informasi dan komunikasi · c. air bersih dan sanitasi · d. irigasi ·
e. pemerintahan · f. lembaga kemasyarakatan · g. peribadatan · h. olah raga · i. kesehatan ·
j. pendidikan · k. energi dan penerangan · l. hiburan dan wisata · m. kebersihan.

## 5. Analisis potensi (Pasal 10–14) → modul `analisis-potensi`

Data Ps 6–9 diukur untuk menentukan tingkat potensi umum, potensi pengembangan, dan tipologi (Ps 10).

**Tingkat potensi umum** (Ps 11–12), % dari skor maksimal:

| Kategori | Skor |
|---|---|
| Potensi Tinggi | > 80% |
| Potensi Sedang | 60–80% |
| Potensi Rendah | < 60% |

**Potensi pengembangan** (Ps 13), % skor indikator dari maksimal potensi yang diukur:

| Kategori | Skor |
|---|---|
| Sangat Potensial Dikembangkan | > 80% |
| Potensial Dikembangkan | 70–80% |
| Cukup Potensial Dikembangkan | 60–70% |
| Kurang Potensial Dikembangkan | < 60% |

**Tipologi** (Ps 14(2)), ditentukan dari hasil skor potensi umum dan potensi pengembangan:
a. persawahan · b. perladangan · c. perkebunan · d. peternakan · e. nelayan · f. pertambangan/galian ·
g. kerajinan dan industri kecil · h. industri sedang dan besar · i. jasa dan perdagangan.

## 6. Tingkat perkembangan (Pasal 15–23) → modul `tingkat-perkembangan`

**9 variabel** (Ps 15), diukur tiap tahun dan tiap lima tahun:

| # | Variabel | Kelompok status kemajuan |
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

**Laju perkembangan tahunan** (Ps 16–17), % dari total skor maksimal per tahun:

| Kategori | Skor |
|---|---|
| Cepat Berkembang | > 90% |
| Berkembang | 60–90% |
| Lamban Berkembang | 30–60% |
| Kurang Berkembang | < 30% |

**Klasifikasi lima tahunan** (Ps 18–19):

| Klasifikasi | Skor |
|---|---|
| Swasembada | > 80% |
| Swakarya | 60–80% |
| Swadaya | < 60% |

**Status kemajuan** (Ps 20–23): kategori Mula, Madya, atau Lanjut. Untuk tiap klasifikasi, kategori
ditentukan bila total skor kelompok variabel (lihat tabel 9 variabel) selama lima tahun **kurang dari** ambang:

| Klasifikasi | Ambang | Ps |
|---|---|---|
| Swasembada | < 90% | 21 |
| Swakarya | < 70% | 22 |
| Swadaya | < 50% | 23 |

Contoh: kelurahan Swakarya dengan skor gabungan ekonomi+kesehatan+pendidikan < 70% → **Swakarya Kategori Mula**.

## 7. Penyusunan profil (BAB III, Pasal 24–40)

**Tahapan** (Ps 24): (a) siapkan instrumen · (b) bentuk Pokja · (c) kumpulkan data · (d) olah data · (e) **publikasi**.

**Instrumen** (Ps 25): daftar isian data dasar keluarga, potensi, dan tingkat perkembangan (Lampiran I–III).

**Pokja Profil tingkat kelurahan** (Ps 27) → modul `pokja-profil`:
- Penanggung jawab: **Lurah**
- Ketua: **Sekretaris Kelurahan**
- Anggota: perangkat kelurahan, Kepala Lingkungan, pengurus lembaga kemasyarakatan, kader pemberdayaan
  masyarakat, aparat perangkat daerah di kelurahan/kecamatan
- Ditetapkan dengan **Keputusan Lurah**

Pokja juga ada di tingkat kecamatan (Ps 28), kabupaten/kota (Ps 29), dan provinsi (Ps 30). Penyusunan berjenjang (Ps 26, 32).

**Sumber informasi** (Ps 31): kepala keluarga, pengurus RT/RW, kepala lingkungan, lurah & perangkat,
TP-PKK, lembaga kemasyarakatan, UPT SKPD dan instansi pusat di kelurahan/kecamatan.

**Pengolahan** (Ps 32–33): klarifikasi, tabulasi, kompilasi, rekapitulasi, lewat program aplikasi atau manual (panduan di Lampiran IV).

**Hasil pengolahan** (Ps 34) → modul terkait:

| Huruf | Hasil | Modul |
|---|---|---|
| a | Kualitas ibu dan anak per dusun/lingkungan (RIAD) | `riad-ibu-anak` |
| b | Tingkatan potensi umum | `analisis-potensi` |
| c | Potensi pengembangan | `analisis-potensi` |
| d | Tipologi sesuai potensi unggulan | `analisis-potensi` |
| e | Laju perkembangan | `tingkat-perkembangan` |
| f | Klasifikasi tingkat perkembangan | `tingkat-perkembangan` |
| g | Kategori status kemajuan | `tingkat-perkembangan` |
| h | Permasalahan: kualitas keluarga, potensi, faktor pembatas, laju & kategori | `permasalahan-program` |
| i | Indikasi program pembangunan tahun berikutnya | `permasalahan-program` |

**Bentuk penyajian** (Ps 35): hardcopy (buku profil, **papan profil**) dan softcopy (CD, flashdisk, audio-video).

**Pengesahan** (Ps 36(1)): data profil kelurahan disahkan dan dipublikasikan oleh Lurah melalui **Keputusan Lurah**.

**Saluran publikasi** (Ps 37): surat dinas, media cetak & elektronik, **website**, dan TI pemerintahan lainnya.
→ Dasar hukum langsung bagi situs profil kelurahan.

**Distribusi** (Ps 38): ke seluruh pelaku pemerintahan, pembangunan, pelayanan publik, pemberdayaan masyarakat, dan pihak berkepentingan.

**Jadwal tahunan** (Ps 39) → modul `jadwal-pendataan`:

| Bulan | Kegiatan |
|---|---|
| Agustus–September | Kepala keluarga mengisi daftar isian data dasar keluarga, diserahkan ke Pokja |
| Oktober | Pokja mengisi daftar isian potensi dan tingkat perkembangan |
| November | Pengolahan data |
| Desember | **Publikasi** |

**Periodisasi** (Ps 40): data potensi diperbarui **tiap 3 tahun**. Data dasar keluarga dan tingkat perkembangan diperbarui **tiap tahun dan tiap 5 tahun**.

## 8. Pendayagunaan data (BAB IV, Pasal 41–42)

Data menjadi data dasar bersama untuk perencanaan, pelaksanaan, evaluasi kebijakan penanggulangan
kemiskinan, pemberdayaan masyarakat, pelayanan publik, pembinaan pemerintahan, dan penataan wilayah (Ps 41).

Pemanfaatan (Ps 42): (a) mengetahui karakteristik potensi dan perkembangan · (b) mengukur kecepatan
perkembangan · (c) mengukur status kemajuan swadaya → swakarya → swasembada · (d) **input Musrenbang** ·
(e) arah pengembangan sesuai tipologi · (f) sinkronisasi program masuk kelurahan · (g) deteksi masalah ·
(h) penataan administrasi · (i) bahan penilaian lomba desa/kelurahan · (j) penentuan sasaran program penanggulangan kemiskinan.

## 9. Pelaporan, pembinaan, pendanaan (BAB V–IX, Pasal 43–56)

- Pelaporan berjenjang: Lurah → Camat (Ps 43) → Bupati/Walikota (Ps 44) → Gubernur & Mendagri (Ps 45–46). Format di Lampiran V.
- Pembinaan dan pengawasan oleh Pemerintah, Provinsi, Kabupaten/Kota, Camat (Ps 48–52). Camat memfasilitasi dana di anggaran kelurahan dan pembentukan Pokja (Ps 52).
- Pendanaan: APBN, APBD provinsi, APBD kab/kota, APBDes, bantuan luar negeri, sumber lain yang sah (Ps 53).
- Bila kelurahan belum mampu mengolah data, penyusunan dilakukan di kecamatan atau kab/kota (Ps 54).

Bagian ini relevan untuk situs hanya sebagai konteks. Tidak ada modul khusus untuk itu.
