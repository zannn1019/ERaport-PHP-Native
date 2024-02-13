<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['edit-siswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $target_dir = "../../../asset/img/siswa/";
    if ($_FILES['foto']['name'] != '') {
        $target_file = $target_dir . time() . '_' . $_FILES["foto"]["name"];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $foto = $target_file;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    } else {
        $data_foto = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"));
        $foto = $data_foto['foto'];
    }
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $kelas = $_POST['kelas'];
    $gender = $_POST['gender'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $ortu = $_POST['nama_ortu'];
    $agama = $_POST['agama'];
    @$nis = $_POST['nis'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    if ($nis != null) {
        $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'");
        if (mysqli_num_rows($cek_nis) > 0) {
            echo "<script>alert('NIS sudah digunakan!');</script>";
            echo "<script> document.location.href='../siswa/' </script>";
        } else {
            $query = mysqli_query($koneksi, "UPDATE `siswa` SET `nama`='$nama',`nis`='$nis',`foto`='$foto',`email`='$email',`password`='$password',`id_kelas`='$kelas',`gender`='$gender',`no_telp`='$no_telp',`alamat`='$alamat',`nama_ortu`='$ortu',agama='$agama', status = '$status' WHERE id_siswa = '$id_siswa'");
            if ($query) {
                echo "<script> alert('Data berhasil diubah!'); </script>";
                echo "<script> document.location.href='../siswa/' </script>";
            } else {
                echo "<script> alert('Data gagal diubah!'); </script>";
                echo "<script> document.location.href='../siswa/' </script>";
            }
        }
    } else {
        $query = mysqli_query($koneksi, "UPDATE `siswa` SET `nama`='$nama',`foto`='$foto',`email`='$email',`password`='$password',`id_kelas`='$kelas',`gender`='$gender',`no_telp`='$no_telp',`alamat`='$alamat',`nama_ortu`='$ortu',agama='$agama', status = '$status' WHERE id_siswa = '$id_siswa'");
        if ($query) {
            echo "<script> alert('Data berhasil diubah!'); </script>";
            echo "<script> document.location.href='../siswa/' </script>";
        } else {
            echo "<script> alert('Data gagal diubah!'); </script>";
            echo "<script> document.location.href='../siswa/' </script>";
        }
    }
} elseif (isset($_POST['edit-guru'])) {
    $id_guru = $_POST['id_guru'];
    $target_dir = "../../../asset/img/guru/";
    if ($_FILES['foto']['name'] != '') {
        $target_file = $target_dir . time() . '_' . $_FILES["foto"]["name"];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $foto = $target_file;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    } else {
        $data_foto = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE id_guru = '$id_guru'"));
        $foto = $data_foto['foto'];
    }
    $nama = $_POST['nama'];
    @$nip = $_POST['nip'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $mapel = $_POST['mapel'];
    $gender = $_POST['gender'];

    if ($nip != null) {
        $cek_nip = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$nip'");
        if (mysqli_num_rows($cek_nip) > 0) {
            echo "<script>alert('NIP sudah digunakan!');</script>";
            echo "<script> document.location.href='../guru/' </script>";
        } else {
            $query = mysqli_query($koneksi, "UPDATE `guru` SET `nama`='$nama',`nip`='$nip',`foto`='$foto',`email`='$email',`password`='$password',`id_mapel`='$mapel',`gender`='$gender',`no_telp`='$no_telp',`alamat`='$alamat',agama='$agama' WHERE id_guru = '$id_guru'");
            if ($query) {
                echo "<script> alert('Data berhasil diubah!'); </script>";
                echo "<script> document.location.href='../guru/' </script>";
            } else {
                echo "<script> alert('Data gagal diubah!'); </script>";
                echo "<script> document.location.href='../guru/' </script>";
            }
        }
    } else {
        $query = mysqli_query($koneksi, "UPDATE `guru` SET `nama`='$nama',`foto`='$foto',`email`='$email',`password`='$password',`id_mapel`='$mapel',`gender`='$gender',`no_telp`='$no_telp',`alamat`='$alamat',agama='$agama' WHERE id_guru = '$id_guru'");
        if ($query) {
            echo "<script> alert('Data berhasil diubah!'); </script>";
            echo "<script> document.location.href='../guru/' </script>";
        } else {
            echo "<script> alert('Data gagal diubah!'); </script>";
            echo "<script> document.location.href='../guru/' </script>";
        }
    }
} else {
    header("Location: ../../");
}
