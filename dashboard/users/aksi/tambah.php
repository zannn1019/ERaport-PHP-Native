<?php
include '../../../koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:../../../");
    session_destroy();
}
if (isset($_POST['tambah-siswa'])) {
    $nama = $_POST['nama'];
    $nis = $_POST['nis'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $kelas = $_POST['kelas'];
    $gender = $_POST['gender'];
    $telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $nama_ortu = $_POST['nama_ortu'];
    $agama = $_POST['agama'];

    $target_dir = "../../../asset/img/siswa/";
    $target_file = $target_dir . time() . '_' . basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $foto = $target_file;
    $tahun_ajar = date("Y") . '-' . date("Y", strtotime("+1 year"));
    $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'");
    if (mysqli_num_rows($cek_nis) > 0) {
        echo "<script>alert('NIS sudah digunakan!');</script>";
        echo "<script> document.location.href='../siswa/' </script>";
    } else {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO siswa 
            VALUES(null,'$nama','$nis','$foto','$email','$password','$kelas','3','$gender','$agama',CURRENT_TIMESTAMP,'$telp','$alamat','$nama_ortu','1','$tahun_ajar')";
            if (mysqli_query($koneksi, $query)) {
                echo "<script> alert('Data berhasil ditambahkan!'); </script>";
                echo "<script> document.location.href='../siswa/' </script>";
            } else {
                echo "<script> alert('Data gagal ditambahkan!'); </script>";
                echo "<script> document.location.href='../siswa/' </script>";
            }
        }
    }
} elseif (isset($_POST['tambah-guru'])) {
    $nama = $_POST['nama'];
    $nip = $_POST['nip'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mapel = $_POST['mapel'];
    $gender = $_POST['gender'];
    $telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];

    $target_dir = "../../../asset/img/guru/";
    $target_file = $target_dir . time() . '_' . basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $foto = $target_file;
    $cek_nip = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$nip'");
    if (mysqli_num_rows($cek_nip) > 0) {
        echo "<script>alert('NIP sudah digunakan!');</script>";
        echo "<script> document.location.href='../guru/' </script>";
    } else {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $query = " INSERT INTO guru 
            VALUES(null,'$nama','$foto','$nip','$email','$password','$mapel','2','$gender','$agama',CURRENT_TIMESTAMP,'$telp','$alamat')";
            if (mysqli_query($koneksi, $query)) {
                echo "<script> alert('Data berhasil ditambahkan!'); </script>";
                echo "<script> document.location.href='../guru/' </script>";
            } else {
                echo "<script> alert('Data gagal ditambahkan!'); </script>";
                echo "<script> document.location.href='../guru/' </script>";
            }
        }
    }
} else {
    header("Location:../../");
}
