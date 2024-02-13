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

$mapel = mysqli_query($koneksi, "DELETE FROM mapel WHERE id_mapel = '$id'");
if ($mapel) {
    echo "<script>alert('Data berhasil dihapus!')</script>";
    echo "<script>document.location.href = '../mapel/'</script>";
} else {
    echo "<script>alert('Data gagal dihapus!')</script>";
    echo "<script>document.location.href = '../mapel/'</script>";
}
