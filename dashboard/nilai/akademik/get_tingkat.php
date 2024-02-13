<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['nama'])) {
    @$nama = $_POST['nama'];
    @$nis = (int) filter_var($nama, FILTER_SANITIZE_NUMBER_INT);
    @$tingkat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas WHERE nis = '$nis'"))['tingkatan'];
    echo $tingkat;
}
