<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1, 2])) {
    header("location:../../../");
    session_destroy();
}

$nilai = $_GET['nilai'];
$id = $_GET['id'];

if ($nilai == "absensi") {
    $query = mysqli_query($koneksi, "DELETE FROM absensi WHERE id_absen = '$id'");
    $nilai = "kehadiran";
} elseif ($nilai == "akademik") {
    $query = mysqli_query($koneksi, "DELETE FROM nilai WHERE id_nilai = '$id'");
} elseif ($nilai == "ekskul") {
    $id_ekskul = $_GET['ekskul'];
    $query = mysqli_query($koneksi, "DELETE FROM data_ekskul WHERE id_siswa = '$id' AND id_ekskul = '$id_ekskul'");
} else {
    header("Location: ../$nilai");
}

if ($query) {
    echo "<script>alert('Data berhasil dihapus!')</script>";
    echo "<script>document.location.href = '../$nilai/'</script>";
} else {
    echo "<script>alert('Data gagal dihapus!')</script>";
    echo "<script>document.location.href = '../$nilai/'</script>";
}
