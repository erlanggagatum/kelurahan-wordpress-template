<?php
/**
 * Kelurahan site modules: which features this site includes.
 *
 * DECLARATION ONLY. The theme does not read this file yet. It is the machine-readable twin of
 * docs/guide/modul.md (catalog) and docs/guide/struktur-situs.md (sitemap). Keep all three in sync.
 *
 * Shape:
 *   '<id>' => array(
 *       'label'    => string,                 Indonesian display name.
 *       'group'    => 'inti' | 'pendukung',   inti = from Permendagri 12/2007, pendukung = not from it.
 *       'source'   => string,                 Article reference, or '' for pendukung.
 *       'enabled'  => bool,                   Per-site switch.
 *       'required' => bool,                   Optional. true = cannot be disabled.
 *       'requires' => string[],               Optional. Module IDs that must also be enabled.
 *       'children' => array(                  Optional. Sub-modules; ignored when the parent is disabled.
 *           '<sub-id>' => array( 'label' => string, 'source' => string, 'enabled' => bool ),
 *       ),
 *   )
 *
 * Sub-module full ID = '<id>.<sub-id>', e.g. 'sda.pertanian'.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

return array(

	// ── A. Inti (Permendagri 12/2007) ────────────────────────────────────────

	'profil-wilayah'       => array(
		'label'   => 'Profil Wilayah & Geografi',
		'group'   => 'inti',
		'source'  => 'Ps 6 huruf a',
		'enabled' => true,
	),

	'sda'                  => array(
		'label'    => 'Potensi Sumber Daya Alam',
		'group'    => 'inti',
		'source'   => 'Ps 6 huruf b-k',
		'enabled'  => true,
		'children' => array(
			'pertanian'    => array( 'label' => 'Pertanian', 'source' => 'Ps 6 huruf b', 'enabled' => false ),
			'perkebunan'   => array( 'label' => 'Perkebunan', 'source' => 'Ps 6 huruf c', 'enabled' => false ),
			'kehutanan'    => array( 'label' => 'Kehutanan', 'source' => 'Ps 6 huruf d', 'enabled' => false ),
			'peternakan'   => array( 'label' => 'Peternakan', 'source' => 'Ps 6 huruf e', 'enabled' => false ),
			'perikanan'    => array( 'label' => 'Perikanan', 'source' => 'Ps 6 huruf f', 'enabled' => false ),
			'bahan-galian' => array( 'label' => 'Bahan Galian', 'source' => 'Ps 6 huruf g', 'enabled' => false ),
			'air'          => array( 'label' => 'Sumber Daya Air', 'source' => 'Ps 6 huruf h', 'enabled' => true ),
			'lingkungan'   => array( 'label' => 'Kualitas Lingkungan', 'source' => 'Ps 6 huruf i', 'enabled' => true ),
			'ruang-publik' => array( 'label' => 'Ruang Publik/Taman', 'source' => 'Ps 6 huruf j', 'enabled' => true ),
			'wisata'       => array( 'label' => 'Wisata', 'source' => 'Ps 6 huruf k', 'enabled' => false ),
		),
	),

	'kependudukan'         => array(
		'label'    => 'Kependudukan',
		'group'    => 'inti',
		'source'   => 'Ps 7',
		'enabled'  => true,
		'children' => array(
			'jumlah'          => array( 'label' => 'Jumlah Penduduk', 'source' => 'Ps 7 huruf a', 'enabled' => true ),
			'usia'            => array( 'label' => 'Usia', 'source' => 'Ps 7 huruf b', 'enabled' => true ),
			'pendidikan'      => array( 'label' => 'Pendidikan', 'source' => 'Ps 7 huruf c', 'enabled' => true ),
			'pekerjaan'       => array( 'label' => 'Mata Pencaharian Pokok', 'source' => 'Ps 7 huruf d', 'enabled' => true ),
			'agama'           => array( 'label' => 'Agama dan Aliran Kepercayaan', 'source' => 'Ps 7 huruf e', 'enabled' => true ),
			'kewarganegaraan' => array( 'label' => 'Kewarganegaraan', 'source' => 'Ps 7 huruf f', 'enabled' => false ),
			'etnis'           => array( 'label' => 'Etnis/Suku Bangsa', 'source' => 'Ps 7 huruf g', 'enabled' => false ),
			'disabilitas'     => array( 'label' => 'Penyandang Disabilitas', 'source' => 'Ps 7 huruf h', 'enabled' => true ),
			'tenaga-kerja'    => array( 'label' => 'Tenaga Kerja', 'source' => 'Ps 7 huruf i', 'enabled' => true ),
		),
	),

	'kelembagaan'          => array(
		'label'    => 'Kelembagaan',
		'group'    => 'inti',
		'source'   => 'Ps 8',
		'enabled'  => true,
		'children' => array(
			'pemerintahan'   => array( 'label' => 'Lembaga Pemerintahan Kelurahan', 'source' => 'Ps 8 huruf a', 'enabled' => true ),
			'kemasyarakatan' => array( 'label' => 'Lembaga Kemasyarakatan', 'source' => 'Ps 8 huruf b', 'enabled' => true ),
			'sosial'         => array( 'label' => 'Lembaga Sosial Kemasyarakatan', 'source' => 'Ps 8 huruf c', 'enabled' => false ),
			'profesi'        => array( 'label' => 'Organisasi Profesi', 'source' => 'Ps 8 huruf d', 'enabled' => false ),
			'parpol'         => array( 'label' => 'Partai Politik', 'source' => 'Ps 8 huruf e', 'enabled' => false ),
			'ekonomi'        => array( 'label' => 'Lembaga Perekonomian', 'source' => 'Ps 8 huruf f', 'enabled' => true ),
			'pendidikan'     => array( 'label' => 'Lembaga Pendidikan', 'source' => 'Ps 8 huruf g', 'enabled' => true ),
			'adat'           => array( 'label' => 'Lembaga Adat', 'source' => 'Ps 8 huruf h', 'enabled' => false ),
			'keamanan'       => array( 'label' => 'Lembaga Keamanan dan Ketertiban', 'source' => 'Ps 8 huruf i', 'enabled' => true ),
		),
	),

	'prasarana-sarana'     => array(
		'label'    => 'Prasarana & Sarana',
		'group'    => 'inti',
		'source'   => 'Ps 9',
		'enabled'  => true,
		'children' => array(
			'transportasi'   => array( 'label' => 'Transportasi', 'source' => 'Ps 9 huruf a', 'enabled' => true ),
			'komunikasi'     => array( 'label' => 'Informasi dan Komunikasi', 'source' => 'Ps 9 huruf b', 'enabled' => true ),
			'air-sanitasi'   => array( 'label' => 'Air Bersih dan Sanitasi', 'source' => 'Ps 9 huruf c', 'enabled' => true ),
			'irigasi'        => array( 'label' => 'Irigasi', 'source' => 'Ps 9 huruf d', 'enabled' => false ),
			'pemerintahan'   => array( 'label' => 'Prasarana Pemerintahan', 'source' => 'Ps 9 huruf e', 'enabled' => true ),
			'lembaga'        => array( 'label' => 'Prasarana Lembaga Kemasyarakatan', 'source' => 'Ps 9 huruf f', 'enabled' => true ),
			'ibadah'         => array( 'label' => 'Prasarana Peribadatan', 'source' => 'Ps 9 huruf g', 'enabled' => true ),
			'olahraga'       => array( 'label' => 'Prasarana Olah Raga', 'source' => 'Ps 9 huruf h', 'enabled' => true ),
			'kesehatan'      => array( 'label' => 'Prasarana dan Sarana Kesehatan', 'source' => 'Ps 9 huruf i', 'enabled' => true ),
			'pendidikan'     => array( 'label' => 'Prasarana dan Sarana Pendidikan', 'source' => 'Ps 9 huruf j', 'enabled' => true ),
			'energi'         => array( 'label' => 'Energi dan Penerangan', 'source' => 'Ps 9 huruf k', 'enabled' => true ),
			'hiburan-wisata' => array( 'label' => 'Hiburan dan Wisata', 'source' => 'Ps 9 huruf l', 'enabled' => false ),
			'kebersihan'     => array( 'label' => 'Kebersihan', 'source' => 'Ps 9 huruf m', 'enabled' => true ),
		),
	),

	// Aggregate figures only. Never store per-person or per-KK records (UU 27/2022 PDP).
	'data-keluarga'        => array(
		'label'    => 'Rekap Data Dasar Keluarga',
		'group'    => 'inti',
		'source'   => 'Ps 3',
		'enabled'  => false,
		'children' => array(
			'sdm'           => array( 'label' => 'Potensi SDM Keluarga', 'source' => 'Ps 3 huruf a', 'enabled' => true ),
			'kesehatan'     => array( 'label' => 'Perkembangan Kesehatan', 'source' => 'Ps 3 huruf b', 'enabled' => true ),
			'pendidikan'    => array( 'label' => 'Perkembangan Pendidikan', 'source' => 'Ps 3 huruf c', 'enabled' => true ),
			'aset'          => array( 'label' => 'Aset Ekonomi dan Sosial', 'source' => 'Ps 3 huruf d', 'enabled' => true ),
			'partisipasi'   => array( 'label' => 'Partisipasi Anggota Keluarga', 'source' => 'Ps 3 huruf e', 'enabled' => true ),
			'kesejahteraan' => array( 'label' => 'Permasalahan Kesejahteraan', 'source' => 'Ps 3 huruf f', 'enabled' => true ),
			'kamtib'        => array( 'label' => 'Keamanan dan Ketertiban Lingkungan', 'source' => 'Ps 3 huruf g', 'enabled' => true ),
		),
	),

	'riad-ibu-anak'        => array(
		'label'    => 'Kualitas Ibu & Anak (RIAD)',
		'group'    => 'inti',
		'source'   => 'Ps 1 angka 16, Ps 4, Ps 34 huruf a',
		'enabled'  => false,
		'requires' => array( 'data-keluarga' ),
	),

	'analisis-potensi'     => array(
		'label'    => 'Tingkat Potensi & Tipologi',
		'group'    => 'inti',
		'source'   => 'Ps 10-14, Ps 34 huruf b-d',
		'enabled'  => true,
		'children' => array(
			'potensi-umum'         => array( 'label' => 'Tingkat Potensi Umum', 'source' => 'Ps 11-12', 'enabled' => true ),
			'potensi-pengembangan' => array( 'label' => 'Potensi Pengembangan', 'source' => 'Ps 13', 'enabled' => true ),
			'tipologi'             => array( 'label' => 'Tipologi', 'source' => 'Ps 14', 'enabled' => true ),
		),
	),

	'tingkat-perkembangan' => array(
		'label'    => 'Tingkat Perkembangan',
		'group'    => 'inti',
		'source'   => 'Ps 15-23, Ps 34 huruf e-g',
		'enabled'  => true,
		'children' => array(
			'variabel'    => array( 'label' => 'Skor 9 Variabel', 'source' => 'Ps 15', 'enabled' => true ),
			'laju'        => array( 'label' => 'Laju Perkembangan Tahunan', 'source' => 'Ps 16-17', 'enabled' => true ),
			'klasifikasi' => array( 'label' => 'Klasifikasi Lima Tahunan', 'source' => 'Ps 18-19', 'enabled' => true ),
			'status'      => array( 'label' => 'Status Kemajuan', 'source' => 'Ps 20-23', 'enabled' => true ),
		),
	),

	'permasalahan-program' => array(
		'label'   => 'Permasalahan & Indikasi Program',
		'group'   => 'inti',
		'source'  => 'Ps 34 huruf h-i, Ps 42 huruf d',
		'enabled' => true,
	),

	'dokumen-profil'       => array(
		'label'   => 'Dokumen & Publikasi Profil',
		'group'   => 'inti',
		'source'  => 'Ps 35-37',
		'enabled' => true,
	),

	'pokja-profil'         => array(
		'label'   => 'Pokja Profil Kelurahan',
		'group'   => 'inti',
		'source'  => 'Ps 27',
		'enabled' => true,
	),

	'jadwal-pendataan'     => array(
		'label'   => 'Jadwal & Siklus Pendataan',
		'group'   => 'inti',
		'source'  => 'Ps 39-40',
		'enabled' => false,
	),

	// ── B. Pendukung (not from Permendagri 12/2007) ─────────────────────────

	'beranda'              => array(
		'label'    => 'Beranda',
		'group'    => 'pendukung',
		'source'   => '',
		'enabled'  => true,
		'required' => true,
	),

	'tentang'              => array(
		'label'    => 'Tentang Kelurahan',
		'group'    => 'pendukung',
		'source'   => '',
		'enabled'  => true,
		'children' => array(
			'sejarah'   => array( 'label' => 'Sejarah', 'source' => '', 'enabled' => true ),
			'visi-misi' => array( 'label' => 'Visi & Misi', 'source' => '', 'enabled' => true ),
		),
	),

	'berita'               => array(
		'label'   => 'Berita & Pengumuman',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => true,
	),

	'agenda'               => array(
		'label'   => 'Agenda Kegiatan',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => true,
	),

	'galeri'               => array(
		'label'   => 'Galeri',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => true,
	),

	'layanan'              => array(
		'label'   => 'Layanan Administrasi',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => true,
	),

	'ppid'                 => array(
		'label'   => 'Informasi Publik (PPID)',
		'group'   => 'pendukung',
		'source'  => 'UU 14/2008 (KIP)',
		'enabled' => true,
	),

	'pengaduan'            => array(
		'label'   => 'Pengaduan Masyarakat',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => true,
	),

	'produk-hukum'         => array(
		'label'   => 'Produk Hukum',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => false,
	),

	'umkm'                 => array(
		'label'   => 'UMKM & Produk Lokal',
		'group'   => 'pendukung',
		'source'  => '',
		'enabled' => false,
	),

	'kontak'               => array(
		'label'    => 'Kontak & Lokasi',
		'group'    => 'pendukung',
		'source'   => '',
		'enabled'  => true,
		'required' => true,
	),
);
