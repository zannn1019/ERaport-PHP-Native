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
$nilai = mysqli_query($koneksi, "DELETE FROM nilai WHERE id_guru = '$id'");
if ($nilai) {
    $absen = mysqli_query($koneksi, "DELETE FROM absensi WHERE id_guru = '$id'");
    if ($absen) {
        $wali_kelas = mysqli_query($koneksi, "UPDATE kelas SET wali_kelas = null WHERE wali_kelas = '$id'");
        if ($wali_kelas) {
            $guru = mysqli_query($koneksi, "DELETE FROM guru WHERE id_guru = '$id'");
            if ($guru) {
                echo "<script>alert('Data berhasil dihapus!')</script>";
                echo "<script>document.location.href = '../guru/'</script>";
            } else {
                echo "<script>alert('Data gagal dihapus!')</script>";
                echo "<script>document.location.href = '../guru/'</script>";
            }
        }
    }
}
