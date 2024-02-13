<?php
include '../../../koneksi.php';
include './FPDF-master/fpdf.php';
class PDF extends FPDF
{
    function Header()
    {
        // Mengatur font dan ukuran teks
        $this->SetFont('Arial', 'B', 16);

        // Menulis teks di posisi X dan Y saat ini
        $this->Cell(0, 10, 'Judul Header', 0, 1, 'C');

        // Menyimpan posisi X dan Y saat ini
        $x = $this->GetX();
        $y = $this->GetY();

        // Mengatur posisi baru
        $this->SetXY($x, $y + 10);
    }

    function Footer()
    {
        // Mengatur font dan ukuran teks
        $this->SetFont('Arial', 'I', 10);

        // Menyimpan posisi X dan Y saat ini
        $x = $this->GetX();
        $y = $this->GetY();

        // Menulis teks di posisi X dan Y saat ini
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');

        // Mengatur posisi baru
        $this->SetXY($x, $y - 10);
    }
}

session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (!in_array($_SESSION['role'], [3])) {
    header("location:../../../");
    session_destroy();
}
$semester = $_SESSION['semester'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_role = $role;
$user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE siswa.nama = '$username'");
$sekolah = mysqli_query($koneksi, "SELECT * FROM informasi_sekolah");
$data_sekolah = mysqli_fetch_assoc($sekolah);
$data_user = mysqli_fetch_assoc($user);
$id_siswa = $data_user['id_siswa'];
$id_walas = $data_user['id_kelas'];
$walas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas INNER JOIN guru ON kelas.wali_kelas = guru.id_guru WHERE id_kelas = '$id_walas'"));

$mapel = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE nilai.id_siswa = '$id_siswa'");
$jumlah = mysqli_num_rows($mapel);
$no = 1;
$absensi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_siswa = '$id_siswa'"));
$ekskul = mysqli_query($koneksi, "SELECT * FROM data_ekskul INNER JOIN ekskul ON data_ekskul.id_ekskul = ekskul.id_ekskul WHERE id_siswa = '$id_siswa'");
$pdf = new FPDF();
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(true, 10);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTopMargin(10);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);

