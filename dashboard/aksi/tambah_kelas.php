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
if (isset($_POST['tambah-kelas'])) {
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];
    $kelas = $_POST['kelas'];
    $walas = $_POST['walas'];
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE tingkatan = '$tingkat' AND kelas.id_jurusan = '$jurusan' AND nama_kelas = '$kelas'"));
    $id_walas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_guru FROM guru WHERE nama = '$walas'"));
    $id = $id_walas['id_guru'] ?? null;
    if ($walas == null) {
        $kelas_query = mysqli_query($koneksi, "INSERT INTO kelas VALUES(NULL,'$tingkat','$jurusan','$kelas',NULL)");
    } else {
        $kelas_query = mysqli_query($koneksi, "INSERT INTO kelas VALUES(NULL,'$tingkat','$jurusan','$kelas','$id')");
    }
    if ($kelas_query) {
        echo "<script>alert('Data berhasil ditambahkan!')</script>";
    } else {
        echo "<script>alert(' " . $cek['tingkatan'] . ' ' . $cek['jurusan'] . ' ' . $cek['nama_kelas'] . " sudah ada!')</script>";
        echo "<script>document.location.href='../kelas/'</script>";
    }
    echo "<script>document.location.href='../kelas/'</script>";
}
