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

if (isset($_POST['tambah-akademik'])) {
    $id_guru = $_POST['id_guru'];
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
    $nama_siswa = $siswa['nama'];
    $ukk = $_POST['ukk'] ?? 'NULL';
    $desk_ukk = $_POST['desk_ukk'] ?? 'NULL';
    $id_mapel_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE id_guru = '$id_guru'"))['id_mapel'];
    $cek = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa = $id_siswa AND id_semester = $semester AND id_guru = $id_guru");
    $cek2 = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE guru.id_mapel = '$id_mapel_guru' AND nilai.id_siswa = '$id_siswa'");
    if (mysqli_num_rows($cek2) > 0) {
        echo "<script>alert('Data gagal ditambahkan karena nilai untuk siswa dengan nama ($nama_siswa) sudah ditambahkan oleh guru lain!')</script>";
        echo "<script>document.location.href='../akademik/'</script>";
    } else {
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Data gagal ditambahkan, siswa dengan nama ($nama_siswa) dan semester $semester sudah ada!')</script>";
            echo "<script>document.location.href='../akademik/'</script>";
        } else {
            $query = mysqli_query($koneksi, "INSERT INTO nilai VALUES(NULL,'$id_guru','$id_siswa', '$semester','$harian','$uts','$uas','$nilai_p','$grade_p','$desk_p','$nilai_k','$grade_k','$desk_k',$ukk,'$desk_ukk')");
            if ($query) {
                echo "<script>alert('Data berhasil ditambahkan!')</script>";
                echo "<script>document.location.href='../akademik/'</script>";
            } else {
                echo "<script>alert('Data gagal ditambahkan!')</script>";
                echo "<script>document.location.href='../akademik/'</script>";
            }
        }
    }
} elseif (isset($_POST['tambah-absensi'])) {
    $id_guru = $_POST['id_guru'];
    $siswa = $_POST['nama_siswa'];
    preg_match('/\((.*?)\)/', $siswa, $nis);
    $nis_siswa = $nis[1];
    $siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis_siswa'"));
    $id_siswa = $siswa['id_siswa'];
    $semester = $_POST['semester'];
    $izin = $_POST['izin'];
    $sakit = $_POST['sakit'];
    $alfa = $_POST['alfa'];
    $nama_siswa = $siswa['nama'];
    $id_mapel_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE id_guru = '$id_guru'"))['id_mapel'];
    $cek = mysqli_query($koneksi, "SELECT * FROM absensi INNER JOIN guru ON absensi.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE guru.id_mapel = '$id_mapel_guru' AND absensi.id_siswa = '$id_siswa'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Data gagal ditambahkan karena absensi untuk siswa dengan nama ($nama_siswa) sudah ditambahkan oleh guru lain!')</script>";
        echo "<script>document.location.href='../kehadiran/'</script>";
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO absensi VALUES(NULL,'$izin','$sakit', '$alfa','$id_siswa','$semester','$id_guru')");
        if ($query) {
            echo "<script>alert('Data berhasil ditambahkan!')</script>";
            echo "<script>document.location.href='../kehadiran/'</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan!')</script>";
            echo "<script>document.location.href='../kehadiran/'</script>";
        }
    }
} elseif (isset($_POST['tambah-nilai-ekskul'])) {
    $nama_siswa = $_POST['nama_siswa'];
    $nis = (int) filter_var($nama_siswa, FILTER_SANITIZE_NUMBER_INT);
    $id_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'"))['id_siswa'];
    $id_ekskul = $_POST['id_ekskul'];
    $nilai = $_POST['nilai_ekskul'];
    $ket = $_POST['keterangan'];
    $cek = mysqli_query($koneksi, "SELECT * FROM data_ekskul WHERE id_siswa = '$id_siswa' AND id_ekskul = '$id_ekskul'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nilai siswa dengan Ekskul tersebut sudah ada!')</script>";
        echo "<script>document.location.href='../ekskul/'</script>";
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO data_ekskul VALUES(NULL, '$id_ekskul', '$id_siswa', '$nilai', '$ket')");
        if ($query) {
            echo "<script>alert('Data berhasil ditambahkan!')</script>";
            echo "<script>document.location.href='../ekskul/'</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan!')</script>";
            echo "<script>document.location.href='../ekskul/'</script>";
        }
    }
} else {
    header("Location: ../");
}
