<?php
include '../../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:../../../");
    session_destroy();
}
$id = $_GET['id'];

$siswa = mysqli_query($koneksi, "DELETE FROM siswa WHERE id_kelas = '$id'");
if ($siswa) {
    $kelas = mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas = '$id'");
    if ($kelas) {
        $absen = mysqli_query($koneksi, "DELETE FROM absensi WHERE id_siswa IN (SELECT id_siswa FROM siswa WHERE id_kelas = '$id')");
        if ($absen) {
            $nilai = mysqli_query($koneksi, "DELETE FROM nilai WHERE id_siswa IN (SELECT id_siswa FROM siswa WHERE id_kelas = '$id')");
            if ($nilai) {
                echo "<script>alert('Data berhasil dihapus!')</script>";
                echo "<script>document.location.href = '../kelas/'</script>";
            } else {
                echo "<script>alert('Data gagal dihapus!')</script>";
                echo "<script>document.location.href = '../kelas/'</script>";
            }
        }
    }
}