/* --- Text --- */
$pdf->SetFont('', 'B', 24);
$pdf->Text(57, 20, 'Laporan Nilai Siswa');
/* --- Text --- */
$pdf->Text(48, 28, $data_sekolah['nama_sekolah']);
/* --- Text --- */
$pdf->SetFontSize(8);
$pdf->Text(17, 33, $data_sekolah['alamat_sekolah']);
/* --- Text --- */
$pdf->Text(44, 38, 'Kontak Sekolah : ' . $data_sekolah['notelp_sekolah'] . '|' . $data_sekolah['email_sekolah'] .  ' | ' . $data_sekolah['website_sekolah']);
/* --- Text --- */
$pdf->SetFontSize(12);
$pdf->Text(12, 50, 'Nama :' . $data_user['nama']);
$pdf->Text(12, 55, 'NIS :' . $data_user['nis']);
$pdf->Text(12, 60, 'Kelas : ' . ' ' . $data_user['tingkatan'] . ' ' . $data_user['jurusan'] . ' ' . $data_user['nama_kelas']);
$semester =  $semester == "1" ? 'Ganjil' : 'Genap';
$pdf->Text(12, 65, 'Semester :' . $semester);
$pdf->Line(5, 74, 205, 74);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Text(62, 81, 'Pencapaian Kompetensi Peserta Didik');
$pdf->Text(5, 89, 'Nilai Akademik dan Kejuruan');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(5, 90);
$pdf->Cell(10, 8, 'NO', 1);
$pdf->Cell(40, 8, 'Mata Pelajaran', 1);
$pdf->Cell(30, 8, 'Pengetahuan', 1);
$pdf->Cell(20, 8, 'Predikat', 1);
$pdf->Cell(30, 8, 'Deskripsi', 1);
$pdf->Cell(30, 8, 'Keterampilan', 1);
$pdf->Cell(20, 8, 'Predikat', 1);
$pdf->Cell(20, 8, 'Deskripsi', 1);
$pdf->Ln();
$loc = 10;
$a = 1;
foreach ($mapel as $m) {
    $pdf->SetX(5);
    $pdf->Cell(10, 8, $a, 1);
    $pdf->Cell(40, 8, $m['mapel'] ?? '-', 1);
    $pdf->Cell(30, 8, $m['nilai_pengetahuan'] ?? '-', 1);
    $pdf->Cell(20, 8, $m['grade_pengetahuan'] ?? '-', 1);
    $pdf->Cell(30, 8, $m['desk_pengetahuan'] ?? '-', 1);
    $pdf->Cell(30, 8, $m['nilai_keterampilan'] ?? '-', 1);
    $pdf->Cell(20, 8, $m['grade_ket'] ?? '-', 1);
    $pdf->Cell(20, 8, $m['desk_ket'] ?? '-', 1);
    $pdf->Ln();
    $a++;
}
$pdf->Ln();
$pdf->SetX(5);
$pdf->Cell(10, 8, 'NO', 1);
$pdf->Cell(40, 8, 'EKSKUL', 1);
$pdf->Cell(30, 8, 'NILAI', 1);
$pdf->Cell(50, 8, 'KETERANGAN', 1);
$pdf->Ln();
foreach ($ekskul as $e) {
    $pdf->SetX(5);
    $pdf->Cell(10, 8, $a, 1);
    $pdf->Cell(40, 8, $e['nama_ekskul']    ?? '-', 1);
    $pdf->Cell(30, 8, $e['nilai'] ?? '-', 1);
    $pdf->Cell(50, 8, $e['keterangan'] ?? '-', 1);
    $pdf->Ln();
    $a++;
}
$pdf->Ln();
$pdf->SetX(5);
$pdf->Cell(50, 8, 'SAKIT', 1);
$pdf->Cell(20, 8, $absensi['sakit'] ?? '-', 1);
$pdf->Ln();
$pdf->SetX(5);
$pdf->Cell(50, 8, 'IZIN', 1);
$pdf->Cell(20, 8, $absensi['izin']  ?? '-', 1);
$pdf->Ln();
$pdf->SetX(5);
$pdf->Cell(50, 8, 'TANPA KETERANGAN', 1);
$pdf->Cell(20, 8, $absensi['alfa']  ?? '-', 1);
$pdf->Ln();

$pdf->Footer();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 10, "Mengetahui", 0, 1);
$pdf->Cell(0, 0, "Orang Tua/Wali,", 0, 1);
$y = $pdf->GetY();
$pdf->SetY($y - 10);
$pdf->SetX(150);
$pdf->Cell(50, 10, "Mengetahui", 0, 1);
$pdf->SetY($y);
$pdf->SetX(150);
$pdf->Cell(0, 0, "Wali Kelas,", 0, 1);
$pdf->SetY($y + 20);
$pdf->SetX(150);
$pdf->Cell(0, 10, $walas['nama'], 0, 1);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Line($x, $y, $x + 40, $y);
$pdf->Line(150, $y, 200, $y);
$pdf->SetX(150);
$pdf->Cell(0, 10, $walas['nip'], 0, 1);
$pdf->Ln();
$pdf->SetX(100);
$pdf->Cell(50, 10, "Mengetahui", 0, 1);
$pdf->SetX(97);
$pdf->Cell(0, 0, "Kepala Sekolah", 0, 1);
$pdf->SetY($y + 50);
$pdf->SetX(87);
$pdf->Cell(0, 10, $data_sekolah['kepala_sekolah'], 0, 1);
$y = $pdf->GetY();
$pdf->Line(85, $y, 135, $y);
$pdf->SetX(87);
$pdf->Cell(0, 10, $data_sekolah['nip'], 0, 1);


$pdf->Output('created_pdf.pdf', 'I');
