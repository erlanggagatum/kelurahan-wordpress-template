<?php
/**
 * Kelurahan site modules: which features this site includes.
 *
 * Read by theme/inc/modules.php: kelurahan_module_enabled( 'sda.air' ), kelurahan_module_url( 'layanan' ),
 * and the block class "kelurahan-module-<id>". Machine-readable twin of docs/guide/modul.md (catalog) and
 * docs/guide/struktur-situs.md (sitemap). Keep all three in sync. `php bin/check.php` validates the shape.
 *
 * Shape:
 *   '<id>' => array(
 *       'label'    => string,                 Indonesian display name.
 *       'group'    => 'inti' | 'pendukung',   inti = from Permendagri 12/2007, pendukung = not from it.
 *       'source'   => string,                 Article reference, or '' for pendukung.
 *       'enabled'  => bool,                   Per-site switch.
 *       'path'     => string,                 Optional. Page URL path from struktur-situs.md, e.g. '/layanan/'.
 *       'required' => bool,                   Optional. true = cannot be disabled.
 *       'requires' => string[],               Optional. Module IDs that must also be enabled.
 *       'children' => array(                  Optional. Sub-modules; off when the parent is off.
 *           '<sub-id>' => array( 'label' => string, 'source' => string, 'enabled' => bool, 'path' => string ),
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
		'label'   => 'Wilayah dan geografi',
		'group'   => 'inti',
		'source'  => 'Ps 6 huruf a',
		'path'    => '/profil/wilayah/',
		'enabled' => true,
	),

	'sda'                  => array(
		'label'    => 'Potensi sumber daya alam',
		'group'    => 'inti',
		'source'   => 'Ps 6 huruf b-k',
		'path'     => '/profil/potensi-sda/',
		'enabled'  => true,
		'children' => array(
			'pertanian'    => array( 'label' => 'Pertanian', 'source' => 'Ps 6 huruf b', 'enabled' => false ),
			'perkebunan'   => array( 'label' => 'Perkebunan', 'source' => 'Ps 6 huruf c', 'enabled' => false ),
			'kehutanan'    => array( 'label' => 'Kehutanan', 'source' => 'Ps 6 huruf d', 'enabled' => false ),
			'peternakan'   => array( 'label' => 'Peternakan', 'source' => 'Ps 6 huruf e', 'enabled' => false ),
			'perikanan'    => array( 'label' => 'Perikanan', 'source' => 'Ps 6 huruf f', 'enabled' => false ),
			'bahan-galian' => array( 'label' => 'Bahan galian', 'source' => 'Ps 6 huruf g', 'enabled' => false ),
			'air'          => array( 'label' => 'Sumber daya air', 'source' => 'Ps 6 huruf h', 'enabled' => true ),
			'lingkungan'   => array( 'label' => 'Kualitas lingkungan', 'source' => 'Ps 6 huruf i', 'enabled' => true ),
			'ruang-publik' => array( 'label' => 'Ruang publik dan taman', 'source' => 'Ps 6 huruf j', 'enabled' => true ),
			'wisata'       => array( 'label' => 'Wisata', 'source' => 'Ps 6 huruf k', 'enabled' => false ),
		),
	),

	'kependudukan'         => array(
		'label'    => 'Kependudukan',
		'group'    => 'inti',
		'source'   => 'Ps 7',
		'path'     => '/data/kependudukan/',
		'enabled'  => true,
		'children' => array(
			'jumlah'          => array( 'label' => 'Jumlah penduduk', 'source' => 'Ps 7 huruf a', 'enabled' => true ),
			'usia'            => array( 'label' => 'Usia', 'source' => 'Ps 7 huruf b', 'enabled' => true ),
			'pendidikan'      => array( 'label' => 'Pendidikan', 'source' => 'Ps 7 huruf c', 'enabled' => true ),
			'pekerjaan'       => array( 'label' => 'Mata pencaharian pokok', 'source' => 'Ps 7 huruf d', 'enabled' => true ),
			'agama'           => array( 'label' => 'Agama dan aliran kepercayaan', 'source' => 'Ps 7 huruf e', 'enabled' => true ),
			'kewarganegaraan' => array( 'label' => 'Kewarganegaraan', 'source' => 'Ps 7 huruf f', 'enabled' => false ),
			'etnis'           => array( 'label' => 'Etnis dan suku bangsa', 'source' => 'Ps 7 huruf g', 'enabled' => false ),
			'disabilitas'     => array( 'label' => 'Penyandang disabilitas', 'source' => 'Ps 7 huruf h', 'enabled' => true ),
			'tenaga-kerja'    => array( 'label' => 'Tenaga kerja', 'source' => 'Ps 7 huruf i', 'enabled' => true ),
		),
	),

	'kelembagaan'          => array(
		'label'    => 'Kelembagaan',
		'group'    => 'inti',
		'source'   => 'Ps 8',
		'path'     => '/lembaga/',
		'enabled'  => true,
		'children' => array(
			'pemerintahan'   => array( 'label' => 'Lembaga pemerintahan kelurahan', 'source' => 'Ps 8 huruf a', 'enabled' => true ),
			'kemasyarakatan' => array( 'label' => 'Lembaga kemasyarakatan', 'source' => 'Ps 8 huruf b', 'enabled' => true ),
			'sosial'         => array( 'label' => 'Lembaga sosial kemasyarakatan', 'source' => 'Ps 8 huruf c', 'enabled' => false ),
			'profesi'        => array( 'label' => 'Organisasi profesi', 'source' => 'Ps 8 huruf d', 'enabled' => false ),
			'parpol'         => array( 'label' => 'Partai politik', 'source' => 'Ps 8 huruf e', 'enabled' => false ),
			'ekonomi'        => array( 'label' => 'Lembaga perekonomian', 'source' => 'Ps 8 huruf f', 'enabled' => true ),
			'pendidikan'     => array( 'label' => 'Lembaga pendidikan', 'source' => 'Ps 8 huruf g', 'enabled' => true ),
			'adat'           => array( 'label' => 'Lembaga adat', 'source' => 'Ps 8 huruf h', 'enabled' => false ),
			'keamanan'       => array( 'label' => 'Lembaga keamanan dan ketertiban', 'source' => 'Ps 8 huruf i', 'enabled' => true ),
		),
	),

	'prasarana-sarana'     => array(
		'label'    => 'Prasarana dan sarana',
		'group'    => 'inti',
		'source'   => 'Ps 9',
		'path'     => '/data/prasarana-sarana/',
		'enabled'  => true,
		'children' => array(
			'transportasi'   => array( 'label' => 'Transportasi', 'source' => 'Ps 9 huruf a', 'enabled' => true ),
			'komunikasi'     => array( 'label' => 'Informasi dan komunikasi', 'source' => 'Ps 9 huruf b', 'enabled' => true ),
			'air-sanitasi'   => array( 'label' => 'Air bersih dan sanitasi', 'source' => 'Ps 9 huruf c', 'enabled' => true ),
			'irigasi'        => array( 'label' => 'Irigasi', 'source' => 'Ps 9 huruf d', 'enabled' => false ),
			'pemerintahan'   => array( 'label' => 'Prasarana pemerintahan', 'source' => 'Ps 9 huruf e', 'enabled' => true ),
			'lembaga'        => array( 'label' => 'Prasarana lembaga kemasyarakatan', 'source' => 'Ps 9 huruf f', 'enabled' => true ),
			'ibadah'         => array( 'label' => 'Prasarana peribadatan', 'source' => 'Ps 9 huruf g', 'enabled' => true ),
			'olahraga'       => array( 'label' => 'Prasarana olahraga', 'source' => 'Ps 9 huruf h', 'enabled' => true ),
			'kesehatan'      => array( 'label' => 'Prasarana dan sarana kesehatan', 'source' => 'Ps 9 huruf i', 'enabled' => true ),
			'pendidikan'     => array( 'label' => 'Prasarana dan sarana pendidikan', 'source' => 'Ps 9 huruf j', 'enabled' => true ),
			'energi'         => array( 'label' => 'Energi dan penerangan', 'source' => 'Ps 9 huruf k', 'enabled' => true ),
			'hiburan-wisata' => array( 'label' => 'Hiburan dan wisata', 'source' => 'Ps 9 huruf l', 'enabled' => false ),
			'kebersihan'     => array( 'label' => 'Kebersihan', 'source' => 'Ps 9 huruf m', 'enabled' => true ),
		),
	),

	// Aggregate figures only. Never store per-person or per-KK records (UU 27/2022 PDP).
	'data-keluarga'        => array(
		'label'    => 'Rekap data dasar keluarga',
		'group'    => 'inti',
		'source'   => 'Ps 3',
		'path'     => '/data/keluarga/',
		'enabled'  => false,
		'children' => array(
			'sdm'           => array( 'label' => 'Potensi SDM keluarga', 'source' => 'Ps 3 huruf a', 'enabled' => true ),
			'kesehatan'     => array( 'label' => 'Perkembangan kesehatan', 'source' => 'Ps 3 huruf b', 'enabled' => true ),
			'pendidikan'    => array( 'label' => 'Perkembangan pendidikan', 'source' => 'Ps 3 huruf c', 'enabled' => true ),
			'aset'          => array( 'label' => 'Aset ekonomi dan sosial', 'source' => 'Ps 3 huruf d', 'enabled' => true ),
			'partisipasi'   => array( 'label' => 'Partisipasi anggota keluarga', 'source' => 'Ps 3 huruf e', 'enabled' => true ),
			'kesejahteraan' => array( 'label' => 'Permasalahan kesejahteraan', 'source' => 'Ps 3 huruf f', 'enabled' => true ),
			'kamtib'        => array( 'label' => 'Keamanan dan ketertiban lingkungan', 'source' => 'Ps 3 huruf g', 'enabled' => true ),
		),
	),

	'riad-ibu-anak'        => array(
		'label'    => 'Kualitas ibu dan anak (RIAD)',
		'group'    => 'inti',
		'source'   => 'Ps 1 angka 16, Ps 4, Ps 34 huruf a',
		'path'     => '/data/ibu-anak/',
		'enabled'  => false,
		'requires' => array( 'data-keluarga' ),
	),

	'analisis-potensi'     => array(
		'label'    => 'Tingkat potensi dan tipologi',
		'group'    => 'inti',
		'source'   => 'Ps 10-14, Ps 34 huruf b-d',
		'path'     => '/perkembangan/potensi-tipologi/',
		'enabled'  => true,
		'children' => array(
			'potensi-umum'         => array( 'label' => 'Tingkat potensi umum', 'source' => 'Ps 11-12', 'enabled' => true ),
			'potensi-pengembangan' => array( 'label' => 'Potensi pengembangan', 'source' => 'Ps 13', 'enabled' => true ),
			'tipologi'             => array( 'label' => 'Tipologi', 'source' => 'Ps 14', 'enabled' => true ),
		),
	),

	'tingkat-perkembangan' => array(
		'label'    => 'Tingkat perkembangan',
		'group'    => 'inti',
		'source'   => 'Ps 15-23, Ps 34 huruf e-g',
		'path'     => '/perkembangan/tingkat/',
		'enabled'  => true,
		'children' => array(
			'variabel'    => array( 'label' => 'Skor sembilan variabel', 'source' => 'Ps 15', 'enabled' => true ),
			'laju'        => array( 'label' => 'Laju perkembangan tahunan', 'source' => 'Ps 16-17', 'enabled' => true ),
			'klasifikasi' => array( 'label' => 'Klasifikasi lima tahunan', 'source' => 'Ps 18-19', 'enabled' => true ),
			'status'      => array( 'label' => 'Status kemajuan', 'source' => 'Ps 20-23', 'enabled' => true ),
		),
	),

	'permasalahan-program' => array(
		'label'   => 'Permasalahan dan indikasi program',
		'group'   => 'inti',
		'source'  => 'Ps 34 huruf h-i, Ps 42 huruf d',
		'path'    => '/perkembangan/program/',
		'enabled' => true,
	),

	'dokumen-profil'       => array(
		'label'   => 'Dokumen profil',
		'group'   => 'inti',
		'source'  => 'Ps 35-37',
		'path'    => '/publikasi/profil/',
		'enabled' => true,
	),

	'pokja-profil'         => array(
		'label'   => 'Pokja profil kelurahan',
		'group'   => 'inti',
		'source'  => 'Ps 27',
		'path'    => '/pemerintahan/pokja-profil/',
		'enabled' => true,
	),

	'jadwal-pendataan'     => array(
		'label'   => 'Jadwal pendataan',
		'group'   => 'inti',
		'source'  => 'Ps 39-40',
		'path'    => '/publikasi/jadwal-pendataan/',
		'enabled' => false,
	),

	// ── B. Pendukung (not from Permendagri 12/2007) ─────────────────────────

	'beranda'              => array(
		'label'    => 'Beranda',
		'group'    => 'pendukung',
		'source'   => '',
		'path'     => '/',
		'enabled'  => true,
		'required' => true,
	),

	'tentang'              => array(
		'label'    => 'Tentang kelurahan',
		'group'    => 'pendukung',
		'source'   => '',
		'enabled'  => true,
		'children' => array(
			'sejarah'   => array( 'label' => 'Sejarah', 'source' => '', 'enabled' => true, 'path' => '/profil/sejarah/' ),
			'visi-misi' => array( 'label' => 'Visi dan misi', 'source' => '', 'enabled' => true, 'path' => '/profil/visi-misi/' ),
		),
	),

	'berita'               => array(
		'label'   => 'Berita dan pengumuman',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/berita/',
		'enabled' => true,
	),

	'agenda'               => array(
		'label'   => 'Agenda kegiatan',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/agenda/',
		'enabled' => true,
	),

	'galeri'               => array(
		'label'   => 'Galeri',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/galeri/',
		'enabled' => true,
	),

	'layanan'              => array(
		'label'   => 'Layanan administrasi',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/layanan/',
		'enabled' => true,
	),

	'ppid'                 => array(
		'label'   => 'Informasi publik (PPID)',
		'group'   => 'pendukung',
		'source'  => 'UU 14/2008 (KIP)',
		'path'    => '/ppid/',
		'enabled' => true,
	),

	'pengaduan'            => array(
		'label'   => 'Pengaduan masyarakat',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/layanan/pengaduan/',
		'enabled' => true,
	),

	'produk-hukum'         => array(
		'label'   => 'Produk hukum',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/publikasi/produk-hukum/',
		'enabled' => false,
	),

	'umkm'                 => array(
		'label'   => 'UMKM dan produk lokal',
		'group'   => 'pendukung',
		'source'  => '',
		'path'    => '/umkm/',
		'enabled' => false,
	),

	'kontak'               => array(
		'label'    => 'Kontak dan lokasi',
		'group'    => 'pendukung',
		'source'   => '',
		'path'     => '/kontak/',
		'enabled'  => true,
		'required' => true,
	),
);
