<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [2])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['edit-akademik'])) {
    $id_nilai = $_POST['id_nilai'];
    $siswa = $_POST['nama_siswa'];
    preg_match('/\((.*?)\)/', $siswa, $nis);
    $nis_siswa = $nis[1];
    $siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis_siswa'"));
    $id_siswa = $siswa['id_siswa'];
    $semester = $_POST['semester'];
    $harian = $_POST['harian'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];
    $nilai_p = $_POST['nilai-p'];
    $grade_p = $_POST['grade-p'];
    $desk_p = $_POST['desk-p'];
    $nilai_k = $_POST['nilai-k'];
    $grade_k = $_POST['grade-k'];
    $desk_k = $_POST['desk-k'];
    $ukk = $_POST['ukk'];
    $desk_ukk = $_POST['desk-ukk'];
    $query = mysqli_query($koneksi, "UPDATE nilai SET id_semester = '$semester', nilai_harian = '$harian',nilai_uts = '$uts', nilai_uas = '$uas', nilai_pengetahuan = '$nilai_p',grade_pengetahuan = '$grade_p',desk_pengetahuan = '$desk_p', nilai_keterampilan = '$nilai_k',grade_ket = '$grade_k',desk_ket = '$desk_k', nilai_ukk = '$ukk', desk_ukk = '$desk_ukk' WHERE id_nilai = '$id_nilai'");
    if ($query) {
        echo "<script>alert('Data berhasil diubah!')</script>";
        echo "<script>document.location.href='../akademik/'</script>";
    } else {
        echo "<script>alert('Data gagal diubah!')</script>";
        echo "<script>document.location.href='../akademik/'</script>";
    }
} elseif (isset($_POST['edit-absensi'])) {
    $id_absen = $_POST['id_absen'];
    $siswa = $_POST['nama_siswa'];
    preg_match('/\((.*?)\)/', $siswa, $nis);
    $nis_siswa = $nis[1];
    $siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis_siswa'"));
    $id_siswa = $siswa['id_siswa'];
    $semester = $_POST['semester'];
    $izin = $_POST['izin'];
    $sakit = $_POST['sakit'];
    $alfa = $_POST['alfa'];
    $query = mysqli_query($koneksi, "UPDATE absensi SET izin = '$izin',sakit = '$sakit', alfa = '$alfa',id_siswa = '$id_siswa',id_semester = '$semester'");
    if ($query) {
        echo "<script>alert('Data berhasil diubah!')</script>";
        echo "<script>document.location.href='../kehadiran/'</script>";
    } else {
        echo "<script>alert('Data gagal diubah!')</script>";
        echo "<script>document.location.href='../kehadiran/'</script>";
    }
} elseif (isset($_POST['edit-nilai-ekskul'])) {
    $nama_siswa = $_POST['nama_siswa'];
    $nis = (int) filter_var($nama_siswa, FILTER_SANITIZE_NUMBER_INT);
    $id_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'"))['id_siswa'];
    $id_data = $_POST['id_ekskul'];
    $nilai = $_POST['nilai_ekskul'];
    $ket = $_POST['keterangan'];
    $query = mysqli_query($koneksi, "UPDATE data_ekskul SET id_siswa = '$id_siswa',nilai = '$nilai', keterangan = '$ket' WHERE id_data = '$id_data'");
    if ($query) {
        echo "<script>alert('Data berhasil diubah!')</script>";
        echo "<script>document.location.href='../ekskul/'</script>";
    } else {
        echo "<script>alert('Data gagal diubah!')</script>";
        echo "<script>document.location.href='../eksku;/'</script>";
    }
} else {
    header("Location: ../");
}
