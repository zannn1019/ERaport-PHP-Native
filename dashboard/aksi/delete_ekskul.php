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

$data_eskul = mysqli_query($koneksi, "DELETE FROM data_ekskul WHERE id_ekskul = '$id'");
if ($data_eskul) {
    $ekskul = mysqli_query($koneksi, "DELETE FROM ekskul WHERE id_ekskul = '$id'");
    if ($ekskul) {
        echo "<script>alert('Data berhasil dihapus!')</script>";
        echo "<script>document.location.href = '../ekskul/'</script>";
    } else {
        echo "<script>alert('Data gagal dihapus!')</script>";
        echo "<script>document.location.href = '../ekskul/'</script>";
    }
}
