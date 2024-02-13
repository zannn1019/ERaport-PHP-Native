<?php
include 'koneksi.php';
session_start();

if (isset($_POST['login-admin'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $query = mysqli_query($koneksi, $sql);
    $data = mysqli_fetch_array($query);
    if (mysqli_num_rows($query) != 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $data['id_role'];
        $tahun_sekarang = date("Y");
        $data_siswa = mysqli_query($koneksi, "SELECT * FROM siswa");
        foreach ($data_siswa as $siswa) {
            $tahun_ajar_siswa = $siswa['tahun_ajaran'];
            $tahun_ajar = explode("-", $tahun_ajar_siswa);
            $tahun_masuk = $tahun_ajar[0];
            $tahun_keluar = $tahun_ajar[1];
            $siswa_lulus = $tahun_ajar[0] + 3;
            $id_siswa = $siswa['id_siswa'];
            if ($siswa_lulus <= $tahun_sekarang) {
                $status = mysqli_query($koneksi, "UPDATE siswa SET status = 'Nonaktif' WHERE id_siswa = '$id_siswa'");
            }
        }
        echo "<script> alert('Login berhasil'); </script>";
        echo "<script> document.location.href='./dashboard' </script>";
    } else {
        echo "<script> alert('Login Gagal'); </script>";
        echo "<script> document.location.href='./' </script>";
        session_destroy();
    }
} elseif (isset($_POST['login-siswa'])) {
    $nis = $_POST['nis'];
    $password = $_POST['password'];
    $sql = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis' AND password = '$password'");
    $data = mysqli_fetch_array($sql);
    if (mysqli_num_rows($sql) != 0) {
        $_SESSION['username'] = $data['nama'];
        $_SESSION['role'] = $data['id_role'];
        $_SESSION['semester'] = $_POST['semester'];
        echo "<script> alert('Login berhasil'); </script>";
        echo "<script> document.location.href='./dashboard' </script>";
    } else {
        echo "<script> alert('Login Gagal'); </script>";
        echo "<script> document.location.href='./' </script>";
        session_destroy();
    }
} elseif (isset($_POST['login-guru'])) {
    $nip = $_POST['nip'];
    $password = $_POST['password'];
    $sql = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$nip' AND password = '$password'");
    $data = mysqli_fetch_array($sql);
    if (mysqli_num_rows($sql) != 0) {
        $_SESSION['username'] = $data['nama'];
        $_SESSION['id_guru'] = $data['id_guru'];
        $_SESSION['role'] = $data['id_role'];
        echo "<script> alert('Login berhasil'); </script>";
        echo "<script> document.location.href='./dashboard' </script>";
    } else {
        echo "<script> alert('Login Gagal'); </script>";
        echo "<script> document.location.href='.//' </script>";
        session_destroy();
    }
}