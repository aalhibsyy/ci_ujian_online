-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Jan 2020 pada 21.32
-- Versi server: 10.4.10-MariaDB
-- Versi PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ptkai`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_evaluasi`
--

CREATE TABLE `detail_evaluasi` (
  `id_detail_evaluasi` int(15) NOT NULL,
  `evaluasi_kd` int(15) NOT NULL,
  `penilaian_id` int(15) NOT NULL,
  `nilai` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `diklat`
--

CREATE TABLE `diklat` (
  `id_diklat` int(11) NOT NULL,
  `nama_diklat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `diklat`
--

INSERT INTO `diklat` (`id_diklat`, `nama_diklat`) VALUES
(1, 'Diklat A'),
(2, 'Diklat B'),
(3, 'diklat data');

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluasi`
--

CREATE TABLE `evaluasi` (
  `kd_evaluasi` int(15) NOT NULL,
  `materi_id` int(15) NOT NULL,
  `siswa_id` int(15) NOT NULL,
  `kritik_saran` varchar(100) DEFAULT NULL,
  `inspirasi` varchar(100) DEFAULT NULL,
  `perbedaan` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'pengajar', 'Pembuat Soal dan ujian'),
(3, 'siswa', 'Peserta Ujian');

-- --------------------------------------------------------

--
-- Struktur dari tabel `h_ujian`
--

CREATE TABLE `h_ujian` (
  `id` int(11) NOT NULL,
  `ujian_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `list_soal` longtext NOT NULL,
  `list_jawaban` longtext NOT NULL,
  `jml_benar` int(11) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `nilai_bobot` decimal(10,2) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `h_ujian`
--

INSERT INTO `h_ujian` (`id`, `ujian_id`, `siswa_id`, `list_soal`, `list_jawaban`, `jml_benar`, `nilai`, `nilai_bobot`, `tgl_mulai`, `tgl_selesai`, `status`) VALUES
(8, 5, 1, '8,10,9', '8:B:N,10:A:N,9:D:N', 1, '33.00', '1.00', '2020-01-24 18:09:11', '2020-01-24 18:24:11', 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(30) NOT NULL,
  `jurusan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `jurusan_id`) VALUES
(1, '12.1E.13', 1),
(2, '11.1A.13', 1),
(3, '10.1D.13', 1),
(7, '12.1A.10', 2),
(8, '12.1B.10', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(16, '::1', '11111111', 1580065982),
(19, '::1', '12183018', 1580070559),
(22, '::1', 'siswa@gmail.com', 1580070700),
(23, '::1', 'siswa@gmail.com', 1580070703),
(24, '::1', 'siswa@gmail.com', 1580070708);

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi`
--

CREATE TABLE `materi` (
  `id_materi` int(15) NOT NULL,
  `nama_materi` varchar(50) NOT NULL,
  `diklat_id` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `materi`
--

INSERT INTO `materi` (`id_materi`, `nama_materi`, `diklat_id`) VALUES
(1, 'Materi A', 1),
(2, 'Materi B', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_ujian`
--

CREATE TABLE `m_ujian` (
  `id_ujian` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `diklat_id` int(11) NOT NULL,
  `nama_ujian` varchar(200) NOT NULL,
  `jumlah_soal` int(11) NOT NULL,
  `waktu` int(11) NOT NULL,
  `jenis` enum('acak','urut') NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `terlambat` datetime NOT NULL,
  `token` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `m_ujian`
--

INSERT INTO `m_ujian` (`id_ujian`, `pengajar_id`, `diklat_id`, `nama_ujian`, `jumlah_soal`, `waktu`, `jenis`, `tgl_mulai`, `terlambat`, `token`) VALUES
(5, 1, 1, 'test nama ujian', 3, 15, 'acak', '2020-01-24 17:27:00', '2020-01-31 17:27:03', 'FUXBV'),
(6, 1, 1, 'coba ujian', 1, 15, 'urut', '2020-01-24 17:28:09', '2020-01-29 17:28:11', 'YFZGE');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajar`
--

CREATE TABLE `pengajar` (
  `id_pengajar` int(11) NOT NULL,
  `nip` char(12) NOT NULL,
  `nama_pengajar` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `diklat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pengajar`
--

INSERT INTO `pengajar` (`id_pengajar`, `nip`, `nama_pengajar`, `email`, `diklat_id`) VALUES
(1, '12121212', 'pengajar', 'pengajar@gmail.com', 1),
(2, '13131313', 'pengajar2', 'pengajar2@gmail.com', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(15) NOT NULL,
  `nama_penilaian` varchar(50) NOT NULL,
  `materi_id` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `nama_penilaian`, `materi_id`) VALUES
(1, 'Kebersihan', 1),
(2, 'Kemana', 2),
(3, 'Keamanan', 1),
(4, 'Kedamaian', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nis` char(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `diklat_id` int(11) NOT NULL COMMENT 'kelas&jurusan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `nis`, `email`, `jenis_kelamin`, `diklat_id`) VALUES
(1, 'siswa', '12345678', 'siswa@gmail.com', 'L', 1),
(2, 'siswa2', '11111111', 'siswa2@gmail.com', 'P', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_file`
--

CREATE TABLE `tbl_file` (
  `id` int(15) NOT NULL,
  `filename` varchar(250) NOT NULL,
  `diklat_id` int(15) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tbl_file`
--

INSERT INTO `tbl_file` (`id`, `filename`, `diklat_id`, `create_at`) VALUES
(4, 'WhatsApp_Image_2020-01-17_at_22_14_282.jpeg', 1, '2020-01-26 19:05:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_soal`
--

CREATE TABLE `tb_soal` (
  `id_soal` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `diklat_id` int(11) NOT NULL,
  `bobot` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `tipe_file` varchar(50) NOT NULL,
  `soal` longtext NOT NULL,
  `opsi_a` longtext NOT NULL,
  `opsi_b` longtext NOT NULL,
  `opsi_c` longtext NOT NULL,
  `opsi_d` longtext NOT NULL,
  `opsi_e` longtext NOT NULL,
  `file_a` varchar(255) NOT NULL,
  `file_b` varchar(255) NOT NULL,
  `file_c` varchar(255) NOT NULL,
  `file_d` varchar(255) NOT NULL,
  `file_e` varchar(255) NOT NULL,
  `jawaban` varchar(5) NOT NULL,
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_soal`
--

INSERT INTO `tb_soal` (`id_soal`, `pengajar_id`, `diklat_id`, `bobot`, `file`, `tipe_file`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `file_a`, `file_b`, `file_c`, `file_d`, `file_e`, `jawaban`, `created_on`, `updated_on`) VALUES
(7, 2, 2, 1, '', '', '<p>test soal</p>', '<p>jwb a</p>', '<p>jwb a</p>', '<p>jwb a</p>', '<p>jwb a</p>', '<p>jwb a</p>', '', '', '', '', '', 'B', 1579856478, 1579860521),
(8, 1, 1, 10, '', '', '<p>soal diklat a</p>', '<p>jwb a</p>', '<p>jwb</p>', '<p>jwb c</p>', '<p>jwb d</p>', '<p>jwb e</p>', '', '', '', '', '', 'E', 1579860809, 1579863977),
(9, 1, 1, 10, '', '', '<p>test soal</p>', '<p>asdas</p>', '<p>asfas</p>', '<p>asfas</p>', '<p>safasfas</p>', '<p>asfas</p>', '', '', '', '', '', 'C', 1579863963, 1579863963),
(10, 1, 1, 10, '', '', '<p>soal</p>', '<p>afeqwrqw</p>', '<p>wqrqw</p>', '<p>fasfas</p>', '<p>afasf</p>', '<p>asfasf</p>', '', '', '', '', '', 'A', 1579864002, 1579864002);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'Administrator', '$2y$12$tGY.AtcyXrh7WmccdbT1rOuKEcTsKH6sIUmDr0ore1yN4LnKTTtuu', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1580070132, 1, 'Manager Training', 'Non Railways', 'ADMIN', '0'),
(14, '::1', '12121212', '$2y$10$PAD59dMENTj1iojMEcBayeldZ6xGOACW.z5s393js/lyWmcO.5/gG', 'pengajar@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1579860635, 1580070669, 1, 'pengajar', 'pengajar', NULL, NULL),
(20, '::1', '11111111', '$2y$10$9q3D7OZdu3CHTzkim9UzwOhWi8FXvGz7qRAQNel/u5X9Fih2k6vz6', 'siswa2@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1580066726, 1580070722, 1, 'siswa2', 'siswa2', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(16, 14, 2),
(22, 20, 3);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_evaluasi`
--
ALTER TABLE `detail_evaluasi`
  ADD PRIMARY KEY (`id_detail_evaluasi`);

--
-- Indeks untuk tabel `diklat`
--
ALTER TABLE `diklat`
  ADD PRIMARY KEY (`id_diklat`);

--
-- Indeks untuk tabel `evaluasi`
--
ALTER TABLE `evaluasi`
  ADD PRIMARY KEY (`kd_evaluasi`);

--
-- Indeks untuk tabel `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `h_ujian`
--
ALTER TABLE `h_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ujian_id` (`ujian_id`),
  ADD KEY `mahasiswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indeks untuk tabel `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`);

--
-- Indeks untuk tabel `m_ujian`
--
ALTER TABLE `m_ujian`
  ADD PRIMARY KEY (`id_ujian`),
  ADD KEY `diklat_id` (`diklat_id`) USING BTREE;

--
-- Indeks untuk tabel `pengajar`
--
ALTER TABLE `pengajar`
  ADD PRIMARY KEY (`id_pengajar`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nim` (`nis`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `diklat_id` (`diklat_id`);

--
-- Indeks untuk tabel `tbl_file`
--
ALTER TABLE `tbl_file`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_soal`
--
ALTER TABLE `tb_soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD KEY `matkul_id` (`diklat_id`),
  ADD KEY `dosen_id` (`pengajar_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`),
  ADD UNIQUE KEY `uc_email` (`email`) USING BTREE;

--
-- Indeks untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_evaluasi`
--
ALTER TABLE `detail_evaluasi`
  MODIFY `id_detail_evaluasi` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `diklat`
--
ALTER TABLE `diklat`
  MODIFY `id_diklat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `h_ujian`
--
ALTER TABLE `h_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `m_ujian`
--
ALTER TABLE `m_ujian`
  MODIFY `id_ujian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengajar`
--
ALTER TABLE `pengajar`
  MODIFY `id_pengajar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_file`
--
ALTER TABLE `tbl_file`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_soal`
--
ALTER TABLE `tb_soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `h_ujian`
--
ALTER TABLE `h_ujian`
  ADD CONSTRAINT `h_ujian_ibfk_1` FOREIGN KEY (`ujian_id`) REFERENCES `m_ujian` (`id_ujian`);

--
-- Ketidakleluasaan untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
