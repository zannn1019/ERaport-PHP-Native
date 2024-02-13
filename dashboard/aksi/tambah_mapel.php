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
if (isset($_POST['tambah-mapel'])) {
    $mapel = $_POST['mapel'];
    $jenis = $_POST['jenis-mapel'];

    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mapel WHERE mapel LIKE '%$mapel%'"));
    @$data = $cek['mapel'] . ' dengan jenis ' . $cek['jenis'];
    try {
        $mapel_query = mysqli_query($koneksi, "INSERT INTO mapel VALUES(NULL,'$mapel','$jenis')");
        if ($mapel_query) {
            echo "<script>alert('Data berhasil ditambahkan!')</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan!')</script>";
        }
        echo "<script>document.location.href='../mapel/'</script>";
    } catch (Exception $th) {
        echo "<script>alert('Mapel $data sudah ada!')</script>";
        echo "<script>document.location.href='../mapel/'</script>";
    }
}
