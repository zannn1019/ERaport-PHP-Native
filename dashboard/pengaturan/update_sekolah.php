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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    @$nama_sekolah = $_POST['nama_sekolah'];
    @$kepsek = $_POST['kepsek'];
    @$nip = $_POST['nip'];
    @$alamat_sekolah = $_POST['alamat_sekolah'];
    @$no_sekolah = $_POST['no_sekolah'];
    @$email_sekolah = $_POST['email_sekolah'];
    @$website_sekolah = $_POST['website_sekolah'];
    $query = mysqli_query($koneksi, "UPDATE informasi_sekolah SET 
            nama_sekolah = '$nama_sekolah',
            alamat_sekolah = '$alamat_sekolah',
            notelp_sekolah = '$no_sekolah',
            email_sekolah = '$email_sekolah',
            website_sekolah = '$website_sekolah',
            kepala_sekolah = '$kepsek',
            nip = '$nip'
            WHERE id_info = '1'
        ");
    if ($query) {
        echo "1";
    } else {
        echo "0";
    }
}
