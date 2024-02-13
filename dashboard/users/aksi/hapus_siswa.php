<?php

include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:../../../");
    session_destroy();
}
$id = $_GET['id'];

$nilai = mysqli_query($koneksi, "DELETE FROM nilai WHERE id_siswa = '$id'");

if ($nilai) {
    $absen = mysqli_query($koneksi, "DELETE FROM absensi WHERE id_siswa = '$id'");
    if ($absen) {
        $siswa = mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa = '$id'");
        if ($siswa) {
            echo "<script>alert('Data berhasil dihapus!')</script>";
            echo "<script>document.location.href = '../siswa/'</script>";
        } else {
            echo "<script>alert('Data gagal dihapus!')</script>";
            echo "<script>document.location.href = '../siswa/'</script>";
        }
    }
}
