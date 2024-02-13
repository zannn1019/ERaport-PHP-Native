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
if (isset($_POST['tambah-ekskul'])) {
    $ekskul = $_POST['nama_ekskul'];
    $pembina = $_POST['pembina'];
    $nip = (int) filter_var($pembina, FILTER_SANITIZE_NUMBER_INT);
    $id_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$nip'"))['id_guru'];
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ekskul WHERE nama_ekskul LIKE '%$ekskul%'"));
    @$data = $cek['ekskul'] . ' dengan ' . $cek['pembina'];
    if ($cek == null) {
        $ekskul_query = mysqli_query($koneksi, "INSERT INTO ekskul VALUES(NULL,'$ekskul','$id_guru')");
        if ($ekskul_query) {
            echo "<script>alert('Data berhasil ditambahkan!')</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan!')</script>";
        }
        echo "<script>document.location.href='../ekskul/'</script>";
    } else {
        echo "<script>alert('Ekskul $data sudah ada!')</script>";
        echo "<script>document.location.href='../ekskul/'</script>";
    }
}