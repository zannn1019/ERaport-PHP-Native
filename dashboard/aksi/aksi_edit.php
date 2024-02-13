<?php
include '../../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:./../../");
    session_destroy();
}

if (isset($_POST['edit-mapel'])) {
    $id_mapel = $_POST['id_mapel'];
    $mapel = $_POST['mapel'];
    $jenis = $_POST['jenis-mapel'];
    $query = mysqli_query($koneksi, "UPDATE mapel SET mapel='$mapel',jenis = '$jenis' WHERE id_mapel = '$id_mapel'");
    if ($query) {
        echo "<script> alert('Data berhasil diubah!'); </script>";
        echo "<script> document.location.href='../mapel/' </script>";
    } else {
        echo "<script> alert('Data gagal diubah!'); </script>";
        echo "<script> document.location.href='../mapel/' </script>";
    }
} elseif (isset($_POST['edit-kelas'])) {
    $id_kelas = $_POST['id_kelas'];
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];
    $kelas = $_POST['kelas'];
    $wali_kelas = $_POST['wali_kelas'];
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE id_kelas = '$id_kelas'"));
    $id_wali = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE nama='$wali_kelas'"));
    $walas = $id_wali['id_guru'];
    try {
        $query = mysqli_query($koneksi, "UPDATE kelas SET tingkatan = '$tingkat',id_jurusan = '$jurusan',nama_kelas='$kelas',wali_kelas='$walas' WHERE id_kelas = '$id_kelas'");
        if ($query) {
            echo "<script> alert('Data berhasil diubah!'); </script>";
            echo "<script> document.location.href='../kelas/' </script>";
        } else {
            echo "<script> alert('Data gagal diubah!'); </script>";
            echo "<script> document.location.href='../kelas/' </script>";
        }
    } catch (Exception $th) {
        echo "<script> alert('Kelas " . $cek['tingkatan'] . ' ' . $cek['jurusan'] . ' ' . $cek['nama_kelas'] . " sudah ada!'); </script>";
        echo "<script> document.location.href='../kelas/' </script>";
    }
} elseif (isset($_POST['edit-ekskul'])) {
    $id = $_POST['id_ekskul'];
    $ekskul = $_POST['nama_ekskul'];
    $pembina = $_POST['pembina'];
    $nip = (int) filter_var($pembina, FILTER_SANITIZE_NUMBER_INT);
    $id_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$nip'"))['id_guru'];
    $edit = mysqli_query($koneksi, "UPDATE ekskul SET nama_ekskul = '$ekskul', id_guru = '$id_guru' WHERE id_ekskul = '$id'");
    if ($edit) {
        echo "<script> alert('Data berhasil diubah!'); </script>";
        echo "<script> document.location.href='../ekskul/' </script>";
    } else {
        echo "<script> alert('Data gagal diubah!'); </script>";
        echo "<script> document.location.href='../ekskul/' </script>";
    }
}
